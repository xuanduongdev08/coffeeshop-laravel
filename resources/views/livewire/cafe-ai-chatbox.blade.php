{{--
    CaféAI Chatbox Widget — Livewire Component
    Thay thế cafe_ai_chatbox.html cũ.
    Giao tiếp với /api/chat qua fetch() JS.
    Styles: css/cafeai.css
--}}
<div id="cafeai-livewire-wrapper">

    {{-- ── Toggle Button ── --}}
    <button
        id="cafeai-toggle"
        wire:click="toggle"
        aria-label="Mở CaféAI"
        title="Chat với CaféAI"
    >
        <span id="cafeai-toggle-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3v-3z"/>
            </svg>
        </span>
    </button>

    {{-- ── Chat Container ── --}}
    @if($isOpen)
    <div id="cafeai-container" class="cafeai-open">

        {{-- Header --}}
        <div class="cafeai-header">
            <div class="cafeai-header-info">
                <div class="cafeai-avatar">☕</div>
                <div>
                    <div class="cafeai-name">CaféAI</div>
                    <div class="cafeai-status">
                        <span class="cafeai-status-dot"></span>
                        Trực tuyến
                    </div>
                </div>
            </div>
            <button wire:click="close" class="cafeai-close-btn" aria-label="Đóng">✕</button>
        </div>

        {{-- Messages --}}
        <div class="cafeai-messages" id="cafeai-messages">
            {{-- Tin nhắn chào mừng --}}
            <div class="cafeai-msg cafeai-msg--bot" id="cafeai-welcome">
                <div class="cafeai-bubble">
                    <p>Xin chào{{ $userName ? ' ' . $userName : '' }}! 👋</p>
                    <p>Tôi là <strong>CaféAI</strong>, trợ lý ảo của XDTHECOFFEEHOUSE.</p>
                    <p>Tôi có thể giúp bạn:</p>
                    <ul style="margin:6px 0 0 16px;padding:0;">
                        <li>☕ Tìm đồ uống phù hợp</li>
                        <li>📦 Theo dõi đơn hàng</li>
                        <li>🌤️ Gợi ý theo thời tiết</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Quick Suggestions --}}
        <div class="cafeai-suggestions" id="cafeai-suggestions">
            <button class="cafeai-chip" onclick="cafeAISend('Gợi ý đồ uống cho tôi')">💡 Gợi ý</button>
            <button class="cafeai-chip" onclick="cafeAISend('Thời tiết hôm nay thế nào?')">🌤️ Thời tiết</button>
            <button class="cafeai-chip" onclick="cafeAISend('Xem menu')">☕ Menu</button>
            @if($isAuth)
                <button class="cafeai-chip" onclick="cafeAISend('Đơn hàng của tôi')">📦 Đơn hàng</button>
            @endif
        </div>

        {{-- Input --}}
        <div class="cafeai-input-area">
            <input
                type="text"
                id="cafeai-input"
                class="cafeai-input"
                placeholder="Nhập tin nhắn..."
                maxlength="500"
                autocomplete="off"
                onkeydown="if(event.key==='Enter' && !event.shiftKey){ event.preventDefault(); cafeAISendFromInput(); }"
            >
            <button class="cafeai-send-btn" onclick="cafeAISendFromInput()" aria-label="Gửi">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </div>

    </div>
    @endif

</div>

