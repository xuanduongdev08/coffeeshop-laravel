/**
 * Review System JavaScript
 * Xử lý tương tác đánh giá sản phẩm
 */

class ReviewSystem {
    constructor(productId, orderId = null) {
        this.productId = productId;
        this.orderId = orderId;
        this.currentPage = 1;
        this.selectedRating = 0;
        this.init();
    }
    
    init() {
        this.loadReviews();
        this.loadStatistics();
        this.setupEventListeners();
        this.checkUserReview();
    }
    
    setupEventListeners() {
        // Nút viết đánh giá
        const btnWriteReview = document.getElementById('btnWriteReview');
        if (btnWriteReview) {
            btnWriteReview.addEventListener('click', () => this.openReviewModal());
        }
        
        // Đánh giá sao
        const stars = document.querySelectorAll('.star-rating i');
        stars.forEach((star, index) => {
            star.addEventListener('click', () => this.setRating(index + 1));
            star.addEventListener('mouseenter', () => this.hoverRating(index + 1));
        });
        
        const starRating = document.querySelector('.star-rating');
        if (starRating) {
            starRating.addEventListener('mouseleave', () => this.resetHover());
        }
        
        // Submit form
        const reviewForm = document.getElementById('reviewForm');
        if (reviewForm) {
            reviewForm.addEventListener('submit', (e) => this.submitReview(e));
        }
        
        // Upload ảnh
        const imageInput = document.getElementById('reviewImage');
        if (imageInput) {
            imageInput.addEventListener('change', (e) => this.previewImages(e));
        }
        
        // Load more
        const btnLoadMore = document.getElementById('btnLoadMore');
        if (btnLoadMore) {
            btnLoadMore.addEventListener('click', () => this.loadMoreReviews());
        }
    }
    
    async loadReviews() {
        try {
            const response = await fetch(`Controller/review.php?action=list&id_hanghoa=${this.productId}&page=${this.currentPage}`);
            const data = await response.json();
            
            if (data.success) {
                this.renderReviews(data.reviews, data.page === 1);
                this.updateLoadMoreButton(data.page, data.total_pages);
            }
        } catch (error) {
            console.error('Error loading reviews:', error);
        }
    }
    
    async loadStatistics() {
        try {
            const response = await fetch(`Controller/review.php?action=list&id_hanghoa=${this.productId}&page=1`);
            const data = await response.json();
            
            if (data.success) {
                this.renderStatistics(data);
            }
        } catch (error) {
            console.error('Error loading statistics:', error);
        }
    }
    
    renderStatistics(data) {
        // Tính toán thống kê từ reviews
        const reviews = data.reviews || [];
        const total = reviews.length;
        
        if (total === 0) return;
        
        let sum = 0;
        const starCounts = { 5: 0, 4: 0, 3: 0, 2: 0, 1: 0 };
        
        reviews.forEach(review => {
            sum += parseInt(review.so_sao);
            starCounts[review.so_sao]++;
        });
        
        const average = (sum / total).toFixed(1);
        
        // Cập nhật điểm trung bình
        const scoreElement = document.querySelector('.rating-score .score');
        if (scoreElement) {
            scoreElement.textContent = average;
        }
        
        // Cập nhật số lượng đánh giá
        const totalElement = document.querySelector('.total-reviews');
        if (totalElement) {
            totalElement.textContent = `${total} đánh giá`;
        }
        
        // Cập nhật thanh tiến trình
        for (let i = 5; i >= 1; i--) {
            const percentage = total > 0 ? ((starCounts[i] / total) * 100).toFixed(1) : 0;
            const progressBar = document.querySelector(`#star${i}Progress`);
            const percentageText = document.querySelector(`#star${i}Percentage`);
            
            if (progressBar) {
                progressBar.style.width = percentage + '%';
            }
            if (percentageText) {
                percentageText.textContent = percentage + '%';
            }
        }
    }
    
    renderReviews(reviews, clearFirst = false) {
        const container = document.getElementById('reviewList');
        if (!container) return;
        
        if (clearFirst) {
            container.innerHTML = '';
        }
        
        if (reviews.length === 0 && clearFirst) {
            container.innerHTML = `
                <div class="empty-reviews">
                    <i class="fas fa-comments"></i>
                    <h4>Chưa có đánh giá nào</h4>
                    <p>Hãy là người đầu tiên đánh giá sản phẩm này!</p>
                </div>
            `;
            return;
        }
        
        reviews.forEach(review => {
            const reviewHtml = this.createReviewHTML(review);
            container.insertAdjacentHTML('beforeend', reviewHtml);
        });
    }
    
    createReviewHTML(review) {
        const stars = this.generateStars(review.so_sao);
        const date = new Date(review.ngay_tao).toLocaleDateString('vi-VN');
        const avatar = review.ten_khachhang.charAt(0).toUpperCase();
        
        return `
            <div class="review-item">
                <div class="review-item-header">
                    <div class="reviewer-info">
                        <div class="reviewer-avatar">${avatar}</div>
                        <div class="reviewer-details">
                            <h5>${this.escapeHtml(review.ten_khachhang)}
                                ${review.id_donhang ? '<span class="verified-badge"><i class="fas fa-check-circle"></i>Đã mua tại XDTHECOFFEESHOP</span>' : ''}
                            </h5>
                            <div class="review-stars">${stars}</div>
                            <div class="review-date">${date}</div>
                        </div>
                    </div>
                </div>
                <div class="review-content">
                    ${review.tieu_de ? `<h6>${this.escapeHtml(review.tieu_de)}</h6>` : ''}
                    <p>${this.escapeHtml(review.noi_dung)}</p>
                    ${review.hinh_anh ? `
                        <div class="review-images">
                            <img src="${review.hinh_anh}" alt="Review image" onclick="window.open(this.src, '_blank')">
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
    }
    
