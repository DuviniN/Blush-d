class RegisterHandler {
    constructor() {
        this.form = document.getElementById('registerForm');
        this.submitBtn = document.getElementById('registerBtn');
        this.successMessage = document.getElementById('successMessage');
        this.isSubmitting = false;
        
        this.init();
    }
    
    init() {
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        
        // Real-time validation
        const inputs = this.form.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
            input.addEventListener('input', () => this.clearFieldError(input));
        });
        
        // Password confirmation validation
        document.getElementById('confirmPassword').addEventListener('input', () => {
            this.validatePasswordMatch();
        });
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
                firstName: formData.get('firstName').trim(),
                lastName: formData.get('lastName').trim(),
                email: formData.get('email').trim(),
                password: formData.get('password')
            };
            
            const response = await fetch('../../../server/api.php?endpoint=auth&action=register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showSuccessMessage();
                // Redirect to customer dashboard after 2 seconds
                setTimeout(() => {
                    window.location.href = '../../customer/dashboard/dashboard.php';
                }, 2000);
            } else {
                this.showError('general', result.message || 'Registration failed. Please try again.');
            }
        } catch (error) {
            console.error('Registration error:', error);
            this.showError('general', 'Network error. Please check your connection and try again.');
        } finally {
            this.isSubmitting = false;
            this.setLoadingState(false);
        }
    }
    
    validateForm() {
        let isValid = true;
        
        // First Name validation
        const firstName = document.getElementById('firstName').value.trim();
        if (!firstName) {
            this.showError('firstName', 'First name is required');
            isValid = false;
        } else if (firstName.length < 2) {
            this.showError('firstName', 'First name must be at least 2 characters');
            isValid = false;
        }
        
        // Last Name validation
        const lastName = document.getElementById('lastName').value.trim();
        if (!lastName) {
            this.showError('lastName', 'Last name is required');
            isValid = false;
        } else if (lastName.length < 2) {
            this.showError('lastName', 'Last name must be at least 2 characters');
            isValid = false;
        }
        
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
        } else if (password.length < 8) {
            this.showError('password', 'Password must be at least 8 characters long');
            isValid = false;
        }
        
        // Confirm Password validation
        const confirmPassword = document.getElementById('confirmPassword').value;
        if (!confirmPassword) {
            this.showError('confirmPassword', 'Please confirm your password');
            isValid = false;
        } else if (password !== confirmPassword) {
            this.showError('confirmPassword', 'Passwords do not match');
            isValid = false;
        }
        
        return isValid;
    }
    
    validateField(input) {
        const fieldName = input.name;
        const value = input.value.trim();
        
        switch (fieldName) {
            case 'firstName':
                if (!value) {
                    this.showError('firstName', 'First name is required');
                } else if (value.length < 2) {
                    this.showError('firstName', 'First name must be at least 2 characters');
                }
                break;
                
            case 'lastName':
                if (!value) {
                    this.showError('lastName', 'Last name is required');
                } else if (value.length < 2) {
                    this.showError('lastName', 'Last name must be at least 2 characters');
                }
                break;
                
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
                } else if (value.length < 8) {
                    this.showError('password', 'Password must be at least 8 characters long');
                }
                this.validatePasswordMatch();
                break;
        }
    }
    
    validatePasswordMatch() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        
        if (confirmPassword && password !== confirmPassword) {
            this.showError('confirmPassword', 'Passwords do not match');
        } else {
            this.clearFieldError(document.getElementById('confirmPassword'));
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
    
    showSuccessMessage() {
        this.form.style.display = 'none';
        this.successMessage.style.display = 'block';
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new RegisterHandler();
});
