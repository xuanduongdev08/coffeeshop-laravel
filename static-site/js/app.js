/**
 * XDTHECOFFEEHOUSE — Client Logic & State Management
 * Chạy 100% không cần backend — Tương thích tuyệt đối với Vercel / Netlify
 */

(function () {
  'use strict';

  // --- STATE MANAGEMENT VIA LOCALSTORAGE ---
  const CART_KEY = 'xdcoffee_cart';
  const ORDERS_KEY = 'xdcoffee_orders';
  const RESERVATIONS_KEY = 'xdcoffee_reservations';

  function getCart() {
    try {
      return JSON.parse(localStorage.getItem(CART_KEY)) || [];
    } catch (e) {
      return [];
    }
  }

  function saveCart(cart) {
    localStorage.setItem(CART_KEY, JSON.stringify(cart));
    updateCartUI();
  }

  // --- TOAST NOTIFICATIONS ---
  function showToast(message, type = 'success') {
    let container = document.getElementById('toastContainer');
    if (!container) {
      container = document.createElement('div');
      container.id = 'toastContainer';
      container.style.cssText = `
        position: fixed; bottom: 25px; right: 25px; z-index: 9999;
        display: flex; flex-direction: column; gap: 10px; pointer-events: none;
      `;
      document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.style.cssText = `
      background: #1A0D08; color: #FAF4EB; border: 1px solid #D4AF37;
      padding: 14px 22px; border-radius: 8px; font-size: 0.88rem; font-weight: 500;
      box-shadow: 0 10px 30px rgba(0,0,0,0.35); pointer-events: auto;
      display: flex; align-items: center; gap: 10px;
      transform: translateX(100px); opacity: 0; transition: all 0.35s ease;
    `;
    const icon = type === 'success' ? '✨' : '⚠️';
    toast.innerHTML = `<span>${icon}</span> <span>${message}</span>`;
    container.appendChild(toast);

    requestAnimationFrame(() => {
      toast.style.transform = 'translateX(0)';
      toast.style.opacity = '1';
    });

    setTimeout(() => {
      toast.style.transform = 'translateX(100px)';
      toast.style.opacity = '0';
      setTimeout(() => toast.remove(), 400);
    }, 3200);
  }

  // --- CART OPERATIONS ---
  function addToCart(productId, size = 'M', quantity = 1) {
    const product = (window.COFFEE_DATA?.products || []).find(p => p.id === productId);
    if (!product) return;

    let price = product.price;
    if (product.has_size && product.sizes) {
      const sizeObj = product.sizes.find(s => s.size === size);
      if (sizeObj) price = sizeObj.price;
    }

    const cart = getCart();
    const existingIndex = cart.findIndex(item => item.id === productId && item.size === size);

    if (existingIndex > -1) {
      cart[existingIndex].quantity += quantity;
    } else {
      cart.push({
        id: product.id,
        name: product.name,
        image: product.image,
        price: price,
        size: product.has_size ? size : null,
        quantity: quantity
      });
    }

    saveCart(cart);
    showToast(`Đã thêm <b>${product.name}</b> vào giỏ!`);
  }

  function updateItemQuantity(index, delta) {
    const cart = getCart();
    if (!cart[index]) return;
    cart[index].quantity += delta;
    if (cart[index].quantity <= 0) {
      cart.splice(index, 1);
    }
    saveCart(cart);
  }

  function removeFromCart(index) {
    const cart = getCart();
    cart.splice(index, 1);
    saveCart(cart);
    showToast('Đã xóa món khỏi giỏ hàng.');
  }

  function formatMoney(amount) {
    return new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
  }

  function updateCartUI() {
    const cart = getCart();
    const totalCount = cart.reduce((sum, item) => sum + item.quantity, 0);
    const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    // Update Badges
    document.querySelectorAll('.cart-badge').forEach(el => {
      el.textContent = totalCount;
      el.style.display = totalCount > 0 ? 'flex' : 'none';
    });

    // Update Drawer
    const cartItemsContainer = document.getElementById('cartDrawerItems');
    const cartTotalEl = document.getElementById('cartDrawerTotal');

    if (cartTotalEl) cartTotalEl.textContent = formatMoney(totalPrice);

    if (cartItemsContainer) {
      if (cart.length === 0) {
        cartItemsContainer.innerHTML = `
          <div style="text-align: center; padding: 50px 20px; color: #8C7B70;">
            <div style="font-size: 3rem; margin-bottom: 12px; opacity: 0.6;">☕</div>
            <p style="font-weight: 500;">Giỏ hàng của bạn đang trống.</p>
            <a href="menu.html" class="btn btn-dark" style="margin-top: 15px; font-size: 0.78rem;">Khám phá thực đơn ngay</a>
          </div>
        `;
      } else {
        cartItemsContainer.innerHTML = cart.map((item, idx) => `
          <div class="cart-item">
            <img src="${item.image}" alt="${item.name}" class="cart-item-img">
            <div class="cart-item-info">
              <h5>${item.name}</h5>
              ${item.size ? `<small>Kích cỡ: <b>Size ${item.size}</b></small>` : ''}
              <div class="cart-item-price">${formatMoney(item.price)}</div>
              <div class="cart-qty-ctrl">
                <button type="button" onclick="window.CoffeeApp.changeQty(${idx}, -1)">-</button>
                <span>${item.quantity}</span>
                <button type="button" onclick="window.CoffeeApp.changeQty(${idx}, 1)">+</button>
              </div>
            </div>
            <button type="button" class="cart-item-del" onclick="window.CoffeeApp.removeItem(${idx})" title="Xóa món">✕</button>
          </div>
        `).join('');
      }
    }
  }

  // --- CHECKOUT SUBMISSION (MÔ PHỎNG ĐẶT HÀNG KHÔNG CẦN BACKEND) ---
  function submitOrder(formData) {
    const cart = getCart();
    if (cart.length === 0) {
      showToast('Giỏ hàng của bạn đang trống!', 'error');
      return;
    }

    const orderId = 'XD' + Math.floor(100000 + Math.random() * 900000);
    const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    const order = {
      orderId: orderId,
      customerName: formData.get('name'),
      phone: formData.get('phone'),
      address: formData.get('address') || 'Nhận tại cửa hàng',
      note: formData.get('note') || '',
      items: cart,
      total: totalPrice,
      createdAt: new Date().toLocaleString('vi-VN')
    };

    // Save to localStorage
    const orders = JSON.parse(localStorage.getItem(ORDERS_KEY) || '[]');
    orders.unshift(order);
    localStorage.setItem(ORDERS_KEY, JSON.stringify(orders));

    // Clear Cart
    localStorage.removeItem(CART_KEY);
    updateCartUI();

    // Close Cart Drawer
    closeCartDrawer();

    // Show Success Modal
    showSuccessModal(order);
  }

  function showSuccessModal(order) {
    let modal = document.getElementById('orderSuccessModal');
    if (!modal) {
      modal = document.createElement('div');
      modal.id = 'orderSuccessModal';
      modal.className = 'modal-overlay';
      modal.innerHTML = `
        <div class="modal-card" style="max-width: 520px; text-align: center; padding: 45px 30px;">
          <div style="font-size: 3.5rem; margin-bottom: 15px;">🎉</div>
          <span class="eyebrow" style="color: #D4AF37;">Đặt món thành công</span>
          <h2 style="font-size: 1.8rem; margin: 10px 0 15px;">Cảm ơn quý khách!</h2>
          <p style="color: #6B5B52; font-size: 0.92rem; margin-bottom: 25px;">
            Đơn hàng mã <b style="color: #B24A24;" id="succOrderCode"></b> đã được hệ thống ghi nhận. Barista của chúng tôi đang chuẩn bị những ly cà phê thơm ngon nhất!
          </p>
          <div style="background: #FFF8EE; border: 1px dashed #D4AF37; padding: 18px; border-radius: 8px; text-align: left; margin-bottom: 25px; font-size: 0.88rem;">
            <div>👤 <b>Người nhận:</b> <span id="succCustomer"></span></div>
            <div style="margin-top: 6px;">📞 <b>Số điện thoại:</b> <span id="succPhone"></span></div>
            <div style="margin-top: 6px;">💰 <b>Tổng thanh toán:</b> <span id="succTotal" style="color: #8E2818; font-weight: 700;"></span> (Thanh toán khi nhận)</div>
          </div>
          <button type="button" class="btn btn-gold" onclick="document.getElementById('orderSuccessModal').classList.remove('active')">
            Tiếp tục thưởng thức
          </button>
        </div>
      `;
      document.body.appendChild(modal);
    }

    document.getElementById('succOrderCode').textContent = '#' + order.orderId;
    document.getElementById('succCustomer').textContent = order.customerName;
    document.getElementById('succPhone').textContent = order.phone;
    document.getElementById('succTotal').textContent = formatMoney(order.total);
    modal.classList.add('active');
  }

  // --- CART DRAWER TOGGLE ---
  function openCartDrawer() {
    document.getElementById('cartDrawer')?.classList.add('active');
    document.getElementById('cartDrawerOverlay')?.classList.add('active');
    updateCartUI();
  }

  function closeCartDrawer() {
    document.getElementById('cartDrawer')?.classList.remove('active');
    document.getElementById('cartDrawerOverlay')?.classList.remove('active');
  }

  // --- QUICK VIEW MODAL ---
  function openQuickView(productId) {
    const product = (window.COFFEE_DATA?.products || []).find(p => p.id === productId);
    if (!product) return;

    let modal = document.getElementById('quickViewModal');
    if (!modal) {
      modal = document.createElement('div');
      modal.id = 'quickViewModal';
      modal.className = 'modal-overlay';
      modal.innerHTML = `
        <div class="modal-card">
          <button type="button" class="modal-close" onclick="document.getElementById('quickViewModal').classList.remove('active')">✕</button>
          <div class="modal-grid">
            <img id="qvImg" src="" alt="" class="modal-img">
            <div class="modal-body">
              <span class="eyebrow" id="qvCategory"></span>
              <h3 id="qvTitle" style="font-size: 1.5rem; margin-bottom: 10px;"></h3>
              <div id="qvPrice" style="font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 700; color: #8E2818; margin-bottom: 14px;"></div>
              <p id="qvDesc" style="font-size: 0.88rem; color: #6B5B52; line-height: 1.6; margin-bottom: 20px;"></p>
              
              <div id="qvSizeWrap" style="margin-bottom: 20px; display: none;">
                <label style="font-size: 0.78rem; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 8px;">Chọn kích cỡ:</label>
                <div id="qvSizes" style="display: flex; gap: 8px;"></div>
              </div>

              <div style="display: flex; gap: 12px; align-items: center; margin-top: 10px;">
                <button type="button" id="qvAddBtn" class="btn btn-gold" style="flex-grow: 1;">
                  Thêm vào giỏ
                </button>
              </div>
            </div>
          </div>
        </div>
      `;
      document.body.appendChild(modal);
    }

    const cat = (window.COFFEE_DATA?.categories || []).find(c => c.id === product.category_id);
    document.getElementById('qvCategory').textContent = cat ? cat.name : 'Đặc sản';
    document.getElementById('qvTitle').textContent = product.name;
    document.getElementById('qvImg').src = product.image;
    document.getElementById('qvDesc').textContent = product.desc;
    document.getElementById('qvPrice').textContent = formatMoney(product.price);

    let selectedSize = 'M';
    const sizeWrap = document.getElementById('qvSizeWrap');
    const sizesContainer = document.getElementById('qvSizes');

    if (product.has_size && product.sizes && product.sizes.length > 0) {
      sizeWrap.style.display = 'block';
      sizesContainer.innerHTML = product.sizes.map(s => `
        <button type="button" class="btn-size ${s.size === 'M' ? 'active' : ''}" data-size="${s.size}" data-price="${s.price}" style="
          padding: 8px 16px; border: 1.5px solid ${s.size === 'M' ? '#B24A24' : '#d5c8b8'};
          background: ${s.size === 'M' ? '#B24A24' : '#fff'}; color: ${s.size === 'M' ? '#fff' : '#1A0D08'};
          border-radius: 4px; font-weight: 700; cursor: pointer;
        ">
          Size ${s.size} (${formatMoney(s.price)})
        </button>
      `).join('');

      sizesContainer.querySelectorAll('.btn-size').forEach(btn => {
        btn.addEventListener('click', function () {
          sizesContainer.querySelectorAll('.btn-size').forEach(b => {
            b.style.borderColor = '#d5c8b8';
            b.style.background = '#fff';
            b.style.color = '#1A0D08';
          });
          this.style.borderColor = '#B24A24';
          this.style.background = '#B24A24';
          this.style.color = '#fff';
          selectedSize = this.dataset.size;
          document.getElementById('qvPrice').textContent = formatMoney(Number(this.dataset.price));
        });
      });
    } else {
      sizeWrap.style.display = 'none';
    }

    const addBtn = document.getElementById('qvAddBtn');
    addBtn.onclick = () => {
      addToCart(product.id, selectedSize, 1);
      modal.classList.remove('active');
    };

    modal.classList.add('active');
  }

  // --- RESERVATION SUBMISSION ---
  function submitReservation(formData) {
    const reservation = {
      name: formData.get('name'),
      phone: formData.get('phone'),
      date: formData.get('date'),
      time: formData.get('time'),
      guests: formData.get('guests'),
      location: formData.get('location') || 'Khu vực trong nhà',
      note: formData.get('note') || '',
      createdAt: new Date().toLocaleString('vi-VN')
    };

    const reservations = JSON.parse(localStorage.getItem(RESERVATIONS_KEY) || '[]');
    reservations.unshift(reservation);
    localStorage.setItem(RESERVATIONS_KEY, JSON.stringify(reservations));

    showToast('✨ Quý khách đã đặt bàn thành công! Quán sẽ giữ bàn đúng giờ hẹn.');
  }

  // --- INITIALIZATION ON DOM READY ---
  document.addEventListener('DOMContentLoaded', () => {
    updateCartUI();

    // Scroll Navbar Effect
    const nav = document.getElementById('mainNav');
    window.addEventListener('scroll', () => {
      if (window.scrollY > 40) {
        nav?.classList.add('scrolled');
      } else {
        nav?.classList.remove('scrolled');
      }
    });

    // Mobile Menu Toggle
    const mobileBtn = document.querySelector('.mobile-toggle');
    const navMenu = document.querySelector('.nav-menu');
    mobileBtn?.addEventListener('click', () => {
      navMenu?.classList.toggle('open');
    });

    // Cart Drawer Toggle Handlers
    document.querySelectorAll('.cart-toggle-btn').forEach(btn => {
      btn.addEventListener('click', openCartDrawer);
    });
    document.getElementById('cartCloseBtn')?.addEventListener('click', closeCartDrawer);
    document.getElementById('cartDrawerOverlay')?.addEventListener('click', closeCartDrawer);

    // Fast Checkout Form in Drawer
    document.getElementById('checkoutForm')?.addEventListener('submit', function (e) {
      e.preventDefault();
      submitOrder(new FormData(this));
      this.reset();
    });

    // Fast Reservation Form
    document.getElementById('reservationForm')?.addEventListener('submit', function (e) {
      e.preventDefault();
      submitReservation(new FormData(this));
      this.reset();
    });

    // Search bar functionality
    document.querySelectorAll('.nav-search').forEach(form => {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        const query = this.querySelector('input')?.value.trim();
        if (query) {
          window.location.href = `menu.html?q=${encodeURIComponent(query)}`;
        }
      });
    });
  });

  // Global API
  window.CoffeeApp = {
    addToCart,
    changeQty: updateItemQuantity,
    removeItem: removeFromCart,
    openCart: openCartDrawer,
    closeCart: closeCartDrawer,
    openQuickView,
    formatMoney
  };

})();
