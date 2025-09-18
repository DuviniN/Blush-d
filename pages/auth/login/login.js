class LoginHandler {
    constructor() {
        this.form = document.getElementById('loginForm');
        this.submitBtn = document.getElementById('loginBtn');
        this.isSubmitting = false;
        
        this.init();
    }
    
    init() {
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        
        // Real-time validation
        const inputs = this.form.querySelectorAll('input[type="email"], input[type="password"]');
        inputs.forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
            input.addEventListener('input', () => this.clearFieldError(input));
        });
        
        // Check if already logged in
        this.checkExistingSession();
    }
    
    async checkExistingSession() {
        try {
            const response = await fetch('../../../server/api.php?endpoint=auth&action=check', {
                method: 'GET',
                credentials: 'same-origin'
            });
            
            const result = await response.json();
            
            if (result.success && result.data) {
                // User is already logged in, redirect based on role
                this.redirectBasedOnRole(result.data.role);
            }
        } catch (error) {
            // Ignore errors for session check
            console.log('No existing session found');
        }
    }
    
    async handleSubmit(e) {
        e.preventDefault();
        
        if (this.isSubmitting) return;
        
        // Clear previous errors
        this.clearAllErrors();
        
        // Validate form
        if (!this.validateForm()) {
            return;
        }
        
        this.isSubmitting = true;
        this.setLoadingState(true);
        
        try {
            const formData = new FormData(this.form);
            const data = {
                email: formData.get('email').trim(),
                password: formData.get('password')
            };
            
            const response = await fetch('../../../server/api.php?endpoint=auth&action=login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                credentials: 'same-origin',
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Show success message briefly
                this.showSuccessMessage(result.data);
                
                // Redirect based on user role after a short delay
                setTimeout(() => {
                    this.redirectBasedOnRole(result.data.role);
                }, 1500);
            } else {
                this.showError('general', result.message || 'Login failed. Please try again.');
            }
        } catch (error) {
            console.error('Login error:', error);
            this.showError('general', 'Network error. Please check your connection and try again.');
        } finally {
            this.isSubmitting = false;
            this.setLoadingState(false);
        }
    }
    
    redirectBasedOnRole(role) {
        switch (role.toUpperCase()) {
            case 'CUSTOMER':
                window.location.href = '../../customer/dashboard/Dashboard.php';
                break;
            case 'MANAGER':
                window.location.href = '../../manager/dashboard/Dashboard.php';
                break;
            case 'ADMIN':
                window.location.href = '../../admin/index.php';
                break;
            default:
                console.error('Unknown role:', role);
                this.showError('general', 'Unknown user role. Please contact support.');
        }
    }
    
    validateForm() {
        let isValid = true;
        
        // Email validation
        const email = document.getElementById('email').value.trim();
        if (!email) {
            this.showError('email', 'Email is required');
            isValid = false;
        } else if (!this.isValidEmail(email)) {
            this.showError('email', 'Please enter a valid email address');
            isValid = false;
        }
        
        // Password validation
        const password = document.getElementById('password').value;
        if (!password) {
            this.showError('password', 'Password is required');
            isValid = false;
        }
        
        return isValid;
    }
    
    validateField(input) {
        const fieldName = input.name;
        const value = input.value.trim();
        
        switch (fieldName) {
            case 'email':
                if (!value) {
                    this.showError('email', 'Email is required');
                } else if (!this.isValidEmail(value)) {
                    this.showError('email', 'Please enter a valid email address');
                }
                break;
                
            case 'password':
                if (!value) {
                    this.showError('password', 'Password is required');
                }
                break;
        }
    }
    
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    showError(fieldName, message) {
        if (fieldName === 'general') {
            // Show general error at the top of the form
            let generalError = document.getElementById('generalError');
            if (!generalError) {
                generalError = document.createElement('div');
                generalError.id = 'generalError';
                generalError.className = 'general-error-message';
                this.form.insertBefore(generalError, this.form.firstChild);
            }
            generalError.textContent = message;
            generalError.style.display = 'block';
        } else {
            const errorElement = document.getElementById(fieldName + 'Error');
            const inputElement = document.getElementById(fieldName);
            
            if (errorElement) {
                errorElement.textContent = message;
                errorElement.style.display = 'block';
            }
            
            if (inputElement) {
                inputElement.classList.add('error');
            }
        }
    }
    
    clearFieldError(input) {
        const fieldName = input.name;
        const errorElement = document.getElementById(fieldName + 'Error');
        
        if (errorElement) {
            errorElement.style.display = 'none';
        }
        
        input.classList.remove('error');
    }
    
    clearAllErrors() {
        const errorMessages = this.form.querySelectorAll('.error-message');
        errorMessages.forEach(error => error.style.display = 'none');
        
        const inputs = this.form.querySelectorAll('input');
        inputs.forEach(input => input.classList.remove('error'));
        
        const generalError = document.getElementById('generalError');
        if (generalError) {
            generalError.style.display = 'none';
        }
    }
    
    setLoadingState(loading) {
        const btnText = this.submitBtn.querySelector('.btn-text');
        const btnLoading = this.submitBtn.querySelector('.btn-loading');
        
        if (loading) {
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline';
            this.submitBtn.disabled = true;
        } else {
            btnText.style.display = 'inline';
            btnLoading.style.display = 'none';
            this.submitBtn.disabled = false;
        }
    }
    
    showSuccessMessage(userData) {
        // Create a temporary success overlay
        const overlay = document.createElement('div');
        overlay.className = 'success-overlay';
        overlay.innerHTML = `
            <div class="success-content">
                <div class="success-icon">✓</div>
                <h3>Welcome back, ${userData.first_name}!</h3>
                <p>Redirecting to your ${userData.role.toLowerCase()} dashboard...</p>
            </div>
        `;
        
        document.body.appendChild(overlay);
        
        // Remove overlay after redirect
        setTimeout(() => {
            if (overlay.parentNode) {
                overlay.parentNode.removeChild(overlay);
            }
        }, 2000);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new LoginHandler();
});
