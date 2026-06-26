<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ChatLog;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    /**
     * Xử lý tin nhắn chat từ CaféAI widget
     */
    public function handle(Request $request): JsonResponse
    {
        $request->validate([
            'message'    => ($request->input('action', 'chat') === 'chat') ? 'required|string|max:1000' : 'nullable|string|max:1000',
            'action'     => 'nullable|string',
            'product_id' => 'required_if:action,add_to_cart|integer',
            'quantity'   => 'nullable|integer|min:1',
        ]);

        $message   = trim($request->input('message'));
        $action    = $request->input('action', 'chat');
        $user      = $request->user(); // nullable (guest)
        $sessionId = $request->session()->getId();

        try {
            return match ($action) {
                'greeting'    => $this->handleGreeting($user),
                'weather'     => $this->handleWeather(),
                'add_to_cart' => $this->handleAddToCart($request),
                default       => $this->handleChat($message, $user, $sessionId),
            };
        } catch (\Exception $e) {
            Log::error('CaféAI Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi, vui lòng thử lại sau.',
            ], 500);
        }
    }

    // ─────────────────────────────────────────────
    // GREETING
    // ─────────────────────────────────────────────

    private function handleGreeting(?object $user): JsonResponse
    {
        $name    = $user ? " {$user->name}" : '';
        $weather = $this->getWeather();

        $weatherInfo = '';
        if ($weather) {
            $weatherInfo = "\n\nThời tiết tại {$weather['city']}: {$weather['temp']}°C, {$weather['description']}";
        }

        $greeting = config('services.cafeai.greeting_vi',
            "Xin chào{$name}! Tôi là CaféAI, trợ lý ảo của XDTHECOFFEEHOUSE.\n\n" .
            "Tìm đồ uống phù hợp\nTheo dõi đơn hàng\nĐặt hàng nhanh\nGợi ý theo thời tiết\n\n" .
            "Bạn cần gì hôm nay?"
        );

        return response()->json([
            'success'     => true,
            'message'     => $greeting . $weatherInfo,
            'suggestions' => $this->getSuggestions('greeting'),
            'weather'     => $weather,
            'is_logged_in'=> (bool) $user,
            'user_name'   => $user?->name,
        ]);
    }

    // ─────────────────────────────────────────────
    // MAIN CHAT HANDLER
    // ─────────────────────────────────────────────

    private function handleChat(string $message, ?object $user, string $sessionId): JsonResponse
    {
        $language = $this->detectLanguage($message);
        $intent   = $this->detectIntent($message, $language);

        // Log user message
        ChatLog::create([
            'user_id'    => $user?->id,
            'session_id' => $sessionId,
            'role'       => 'user',
            'message'    => $message,
            'intent'     => $intent,
            'language'   => $language,
        ]);

        // Try local processing first
        $response = $this->processLocally($intent, $message, $language, $user);

        // Fallback to Gemini API for general chat
        if ($response === null) {
            $response = $this->callGeminiAPI($message, $language, $user, $sessionId);
        }

        // Log AI response
        ChatLog::create([
            'user_id'    => $user?->id,
            'session_id' => $sessionId,
            'role'       => 'assistant',
            'message'    => $response['message'],
            'intent'     => $intent,
            'language'   => $language,
            'metadata'   => $response['metadata'] ?? null,
        ]);

        return response()->json([
            'success'     => true,
            'message'     => $response['message'],
            'products'    => $response['products']    ?? [],
            'suggestions' => $response['suggestions'] ?? [],
            'cart_action' => $response['cart_action'] ?? null,
            'intent'      => $intent,
            'language'    => $language,
        ]);
    }

    // ─────────────────────────────────────────────
    // WEATHER
    // ─────────────────────────────────────────────

    private function handleWeather(): JsonResponse
    {
        $weather     = $this->getWeather();
        $suggestions = $this->getWeatherSuggestions($weather);

        return response()->json([
            'success'  => true,
            'weather'  => $weather,
            'message'  => $suggestions['message'],
            'products' => $suggestions['products'],
        ]);
    }

    /**
     * Thêm sản phẩm vào giỏ hàng từ CaféAI
     */
    private function handleAddToCart(Request $request): JsonResponse
    {
        $productId = $request->input('product_id');
        $quantity = (int) $request->input('quantity', 1);

        $product = Product::find($productId);
        if (!$product) {
            return response()->json([
                'success' => false,
                'error'   => 'Sản phẩm không tồn tại.',
            ]);
        }

        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'error'   => 'Sản phẩm không còn kinh doanh.',
            ]);
        }

        if ($product->stock < 1) {
            return response()->json([
                'success' => false,
                'error'   => 'Sản phẩm đã hết hàng.',
            ]);
        }

        $cart = session('cart', []);
        
        // Key duy nhất cho mỗi combination (product + size + modifiers)
        // Chatbot thêm trực tiếp mặc định không có size/modifier
        $key = 'p' . $product->id . '_ns_0';

        if (isset($cart[$key])) {
            $newQty = $cart[$key]['quantity'] + $quantity;
            if ($newQty > $product->stock) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Số lượng vượt quá tồn kho.',
                ]);
            }
            $cart[$key]['quantity'] = $newQty;
        } else {
            $cart[$key] = [
                'product_id'     => $product->id,
                'name'           => $product->name,
                'slug'           => $product->slug,
                'image'          => $product->image,
                'size'           => null,
                'base_price'     => $product->effective_price,
                'modifier_extra' => 0,
                'unit_price'     => $product->effective_price,
                'price'          => $product->effective_price,
                'modifier_ids'   => [],
                'modifier_names' => '',
                'quantity'       => $quantity,
            ];
        }

        session(['cart' => $cart]);

        $cartCount = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'success'   => true,
            'total_qty' => $cartCount,
            'cart_count'=> $cartCount,
        ]);
    }

    // ─────────────────────────────────────────────
    // LOCAL INTENT PROCESSING
    // ─────────────────────────────────────────────

    private function processLocally(string $intent, string $message, string $language, ?object $user): ?array
    {
        $isVi = $language === 'vi';

        return match ($intent) {
            // Let Gemini handle conversational greetings dynamically
            // 'greeting'       => $this->intentGreeting($user, $isVi),
            'product_lookup' => $this->intentProductLookup($message, $isVi),
            'order_tracking' => $this->intentOrderTracking($user, $message, $isVi),
            'weather'        => $this->intentWeather($isVi),
            'mood'           => $this->intentMood($message, $isVi),
            'recommendation' => $this->intentRecommendation($user, $isVi),
            'escalation'     => $this->intentEscalation($isVi),
            default          => null,
        };
    }

    private function intentGreeting(?object $user, bool $isVi): array
    {
        $name = $user ? " {$user->name}" : '';
        $msg  = $isVi
            ? "Xin chào{$name}! Tôi là CaféAI, trợ lý ảo của XDTHECOFFEEHOUSE.\n\nTìm đồ uống phù hợp\nTheo dõi đơn hàng\nĐặt hàng nhanh\nGợi ý theo thời tiết\n\nBạn cần gì hôm nay?"
            : "Hello{$name}! I'm CaféAI, XDTHECOFFEEHOUSE's virtual assistant.\n\nFind the perfect drink\nTrack your orders\nQuick ordering\nWeather-based suggestions\n\nWhat can I do for you today?";

        return ['message' => $msg, 'suggestions' => $this->getSuggestions('greeting')];
    }

    private function intentProductLookup(string $message, bool $isVi): ?array
    {
        $products = $this->smartProductSearch($message);

        if ($products->isNotEmpty()) {
            $msg = $isVi ? "Đây là những sản phẩm phù hợp 🔍:\n\n" : "Here are matching products 🔍:\n\n";
            foreach ($products as $p) {
                $price = number_format($p->effective_price, 0, ',', '.') . 'đ';
                $msg  .= "☕ **{$p->name}** - {$price}\n   " . \Str::limit($p->description, 80) . "\n\n";
            }
            $msg .= $isVi ? '_Bạn muốn thêm sản phẩm nào vào giỏ hàng không?_' : '_Would you like to add any to your cart?_';

            return ['message' => $msg, 'products' => $products->toArray(), 'suggestions' => $this->getSuggestions('product')];
        }

        // Log market gap
        ProductRequest::logRequest($message, $message);

        // Returning null allows falling back to Gemini, which is better for conversational responses
        return null;
    }

    private function intentOrderTracking(?object $user, string $message, bool $isVi): ?array
    {
        if (! $user) {
            // Only prompt login if they actually ask about status/tracking
            if (preg_match('/theo dõi|kiểm tra|trạng thái|track|status/iu', $message)) {
                return [
                    'message'     => $isVi ? "Bạn cần đăng nhập để xem đơn hàng. 🔐" : "Please login to track your orders. 🔐",
                    'suggestions' => [['text' => '🔑 ' . ($isVi ? 'Đăng nhập' : 'Login'), 'action' => 'login']],
                ];
            }
            return null;
        }

        // Check for specific order ID
        if (preg_match('/(?:#?)(XD\d+|\d{4,})/iu', $message, $matches)) {
            $order = Order::where('user_id', $user->id)
                ->where(function ($q) use ($matches) {
                    $q->where('tracking_code', strtoupper($matches[1]))
                      ->orWhere('id', (int) $matches[1]);
                })->first();

            if ($order) {
                $msg = $isVi
                    ? "📦 **Đơn hàng {$order->tracking_code}**\n\nTrạng thái: {$order->status}\nThanh toán: {$order->payment_status}\nTổng tiền: " . number_format($order->total, 0, ',', '.') . "đ"
                    : "📦 **Order {$order->tracking_code}**\n\nStatus: {$order->status}\nPayment: {$order->payment_status}\nTotal: " . number_format($order->total, 0, ',', '.') . "đ";

                return ['message' => $msg, 'suggestions' => $this->getSuggestions('order')];
            }
        }

        // If asking to track, list recent orders
        if (preg_match('/theo dõi|kiểm tra|trạng thái|track|status/iu', $message)) {
            $orders = Order::where('user_id', $user->id)->latest()->take(5)->get();

            if ($orders->isEmpty()) {
                return [
                    'message'     => $isVi ? "Bạn chưa có đơn hàng nào. Hãy khám phá menu! ☕" : "No orders yet. Let's explore our menu! ☕",
                    'suggestions' => $this->getSuggestions('no_orders'),
                ];
            }

            $msg = $isVi ? "📦 Đơn hàng gần đây của bạn:\n\n" : "📦 Your recent orders:\n\n";
            foreach ($orders as $o) {
                $emoji = match ($o->status) {
                    'Hoàn thành' => '✅', 'Đang giao' => '🚚', 'Đã hủy' => '❌', default => '⏳'
                };
                $msg .= "{$emoji} **{$o->tracking_code}** — " . number_format($o->total, 0, ',', '.') . "đ — {$o->status}\n";
            }

            return ['message' => $msg, 'suggestions' => $this->getSuggestions('order')];
        }

        // Fallback to Gemini for general questions about orders
        return null;
    }

    private function intentWeather(bool $isVi): array
    {
        $weather     = $this->getWeather();
        $suggestions = $this->getWeatherSuggestions($weather);

        $msg = $isVi
            ? "**Thời tiết tại {$weather['city']}:**\n{$weather['temp']}°C | Độ ẩm: {$weather['humidity']}% | {$weather['description']}\n\n{$suggestions['message']}"
            : "**Weather in {$weather['city']}:**\n{$weather['temp']}°C | Humidity: {$weather['humidity']}% | {$weather['description']}\n\n{$suggestions['message']}";

        foreach ($suggestions['products'] as $p) {
            $msg .= "\n**{$p->name}** — " . number_format($p->effective_price, 0, ',', '.') . "đ";
        }

        return ['message' => $msg, 'products' => $suggestions['products']->toArray(), 'suggestions' => $this->getSuggestions('weather'), 'metadata' => ['weather' => $weather]];
    }

    private function intentMood(string $message, bool $isVi): array
    {
        $msgLower = mb_strtolower($message, 'UTF-8');

        $moods = [
            'tired'    => ['patterns' => ['mệt', 'tired', 'buồn ngủ', 'sleepy', 'exhausted'], 'vi' => 'Đây là những thức uống giúp bạn tỉnh táo!', 'en' => 'Here are drinks to boost your energy!', 'keywords' => 'espresso americano robusta'],
            'stressed' => ['patterns' => ['stress', 'căng thẳng', 'lo lắng', 'anxious'], 'vi' => 'Hít thở sâu... Đây là thức uống giúp bạn thư giãn', 'en' => 'Take a deep breath... Here are relaxing drinks', 'keywords' => 'trà latte'],
            'sad'      => ['patterns' => ['buồn', 'sad', 'chán', 'cô đơn', 'lonely'], 'vi' => 'Một ly ấm áp sẽ giúp bạn cảm thấy tốt hơn', 'en' => 'A warm drink will make you feel better', 'keywords' => 'latte capuccino caramel'],
            'happy'    => ['patterns' => ['vui', 'happy', 'hạnh phúc', 'tuyệt vời', 'great'], 'vi' => 'Tuyệt vời! Hãy thử những thức uống tràn đầy năng lượng!', 'en' => "That's great! Here are some energetic drinks!", 'keywords' => 'caramel trà vải nước cam'],
        ];

        $detected = 'happy';
        foreach ($moods as $key => $mood) {
            foreach ($mood['patterns'] as $pattern) {
                if (str_contains($msgLower, $pattern)) { $detected = $key; break 2; }
            }
        }

        $mood     = $moods[$detected];
        $products = $this->smartProductSearch($mood['keywords']);
        $msg      = "**Virtual Barista**\n\n" . ($isVi ? $mood['vi'] : $mood['en']) . "\n\n";

        foreach ($products as $p) {
            $msg .= "**{$p->name}** — " . number_format($p->effective_price, 0, ',', '.') . "đ\n";
        }

        return ['message' => $msg, 'products' => $products->toArray(), 'suggestions' => $this->getSuggestions('mood'), 'metadata' => ['mood' => $detected]];
    }

    private function intentRecommendation(?object $user, bool $isVi): array
    {
        $products = Product::active()->inStock()->latest()->take(4)->get();
        $msg      = $isVi ? "Sản phẩm bán chạy nhất:\n\n" : "Our best sellers:\n\n";

        foreach ($products as $p) {
            $msg .= "**{$p->name}** — " . number_format($p->effective_price, 0, ',', '.') . "đ\n   " . \Str::limit($p->description, 80) . "\n\n";
        }

        return ['message' => $msg, 'products' => $products->toArray(), 'suggestions' => $this->getSuggestions('recommendation')];
    }

    private function intentEscalation(bool $isVi): array
    {
        $msg = $isVi
            ? "Tôi hiểu bạn cần hỗ trợ thêm!\n\n**Hotline:** +84 978 853 110\n**Email:** dn250621@coffeeshop.com\n**Địa chỉ:** 93 Lê Cao Lãng, Quận Tân Phú, TP.HCM\n**Giờ mở cửa:** 8:00 - 21:00"
            : "I understand you need more help!\n\n**Hotline:** +84 978 853 110\n**Email:** dn250621@coffeeshop.com\n**Address:** 93 Le Cao Lang, Tan Phu, HCMC\n**Hours:** 8:00 AM - 9:00 PM";

        return ['message' => $msg, 'suggestions' => [['text' => 'Menu', 'action' => 'menu']]];
    }

    // ─────────────────────────────────────────────
    // GEMINI API FALLBACK
    // ─────────────────────────────────────────────

    private function callGeminiAPI(string $message, string $language, ?object $user, string $sessionId): array
    {
        $apiKey = config('services.gemini.api_key'); // Dùng Gemini API (key trong .env: GEMINI_API_KEY)

        if (empty($apiKey)) {
            return $this->fallbackResponse($message, $language);
        }

        $menuContext = Product::active()->with('category')->take(20)->get()
            ->map(fn($p) => "[ID:{$p->id}] {$p->name} — " . number_format($p->effective_price, 0, ',', '.') . "đ — {$p->description}")
            ->implode("\n");

        $weather = $this->getWeather();

        $systemPrompt = "You are CaféAI, a friendly, professional virtual barista for the coffee shop 'XDTHECOFFEEHOUSE'.\n" .
            "Language: " . ($language === 'vi' ? 'Vietnamese' : 'English') . "\n" .
            "Customer Name: " . ($user?->name ?? 'Guest') . "\n" .
            "Current Weather in Shop City: {$weather['temp']}°C, {$weather['description']}\n\n" .
            "COFFEE SHOP DETAILS:\n" .
            "- Address: 93 Lê Cao Lãng, Quận Tân Phú, TP.HCM\n" .
            "- Hotline: +84 978 853 110\n" .
            "- Email: dn250621@coffeeshop.com\n" .
            "- Opening Hours: 8:00 AM - 9:00 PM (Every day)\n\n" .
            "PRODUCT MENU:\n{$menuContext}\n\n" .
            "INSTRUCTIONS:\n" .
            "1. You must answer questions using the shop details and menu context. If asked about recommendations, suggest drinks from the menu based on the weather, customer preferences, or mood.\n" .
            "2. When recommending or mentioning a product from the MENU, you must append [ID:X] (where X is the product ID, e.g. [ID:3]) to the product name. This is crucial for the front-end to display clickable product cards.\n" .
            "3. Keep your responses friendly, concise, natural, and helpful. Avoid using emojis inside the text to keep the interface clean.";

        $history = ChatLog::where('session_id', $sessionId)->latest()->take(10)->get()->reverse()
            ->map(fn($log) => ['role' => $log->role, 'content' => $log->message])
            ->values()->toArray();

        $history[] = ['role' => 'user', 'content' => $message];

        // Chuyển đổi history sang format Gemini (contents array)
        $contents = array_map(fn($h) => [
            'role'  => $h['role'] === 'assistant' ? 'model' : 'user',
            'parts' => [['text' => $h['content']]],
        ], $history);

        $model = config('services.gemini.model', 'gemini-3.5-flash');

        try {
            $response = Http::timeout(30)
                ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                    'contents'          => $contents,
                    'systemInstruction' => [
                        'parts' => [['text' => $systemPrompt]],
                    ],
                    'generationConfig'  => [
                        'maxOutputTokens' => 1024,
                        'temperature'     => 0.7,
                    ],
                ]);

            if ($response->successful()) {
                $aiMessage = $response->json('candidates.0.content.parts.0.text', '');

                // Extract product IDs mentioned
                $products = collect();
                if (preg_match_all('/\[ID:(\d+)\]/', $aiMessage, $matches)) {
                    $products  = Product::whereIn('id', $matches[1])->get();
                    $aiMessage = preg_replace('/\s*\[ID:\d+\]\s*/', ' ', $aiMessage);
                }

                return ['message' => trim($aiMessage), 'products' => $products->toArray()];
            } else {
                Log::warning('Gemini API returned error: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::warning('Gemini API failed: ' . $e->getMessage());
        }

        return $this->fallbackResponse($message, $language);
    }

    private function fallbackResponse(string $message, string $language): array
    {
        $isVi = $language === 'vi';
        $msg  = $isVi
            ? "Xin lỗi, tôi chưa hiểu câu hỏi của bạn. Bạn có thể hỏi về:\n\nSản phẩm & menu\nTheo dõi đơn hàng\nGợi ý theo thời tiết\nTư vấn đồ uống"
            : "Sorry, I didn't quite understand. You can ask about:\n\nProducts & menu\nOrder tracking\nWeather suggestions\nDrink recommendations";

        return ['message' => $msg, 'suggestions' => $this->getSuggestions('greeting')];
    }

    // ─────────────────────────────────────────────
    // HELPERS
    // ─────────────────────────────────────────────

    private function detectLanguage(string $message): string
    {
        // Detect Vietnamese by common characters
        if (preg_match('/[àáâãèéêìíòóôõùúýăđơưạảấầẩẫậắằẳẵặẹẻẽếềểễệỉịọỏốồổỗộớờởỡợụủứừửữựỳỵỷỹ]/u', $message)) {
            return 'vi';
        }
        return 'en';
    }

    private function detectIntent(string $message, string $language): string
    {
        $msg = mb_strtolower($message, 'UTF-8');

        $patterns = [
            'order_tracking' => '/đơn hàng|theo dõi|kiểm tra đơn|trạng thái đơn|order.*status|track.*order|my order|XD\d+/iu',
            'weather'        => '/thời tiết|weather|nóng quá|lạnh quá|trời mưa|trời nắng|hot.*day|cold.*day|rainy/iu',
            'mood'           => '/buồn|vui|mệt|stress|tỉnh táo|năng lượng|thư giãn|sad|happy|tired|energetic|relax/iu',
            'recommendation' => '/gợi ý|recommend|suggest|tư vấn|nên uống|what should|best.*drink|phổ biến|popular/iu',
            'product_lookup' => '/menu|thực đơn|có gì|sản phẩm|đồ uống|cà phê|coffee|latte|espresso|trà|tea|bánh|giá|price|bao nhiêu|how much/iu',
            'greeting'       => '/^(xin chào|hello|hi|hey|chào|good morning|good afternoon)/iu',
            'escalation'     => '/giúp đỡ|help|hỗ trợ|support|liên hệ|contact|nhân viên|staff|hotline/iu',
        ];

        foreach ($patterns as $intent => $pattern) {
            if (preg_match($pattern, $msg)) {
                return $intent;
            }
        }

        return 'general';
    }

    private function smartProductSearch(string $query): \Illuminate\Database\Eloquent\Collection
    {
        return Product::active()->inStock()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->take(4)
            ->get();
    }

    private function getWeather(): array
    {
        $apiKey = config('services.openweather.api_key');
        $city   = config('services.openweather.city', 'Ho Chi Minh City');

        if ($apiKey) {
            try {
                $response = Http::timeout(5)->get('https://api.openweathermap.org/data/2.5/weather', [
                    'q'     => $city,
                    'appid' => $apiKey,
                    'units' => 'metric',
                    'lang'  => 'vi',
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'city'        => $data['name'],
                        'temp'        => round($data['main']['temp']),
                        'feels_like'  => round($data['main']['feels_like']),
                        'humidity'    => $data['main']['humidity'],
                        'description' => $data['weather'][0]['description'] ?? '',
                    ];
                }
            } catch (\Exception $e) {
                // fallback below
            }
        }

        return ['city' => 'TP.HCM', 'temp' => 32, 'feels_like' => 35, 'humidity' => 75, 'description' => 'Nắng nóng'];
    }

    private function getWeatherSuggestions(array $weather): array
    {
        $temp = $weather['temp'];

        if ($temp >= 30) {
            $msg      = "Trời nóng {$temp}°C! Hãy thử những thức uống mát lạnh sau:";
            $keywords = 'trà đá nước lạnh iced';
        } elseif ($temp <= 20) {
            $msg      = "Trời lạnh {$temp}°C! Một ly nóng sẽ giúp bạn ấm lòng:";
            $keywords = 'latte capuccino espresso nóng';
        } else {
            $msg      = "Thời tiết dễ chịu {$temp}°C! Thưởng thức bất kỳ thức uống nào bạn thích:";
            $keywords = 'cà phê trà';
        }

        return ['message' => $msg, 'products' => $this->smartProductSearch($keywords)];
    }

    private function getSuggestions(string $context): array
    {
        return match ($context) {
            'greeting'       => [['text' => 'Xem menu', 'action' => 'menu'], ['text' => 'Gợi ý cho tôi', 'action' => 'recommend'], ['text' => 'Theo dõi đơn hàng', 'action' => 'track_order'], ['text' => 'Gợi ý theo thời tiết', 'action' => 'weather_suggest']],
            'product'        => [['text' => 'Thêm vào giỏ', 'action' => 'add_to_cart'], ['text' => 'Tìm thêm', 'action' => 'menu'], ['text' => 'Gợi ý khác', 'action' => 'recommend']],
            'order'          => [['text' => 'Xem tất cả đơn', 'action' => 'all_orders'], ['text' => 'Tiếp tục mua sắm', 'action' => 'menu']],
            'weather'        => [['text' => 'Đặt ngay', 'action' => 'menu'], ['text' => 'Gợi ý khác', 'action' => 'recommend']],
            'mood'           => [['text' => 'Đặt ngay', 'action' => 'menu'], ['text' => 'Theo thời tiết', 'action' => 'weather_suggest']],
            'recommendation' => [['text' => 'Đặt ngay', 'action' => 'menu'], ['text' => 'Theo thời tiết', 'action' => 'weather_suggest']],
            'no_results'     => [['text' => 'Xem menu đầy đủ', 'action' => 'menu'], ['text' => 'Gợi ý cho tôi', 'action' => 'recommend']],
            'no_orders'      => [['text' => 'Mua sắm ngay', 'action' => 'menu'], ['text' => 'Gợi ý cho tôi', 'action' => 'recommend']],
            default          => [['text' => 'Xem menu', 'action' => 'menu']],
        };
    }
}
