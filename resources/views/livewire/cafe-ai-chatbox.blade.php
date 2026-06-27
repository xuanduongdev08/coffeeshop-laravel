{{--
    CaféAI Chatbox Widget — Livewire Component
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
    <div id="cafeai-container" class="open">

        {{-- Header --}}
        <div class="cafeai-header">
            <div class="cafeai-header-avatar">
                <span style="font-size:22px;">☕</span>
            </div>
            <div class="cafeai-header-info">
                <h3>
                    CaféAI
                    <span class="cafeai-header-badge">BETA</span>
                </h3>
                <div class="cafeai-header-status">
                    <span class="status-dot"></span>
                    Trợ lý ảo thông minh
                </div>
            </div>
            <button wire:click="close" class="cafeai-close-btn" aria-label="Đóng"
                style="background:none;border:none;color:white;cursor:pointer;font-size:18px;padding:4px 8px;opacity:0.8;transition:opacity .2s;"
                onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=.8">✕</button>
        </div>

        {{-- Messages --}}
        <div class="cafeai-messages" id="cafeai-messages">
            {{-- Tin nhắn chào mừng --}}
            <div class="cafeai-msg-wrapper bot" id="cafeai-welcome">
                <div class="cafeai-bot-avatar">
                    <span style="font-size:16px;">☕</span>
                </div>
                <div class="cafeai-msg-content">
                    <div class="cafeai-bubble">
                        <p>Xin chào{{ $userName ? ' <strong>' . $userName . '</strong>' : '' }}! 👋</p>
                        <p>Tôi là <strong>CaféAI</strong>, trợ lý ảo của XDTHECOFFEEHOUSE.</p>
                        <p>Tôi có thể giúp bạn:</p>
                        <ul style="margin:6px 0 0 16px;padding:0;line-height:1.8;">
                            <li>☕ Tìm đồ uống phù hợp</li>
                            <li>📦 Theo dõi đơn hàng</li>
                            <li>🌤️ Gợi ý theo thời tiết</li>
                            <li>💡 Tư vấn theo sở thích</li>
                        </ul>
                    </div>
                    <div class="cafeai-msg-time">{{ now()->format('H:i') }}</div>
                </div>
            </div>
        </div>

        {{-- Quick Suggestions --}}
        <div class="cafeai-chips" id="cafeai-suggestions">
            <button class="cafeai-chip" onclick="cafeAISend('Menu')">☕ Menu</button>
            <button class="cafeai-chip" onclick="cafeAISend('Gợi ý cho tôi')">💡 Gợi ý cho tôi</button>
            <button class="cafeai-chip" onclick="cafeAISend('Theo dõi đơn hàng')">📦 Đơn hàng</button>
            <button class="cafeai-chip" onclick="cafeAISend('Thời tiết hôm nay thế nào?')">🌤️ Theo thời tiết</button>
        </div>

        {{-- Input --}}
        <div class="cafeai-input-area">
            <div class="cafeai-input-box">
                <input
                    type="text"
                    id="cafeai-input"
                    class="cafeai-input"
                    placeholder="Hỏi CaféAI bất cứ điều gì..."
                    maxlength="500"
                    autocomplete="off"
                    onkeydown="if(event.key==='Enter' && !event.shiftKey){ event.preventDefault(); cafeAISendFromInput(); }"
                >
                <button id="cafeai-send" onclick="cafeAISendFromInput()" aria-label="Gửi">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </div>
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

        var now = new Date();
        var timeStr = now.getHours().toString().padStart(2,'0') + ':' + now.getMinutes().toString().padStart(2,'0');

        var wrapper = document.createElement('div');
        wrapper.className = 'cafeai-msg-wrapper ' + (role === 'user' ? 'user' : 'bot');

        if (role === 'bot') {
            var avatarDiv = document.createElement('div');
            avatarDiv.className = 'cafeai-bot-avatar';
            avatarDiv.innerHTML = '<span style="font-size:16px;">☕</span>';
            wrapper.appendChild(avatarDiv);
        }

        var contentDiv = document.createElement('div');
        contentDiv.className = 'cafeai-msg-content';

        var bubble = document.createElement('div');
        bubble.className = 'cafeai-bubble';
        // Render markdown đầy đủ: bold, list, xuống dòng
        bubble.innerHTML = formatMessage(text);

        var timeDiv = document.createElement('div');
        timeDiv.className = 'cafeai-msg-time';
        timeDiv.textContent = timeStr;

        contentDiv.appendChild(bubble);
        contentDiv.appendChild(timeDiv);
        wrapper.appendChild(contentDiv);
        container.appendChild(wrapper);
        scrollToBottom();
    }

    // ── Hiển thị sản phẩm gợi ý ──
    function appendProducts(products) {
        var container = document.getElementById('cafeai-messages');
        if (!container || !products.length) return;

        var wrapper = document.createElement('div');
        wrapper.className = 'cafeai-msg-wrapper bot';

        var botAvatar = document.createElement('div');
        botAvatar.className = 'cafeai-bot-avatar';
        botAvatar.innerHTML = '<span style="font-size:16px;">☕</span>';
        wrapper.appendChild(botAvatar);

        var scroller = document.createElement('div');
        scroller.className = 'cafeai-products-scroller';

        products.slice(0, 4).forEach(function (p) {
            var price = p.discount_price || p.effective_price || p.price;
            var card  = document.createElement('div');
            card.className = 'cafeai-card';
            card.innerHTML =
                '<div class="cafeai-card-img">' +
                    '<img src="' + (p.image ? '/' + p.image : '/images/menu-1.jpg') + '" alt="' + escHtml(p.name) + '" loading="lazy" onerror="this.src=\'/images/menu-1.jpg\'">' +
                '</div>' +
                '<div class="cafeai-card-body">' +
                    '<div class="cafeai-card-name">' + escHtml(p.name) + '</div>' +
                    '<div class="cafeai-card-price">' + formatPrice(price) + '</div>' +
                    '<button class="cafeai-add-btn" onclick="window.location.href=\'/san-pham/' + escHtml(p.slug || '') + '\'">' +
                        '<svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>' +
                        ' Xem ngay' +
                    '</button>' +
                '</div>';
            scroller.appendChild(card);
        });

        wrapper.appendChild(scroller);
        container.appendChild(wrapper);
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

        var wrapper = document.createElement('div');
        wrapper.id = 'cafeai-typing';
        wrapper.className = 'cafeai-msg-wrapper bot';

        var avatar = document.createElement('div');
        avatar.className = 'cafeai-bot-avatar';
        avatar.innerHTML = '<span style="font-size:16px;">☕</span>';

        var contentDiv = document.createElement('div');
        contentDiv.className = 'cafeai-msg-content';

        var bubble = document.createElement('div');
        bubble.className = 'cafeai-bubble cafeai-typing-bubble';
        bubble.innerHTML = '<div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div>';

        contentDiv.appendChild(bubble);
        wrapper.appendChild(avatar);
        wrapper.appendChild(contentDiv);
        container.appendChild(wrapper);
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

    /**
     * formatMessage — Chuyển markdown đơn giản sang HTML
     * Hỗ trợ: **bold**, _italic_, dòng đánh số, dòng gạch đầu, xuống dòng
     */
    function formatMessage(text) {
        // 1. Escape HTML đặc biệt trước
        var safe = text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');

        // 2. Xử lý danh sách có đánh số (1. 2. 3.) — gom thành <ol>
        safe = safe.replace(/((?:^\d+\.\s+.+\n?)+)/gm, function(block) {
            var items = block.trim().split('\n').map(function(line) {
                return '<li>' + line.replace(/^\d+\.\s+/, '') + '</li>';
            }).join('');
            return '<ol style="margin:6px 0 6px 18px;padding:0;">' + items + '</ol>';
        });

        // 3. Xử lý danh sách gạch đầu (- item) — gom thành <ul>
        safe = safe.replace(/((?:^[-*]\s+.+\n?)+)/gm, function(block) {
            var items = block.trim().split('\n').map(function(line) {
                return '<li>' + line.replace(/^[-*]\s+/, '') + '</li>';
            }).join('');
            return '<ul style="margin:6px 0 6px 18px;padding:0;">' + items + '</ul>';
        });

        // 4. Bold: **text**
        safe = safe.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

        // 5. Italic: _text_ hoặc *text*
        safe = safe.replace(/(?<!\*)\*([^*]+)\*(?!\*)/g, '<em>$1</em>');
        safe = safe.replace(/_([^_]+)_/g, '<em>$1</em>');

        // 6. Xuống dòng thường → <br> (nhưng không thêm sau thẻ block)
        safe = safe.replace(/\n(?!<\/?(ol|ul|li))/g, '<br>');

        // 7. Dọn kép <br>
        safe = safe.replace(/(<br>){3,}/g, '<br><br>');

        return safe;
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