    generateStars(rating) {
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            stars += i <= rating ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
        }
        return stars;
    }
    
    setRating(rating) {
        this.selectedRating = rating;
        this.updateStarDisplay();
    }
    
    hoverRating(rating) {
        const stars = document.querySelectorAll('.star-rating i');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
    }
    
    resetHover() {
        this.updateStarDisplay();
    }
    
    updateStarDisplay() {
        const stars = document.querySelectorAll('.star-rating i');
        stars.forEach((star, index) => {
            if (index < this.selectedRating) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
    }
    
    async openReviewModal() {
        // Kiểm tra đăng nhập
        const isLoggedIn = document.body.dataset.loggedIn === 'true';
        
        if (!isLoggedIn) {
            this.showAlert('Vui lòng đăng nhập tài khoản trước khi đánh giá', 'error');
            setTimeout(() => {
                window.location.href = 'index.php?action=login';
            }, 2000);
            return;
        }
        
        // Mở modal
        const modal = new bootstrap.Modal(document.getElementById('reviewModal'));
        modal.show();
    }
    
    previewImages(e) {
        const files = e.target.files;
        const preview = document.getElementById('imagePreview');
        
        if (!preview) return;
        
        preview.innerHTML = '';
        
        Array.from(files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const html = `
                        <div class="image-preview-item">
                            <img src="${e.target.result}" alt="Preview">
                            <button type="button" class="remove-image" onclick="reviewSystem.removeImage(${index})">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                    preview.insertAdjacentHTML('beforeend', html);
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    removeImage(index) {
        const input = document.getElementById('reviewImage');
        const dt = new DataTransfer();
        const files = input.files;
        
        for (let i = 0; i < files.length; i++) {
            if (i !== index) {
                dt.items.add(files[i]);
            }
        }
        
        input.files = dt.files;
        this.previewImages({ target: input });
    }
    
    async submitReview(e) {
        e.preventDefault();
        
        if (this.selectedRating === 0) {
            this.showAlert('Vui lòng chọn số sao đánh giá', 'error');
            return;
        }
        
        const formData = new FormData(e.target);
        formData.append('id_hanghoa', this.productId);
        formData.append('so_sao', this.selectedRating);
        
        if (this.orderId) {
            formData.append('id_donhang', this.orderId);
        }
        
        try {
            const response = await fetch('Controller/review.php?action=add', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showAlert(data.message, 'success');
                
                // Đóng modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('reviewModal'));
                modal.hide();
                
                // Reset form
                e.target.reset();
                this.selectedRating = 0;
                this.updateStarDisplay();
                
                // Reload reviews
                this.currentPage = 1;
                this.loadReviews();
                this.loadStatistics();
                
                // Reload trang sau 2 giây
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                this.showAlert(data.message, 'error');
            }
        } catch (error) {
            console.error('Error submitting review:', error);
            this.showAlert('Có lỗi xảy ra, vui lòng thử lại', 'error');
        }
    }
    
    async checkUserReview() {
        const isLoggedIn = document.body.dataset.loggedIn === 'true';
        if (!isLoggedIn) return;
        
        try {
            let url = `Controller/review.php?action=check&id_hanghoa=${this.productId}`;
            if (this.orderId) {
                url += `&id_donhang=${this.orderId}`;
            }
            
            const response = await fetch(url);
            const data = await response.json();
            
            if (data.reviewed) {
                const btnWriteReview = document.getElementById('btnWriteReview');
                if (btnWriteReview) {
                    btnWriteReview.textContent = 'Xem đánh giá của bạn';
                    btnWriteReview.classList.remove('btn-write-review');
                    btnWriteReview.classList.add('btn-view-review');
                }
            }
        } catch (error) {
            console.error('Error checking user review:', error);
        }
    }
    
    loadMoreReviews() {
        this.currentPage++;
        this.loadReviews();
    }
    
    updateLoadMoreButton(currentPage, totalPages) {
        const btnLoadMore = document.getElementById('btnLoadMore');
        if (!btnLoadMore) return;
        
        if (currentPage >= totalPages) {
            btnLoadMore.style.display = 'none';
        } else {
            btnLoadMore.style.display = 'block';
        }
    }
    
    showAlert(message, type = 'info') {
        const alertContainer = document.getElementById('reviewAlertContainer');
        if (!alertContainer) {
            // Tạo container nếu chưa có
            const container = document.createElement('div');
            container.id = 'reviewAlertContainer';
            container.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
            document.body.appendChild(container);
        }
        
        const alert = document.createElement('div');
        alert.className = `review-alert ${type}`;
        alert.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        `;
        
        document.getElementById('reviewAlertContainer').appendChild(alert);
        
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 3000);
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Khởi tạo khi DOM loaded
document.addEventListener('DOMContentLoaded', function() {
    const reviewSection = document.getElementById('reviewSection');
    if (reviewSection) {
        const productId = reviewSection.dataset.productId;
        const orderId = reviewSection.dataset.orderId || null;
        window.reviewSystem = new ReviewSystem(productId, orderId);
    }
});
