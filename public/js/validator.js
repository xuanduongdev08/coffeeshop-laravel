/**
 * FormValidator Class
 * Xử lý validation phía Client-side theo phong cách OOP
 */
class FormValidator {
    constructor(formSelector) {
        this.form = document.querySelector(formSelector);
        if (!this.form) return;

        // Lưu trữ các rules
        this.rules = {};

        // Khởi tạo các event listeners
        this.init();
    }

    /**
     * Khởi tạo logic
     */
    init() {
        // Tìm tất cả input có data-validate
        const inputs = this.form.querySelectorAll('[data-validate]');

        inputs.forEach(input => {
            // Lắng nghe sự kiện input (khi gõ) và blur (khi click ra ngoài)
            ['input', 'blur'].forEach(eventType => {
                input.addEventListener(eventType, () => {
                    this.validateInput(input);
                });
            });
        });
    }

    /**
     * Validate một input cụ thể
     */
    validateInput(input) {
        const type = input.dataset.validate;
        const value = input.value;
        let isValid = true;
        let errorMessage = '';

        // Logic check theo từng loại
        switch (type) {
            case 'confirm-password':
                const passwordInput = this.form.querySelector(input.dataset.target);
                if (passwordInput && value !== passwordInput.value) {
                    isValid = false;
                    errorMessage = 'Mật khẩu xác nhận không khớp!';
                }
                break;

            case 'password':
                if (value.length < 6) {
                    isValid = false;
                    errorMessage = 'Mật khẩu phải có ít nhất 6 ký tự';
                }
                // Nếu sửa password chính, cũng cần check lại confirm password (nếu có)
                this.revalidateConfirmPassword();
                break;

            case 'email':
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    isValid = false;
                    errorMessage = 'Email không hợp lệ';
                }
                break;

            case 'phone': // Thêm rule cho số điện thoại
                const phoneRegex = /^0[0-9]{9}$/;
                if (value.length > 0 && !phoneRegex.test(value)) {
                    isValid = false;
                    errorMessage = 'Số điện thoại phải bắt đầu bằng 0 và gồm 10 số';
                }
                break;
        }

        // Hiển thị hoặc ẩn lỗi
        this.toggleError(input, isValid, errorMessage);

        return isValid;
    }

    /**
     * Check lại confirm password khi password chính thay đổi
     */
    revalidateConfirmPassword() {
        const confirmInput = this.form.querySelector('[data-validate="confirm-password"]');
        if (confirmInput && confirmInput.value !== '') {
            this.validateInput(confirmInput);
        }
    }

    /**
     * Hiển thị lỗi ra UI
     */
    toggleError(input, isValid, message) {
        // Tìm hoặc tạo element hiển thị lỗi
        let errorDisplay = input.nextElementSibling;

        // Nếu element kế tiếp không phải là small.text-danger thì tạo mới
        if (!errorDisplay || !errorDisplay.classList.contains('validation-message')) {
            errorDisplay = document.createElement('small');
            errorDisplay.className = 'text-danger validation-message';
            errorDisplay.style.display = 'none';
            errorDisplay.style.marginTop = '5px';
            errorDisplay.style.fontWeight = 'bold';
            input.parentNode.insertBefore(errorDisplay, input.nextSibling);
        }

        if (!isValid) {
            input.classList.add('is-invalid');
            input.style.borderColor = '#dc3545';
            errorDisplay.textContent = message;
            errorDisplay.style.display = 'block';
        } else {
            input.classList.remove('is-invalid');
            input.style.borderColor = '#28a745'; // Xanh lá khi đúng
            if (input.value === '') input.style.borderColor = ''; // Reset nếu rỗng
            errorDisplay.style.display = 'none';
        }
    }
}
