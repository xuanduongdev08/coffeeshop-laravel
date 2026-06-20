/**
 * CaféAI - Frontend Chat Engine
 * Modern JavaScript (ES6+) chatbot UI
 */

(function() {
    'use strict';

    // ==========================================
    // Configuration
    // ==========================================
    const CONFIG = {
        // Dùng Laravel API endpoint thay vì PHP thuần cũ
        apiUrl: '/api/chat',
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        maxMessages: 50,
        storageKey: 'cafeai_history',
    };

    // ==========================================
    // State
    // ==========================================
    let isOpen = false;
    let isTyping = false;
    let chatHistory = [];

    // ==========================================
    // DOM Creation
    // ==========================================
    function createChatUI() {
        const basePath = detectBasePath();
        
        // Toggle Button
        const toggle = document.createElement('button');
        toggle.id = 'cafeai-toggle';
        toggle.setAttribute('aria-label', 'Open CaféAI Chat');
        toggle.innerHTML = `
            <img src="${basePath}/images/cafeai_icon.png" alt="Chat">
            <svg class="cafeai-icon-close" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
            </svg>
            <span class="cafeai-badge" style="display:none">1</span>
        `;
        document.body.appendChild(toggle);

        // Chat Container
        const container = document.createElement('div');
        container.id = 'cafeai-container';
        container.innerHTML = `
            <div class="cafeai-header">
                <div class="cafeai-header-avatar">
                    <img src="${basePath}/images/cafeai_icon.png" alt="Logo">
                </div>
                <div class="cafeai-header-info">
                    <h3>Caf\u00e9AI <span class="cafeai-header-badge">Beta</span></h3>
                    <div class="cafeai-header-status">
                        <span class="status-dot"></span>
                        Trợ lý ảo thông minh
                    </div>
                </div>
                <button class="cafeai-header-close" id="cafeai-close">&times;</button>
            </div>
            
            <div class="cafeai-messages" id="cafeai-messages"></div>
            
            <div class="cafeai-quick-menu">
                <button class="cafeai-quick-btn" data-action="menu">☕ Menu</button>
                <button class="cafeai-quick-btn" data-action="recommend">🔍 Gợi ý cho tôi</button>
                <button class="cafeai-quick-btn" data-action="weather_suggest">🌤️ Theo thời tiết</button>
                <button class="cafeai-quick-btn" data-action="track_order">📦 Đơn hàng</button>
                <button class="cafeai-quick-btn" data-action="mood">💬 Tâm trạng</button>
            </div>
            
            <div class="cafeai-input-area">
                <div class="cafeai-input-box">
                    <textarea id="cafeai-input" placeholder="Hỏi CaféAI bất cứ điều gì..." rows="1"></textarea>
                    <button id="cafeai-send" title="Gửi (Enter)">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                    </button>
                </div>
            </div>
        `;
        document.body.appendChild(container);

        // Event Listeners
        toggle.addEventListener('click', toggleChat);
        document.getElementById('cafeai-close').addEventListener('click', toggleChat);
        document.getElementById('cafeai-send').addEventListener('click', sendMessage);
        
        const input = document.getElementById('cafeai-input');
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        input.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 100) + 'px';
        });

        document.querySelectorAll('.cafeai-quick-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const action = btn.getAttribute('data-action');
                handleQuickAction(action);
            });
        });
    }

    // ==========================================
    // Chat Toggle
    // ==========================================
    function toggleChat() {
        const container = document.getElementById('cafeai-container');
        const toggle = document.getElementById('cafeai-toggle');
        
        isOpen = !isOpen;
        
        if (isOpen) {
            container.classList.add('open');
            toggle.classList.add('active');
            toggle.querySelector('.cafeai-badge').style.display = 'none';
            
            setTimeout(() => {
                document.getElementById('cafeai-input').focus();
            }, 300);
            
            if (chatHistory.length === 0) {
                loadGreeting();
            }
        } else {
            container.classList.remove('open');
            toggle.classList.remove('active');
        }
    }

    // ==========================================
    // API Communication
    // ==========================================
    async function apiCall(action, data = {}) {
        try {
            const response = await fetch(CONFIG.apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CONFIG.csrfToken,
                },
                credentials: 'same-origin',
                body: JSON.stringify({ action, ...data }),
            });
            
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            return await response.json();
        } catch (error) {
            console.error('CaféAI API Error:', error);
            return { 
                success: false, 
                message: 'Xin lỗi, tôi đang gặp sự cố kết nối 😥. Vui lòng thử lại sau!'
            };
        }
    }

    // ==========================================
    // Message Handling
    // ==========================================
    async function loadGreeting() {
        showTyping();
        const result = await apiCall('greeting');
        hideTyping();
        if (result.success || result.message) {
            addBotMessage(result.message, result.products, result.suggestions);
        }
    }

    async function sendMessage() {
        const input = document.getElementById('cafeai-input');
        const message = input.value.trim();
        if (!message || isTyping) return;
        
        input.value = '';
        input.style.height = 'auto';
        
        addUserMessage(message);
        showTyping();
        
        const result = await apiCall('chat', { message });
        hideTyping();
        
        if (result.success || result.message) {
            addBotMessage(result.message, result.products, result.suggestions, result.cart_action);
        } else {
            addBotMessage(result.error || 'Xin lỗi, đã có lỗi xảy ra 😥');
        }
    }

    function handleQuickAction(action) {
        const actions = {
            'menu': 'Cho tôi xem menu',
            'recommend': 'Gợi ý đồ uống cho tôi',
            'weather_suggest': 'Gợi ý theo thời tiết',
            'mood': 'Tôi muốn tìm đồ uống theo tâm trạng',
            'track_order': 'Kiểm tra đơn hàng của tôi',
            'login': () => {
                const b = document.body.getAttribute('data-base-path') || '';
                window.location.href = b + '/index.php?action=login';
                return null;
            }
        };
        
        const val = actions[action];
        if (typeof val === 'function') {
            val();
        } else if (val) {
            document.getElementById('cafeai-input').value = val;
            sendMessage();
        }
    }

    // ==========================================
    // Rendering
    // ==========================================
    function addUserMessage(text) {
        const el = createMessageEl('user', text);
        document.getElementById('cafeai-messages').appendChild(el);
        scrollToBottom();
        chatHistory.push({ role: 'user', message: text, time: new Date() });
        saveChatHistory();
    }

    function addBotMessage(text, products = [], suggestions = [], cartAction = null) {
        const area = document.getElementById('cafeai-messages');
        
        // Text
        area.appendChild(createMessageEl('bot', text));
        
        // Products
        if (products && products.length > 0) {
            area.appendChild(createProductScroller(products));
        }
        
        // Cart Action
        if (cartAction && cartAction.type === 'add_to_cart') {
            area.appendChild(createCartActionEl(cartAction));
        }
        
        // Chips
        if (suggestions && suggestions.length > 0) {
            area.appendChild(createSuggestionChips(suggestions));
        }
        
        scrollToBottom();
        chatHistory.push({ role: 'bot', message: text, time: new Date() });
        saveChatHistory();
    }

    function createMessageEl(role, text) {
        const wrap = document.createElement('div');
        wrap.className = `cafeai-msg-wrapper ${role}`;
        
        let avatar = '';
        if (role === 'bot') {
            avatar = `<div class="cafeai-bot-avatar"><img src="${detectBasePath()}/images/cafeai_icon.png"></div>`;
        }
        
        const content = document.createElement('div');
        content.className = 'cafeai-msg-content';
        
        const bubble = document.createElement('div');
        bubble.className = 'cafeai-bubble';
        bubble.innerHTML = renderMarkdown(text);
        
        const time = document.createElement('div');
        time.className = 'cafeai-msg-time';
        time.textContent = formatTime(new Date());
        
        content.appendChild(bubble);
        content.appendChild(time);
        
        if (role === 'bot') wrap.innerHTML = avatar;
        wrap.appendChild(content);
        return wrap;
    }

    function createProductScroller(products) {
        const wrap = document.createElement('div');
        wrap.className = 'cafeai-products-scroller';
        const basePath = detectBasePath();
        
        products.forEach(p => {
            const card = document.createElement('div');
            card.className = 'cafeai-card';
            // Hỗ trợ cả field name Laravel (image, name, id) và PHP cũ (hinh_anh, ten_hanghoa, id_hanghoa)
            const imgSrc = p.image || p.hinh_anh;
            const img = imgSrc ? (basePath + '/' + imgSrc) : (basePath + '/images/menu-1.jpg');
            const name = p.name || p.ten_hanghoa || '';
            const price = p.effective_price || p.discount_price || p.price || p.gia || 0;
            const productId = p.id || p.id_hanghoa;
            const slug = p.slug || '';
            
            card.innerHTML = `
                <div class="cafeai-card-img"><img src="${img}" onerror="this.src='${basePath}/images/menu-1.jpg'"></div>
                <div class="cafeai-card-body">
                    <div class="cafeai-card-name">${escapeHtml(name)}</div>
                    <div class="cafeai-card-price">${formatPrice(price)}</div>
                    <button class="cafeai-add-btn" data-id="${productId}">Thêm vào giỏ</button>
                </div>
            `;
            
            card.querySelector('.cafeai-add-btn').addEventListener('click', (e) => {
                e.stopPropagation();
                addToCart(productId, name);
            });
            
            card.addEventListener('click', () => {
                if (slug) {
                    window.open('/san-pham/' + slug, '_blank');
                }
            });
            
            wrap.appendChild(card);
        });
        return wrap;
    }

    function createSuggestionChips(suggestions) {
        const wrap = document.createElement('div');
        wrap.className = 'cafeai-chips';
        suggestions.forEach(s => {
            const chip = document.createElement('button');
            chip.className = 'cafeai-chip';
            chip.textContent = s.text;
            chip.addEventListener('click', () => {
                if (s.action === 'confirm_add' && s.data) {
                    addToCart(s.data.product_id);
                } else {
                    handleQuickAction(s.action);
                }
                wrap.remove();
            });
            wrap.appendChild(chip);
        });
        return wrap;
    }

    function createCartActionEl(cartAction) {
        const wrap = document.createElement('div');
        wrap.style.padding = '0 12px 12px 44px';
        const btn = document.createElement('button');
        btn.className = 'cafeai-add-btn';
        btn.style.background = '#c49b63';
        btn.style.color = 'white';
        btn.textContent = '✅ Xác nhận thêm vào giỏ';
        btn.onclick = () => {
            addToCart(cartAction.product_id, cartAction.product_name);
            wrap.remove();
        };
        wrap.appendChild(btn);
        return wrap;
    }

    async function addToCart(id, name = '') {
        if (document.body.getAttribute('data-logged-in') !== 'true') {
            addBotMessage('🔐 Bạn cần đăng nhập để đặt hàng.', [], [{ text: '🔑 Đăng nhập', action: 'login' }]);
            return;
        }
        showTyping();
        const res = await apiCall('add_to_cart', { product_id: id, quantity: 1 });
        hideTyping();
        if (res.success) {
            addBotMessage(`✅ Đã thêm **${name || 'sản phẩm'}** vào giỏ hàng!`);
            showToast(`🛒 Đã thêm vào giỏ hàng!`);
            const badge = document.querySelector('.bag small');
            if (badge && res.total_qty !== undefined) badge.textContent = res.total_qty;
        } else {
            addBotMessage('❌ ' + (res.error || 'Lỗi thêm vào giỏ'));
        }
    }

    // ==========================================
    // Helpers
    // ==========================================
    function showTyping() {
        isTyping = true;
        const area = document.getElementById('cafeai-messages');
        const wrap = document.createElement('div');
        wrap.id = 'cafeai-typing';
        wrap.className = 'cafeai-msg-wrapper bot';
        wrap.innerHTML = `
            <div class="cafeai-bot-avatar"><img src="${detectBasePath()}/images/cafeai_icon.png"></div>
            <div class="cafeai-typing-bubble cafeai-bubble">
                <div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div>
            </div>
        `;
        area.appendChild(wrap);
        scrollToBottom();
        document.getElementById('cafeai-send').disabled = true;
    }

    function hideTyping() {
        isTyping = false;
        const el = document.getElementById('cafeai-typing');
        if (el) el.remove();
        document.getElementById('cafeai-send').disabled = false;
    }

    function scrollToBottom() {
        const el = document.getElementById('cafeai-messages');
        requestAnimationFrame(() => el.scrollTop = el.scrollHeight);
    }

    function showToast(msg) {
        const t = document.createElement('div');
        t.className = 'cafeai-toast';
        t.innerHTML = `<span>✅</span> ${msg}`;
        document.body.appendChild(t);
        setTimeout(() => t.remove(), 3000);
    }

    function renderMarkdown(t) {
        if (!t) return '';
        let h = escapeHtml(t);
        h = h.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        h = h.replace(/__(.*?)__/g, '<strong>$1</strong>');
        h = h.replace(/\n/g, '<br>');
        return h;
    }

    function escapeHtml(t) {
        const d = document.createElement('div');
        d.textContent = t;
        return d.innerHTML;
    }

    function formatTime(d) {
        return d.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
    }

    function formatPrice(p) {
        return new Intl.NumberFormat('vi-VN').format(p) + 'đ';
    }

    function detectBasePath() {
        return document.body.getAttribute('data-base-path') || '';
    }

    function saveChatHistory() {
        try {
            sessionStorage.setItem(CONFIG.storageKey, JSON.stringify(chatHistory.slice(-CONFIG.maxMessages)));
        } catch (e) {}
    }

    function loadChatHistory() {
        try {
            const saved = sessionStorage.getItem(CONFIG.storageKey);
            if (saved) {
                chatHistory = JSON.parse(saved);
                const area = document.getElementById('cafeai-messages');
                chatHistory.forEach(m => area.appendChild(createMessageEl(m.role, m.message)));
                scrollToBottom();
            }
        } catch (e) {}
    }

    function init() {
        if (document.getElementById('cafeai-toggle')) return;
        createChatUI();
        loadChatHistory();
        console.log('☕ CaféAI initialized successfully!');
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