@push('scripts')
<script>
(function () {
    var API_URL = '{{ $apiUrl }}';
    var CSRF    = '{{ csrf_token() }}';

    // ── Gửi tin nhắn từ input ──
    window.cafeAISendFromInput = function () {
        var input = document.getElementById('cafeai-input');
        if (!input) return;
        var msg = input.value.trim();
        if (!msg) return;
        input.value = '';
        cafeAISend(msg);
    };

    // ── Gửi tin nhắn ──
    window.cafeAISend = function (message) {
        if (!message || !message.trim()) return;

        appendMessage('user', message);
        showTyping();
        hideSuggestions();

        fetch(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ message: message, action: 'chat' }),
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            hideTyping();
            if (data.success) {
                appendMessage('bot', data.message);
                if (data.products && data.products.length > 0) {
                    appendProducts(data.products);
                }
                if (data.suggestions && data.suggestions.length > 0) {
                    renderSuggestions(data.suggestions);
                }
            } else {
                appendMessage('bot', data.message || 'Đã xảy ra lỗi, vui lòng thử lại.');
            }
        })
        .catch(function () {
            hideTyping();
            appendMessage('bot', 'Không thể kết nối. Vui lòng kiểm tra mạng và thử lại.');
        });
    };

    // ── Thêm tin nhắn vào khung chat ──
    function appendMessage(role, text) {
        var container = document.getElementById('cafeai-messages');
        if (!container) return;

        var div = document.createElement('div');
        div.className = 'cafeai-msg cafeai-msg--' + (role === 'user' ? 'user' : 'bot');

        var bubble = document.createElement('div');
        bubble.className = 'cafeai-bubble';
        // Chuyển **bold** và \n thành HTML
        bubble.innerHTML = formatMessage(text);

        div.appendChild(bubble);
        container.appendChild(div);
        scrollToBottom();
    }

    // ── Hiển thị sản phẩm gợi ý ──
    function appendProducts(products) {
        var container = document.getElementById('cafeai-messages');
        if (!container || !products.length) return;

        var wrapper = document.createElement('div');
        wrapper.className = 'cafeai-products-row';

        products.slice(0, 4).forEach(function (p) {
            var price = p.discount_price || p.price;
            var card  = document.createElement('div');
            card.className = 'cafeai-product-card';
            card.innerHTML =
                '<img src="' + (p.image ? '/' + p.image : '/images/menu-1.jpg') + '" alt="' + escHtml(p.name) + '" onerror="this.src=\'/images/menu-1.jpg\'">' +
                '<div class="cafeai-product-info">' +
                    '<div class="cafeai-product-name">' + escHtml(p.name) + '</div>' +
                    '<div class="cafeai-product-price">' + formatPrice(price) + '</div>' +
                '</div>' +
                '<a href="/san-pham/' + escHtml(p.slug || '') + '" class="cafeai-product-btn">Xem</a>';
            wrapper.appendChild(card);
        });

        var msgDiv = document.createElement('div');
        msgDiv.className = 'cafeai-msg cafeai-msg--bot';
        msgDiv.appendChild(wrapper);
        container.appendChild(msgDiv);
        scrollToBottom();
    }

    // ── Render quick suggestions ──
    function renderSuggestions(suggestions) {
        var container = document.getElementById('cafeai-suggestions');
        if (!container) return;
        container.innerHTML = '';
        container.style.display = 'flex';

        suggestions.slice(0, 4).forEach(function (s) {
            var btn = document.createElement('button');
            btn.className = 'cafeai-chip';
            btn.textContent = s.text;
            btn.onclick = function () { cafeAISend(s.text); };
            container.appendChild(btn);
        });
    }

    function hideSuggestions() {
        var s = document.getElementById('cafeai-suggestions');
        if (s) s.style.display = 'none';
    }

    // ── Typing indicator ──
    function showTyping() {
        var container = document.getElementById('cafeai-messages');
        if (!container) return;
        var div = document.createElement('div');
        div.id = 'cafeai-typing';
        div.className = 'cafeai-msg cafeai-msg--bot';
        div.innerHTML = '<div class="cafeai-bubble cafeai-typing-bubble"><span></span><span></span><span></span></div>';
        container.appendChild(div);
        scrollToBottom();
    }

    function hideTyping() {
        var el = document.getElementById('cafeai-typing');
        if (el) el.remove();
    }

    // ── Helpers ──
    function scrollToBottom() {
        var c = document.getElementById('cafeai-messages');
        if (c) c.scrollTop = c.scrollHeight;
    }

    function formatMessage(text) {
        return text
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\n/g, '<br>');
    }

    function escHtml(str) {
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN').format(Math.round(price)) + 'đ';
    }
})();
</script>
@endpush
