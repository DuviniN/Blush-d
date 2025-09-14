/**
 * Password Manager for Change Password Modal
 * Handles frontend validation and API communication
 */

class PasswordManager {
    constructor() {
        this.apiEndpoint = '../../../server/api.php?endpoint=password';
        this.passwordRequirements = {
            'length-req': { 
                regex: /.{8,}/, 
                message: 'At least 8 characters long',
                element: 'req-length'
            },
            'uppercase-req': { 
                regex: /[A-Z]/, 
                message: 'One uppercase letter (A-Z)',
                element: 'req-uppercase'
            },
            'lowercase-req': { 
                regex: /[a-z]/, 
                message: 'One lowercase letter (a-z)',
                element: 'req-lowercase'
            },
            'number-req': { 
                regex: /\d/, 
                message: 'One number (0-9)',
                element: 'req-number'
            },
            'special-req': { 
                regex: /[!@#$%^&*(),.?":{}|<>]/, 
                message: 'One special character (!@#$%^&*)',
                element: 'req-special'
            }
        };
        this.init();
    }

    init() {
        this.bindEvents();
        this.addStyles();
    }

    bindEvents() {
        // Get form elements
        const currentPassword = document.querySelector('input[name="currentPassword"]');
        const newPassword = document.querySelector('input[name="newPassword"]');
        const confirmPassword = document.querySelector('input[name="confirmPassword"]');
        const form = document.getElementById('changePasswordForm');

        if (newPassword) {
            newPassword.addEventListener('input', () => {
                this.validateNewPassword();
                this.validatePasswordMatch(); // Check match when new password changes
            });
            newPassword.addEventListener('focus', () => this.showRequirements());
        }

        if (confirmPassword) {
            confirmPassword.addEventListener('input', () => this.validatePasswordMatch());
        }

        if (currentPassword) {
            currentPassword.addEventListener('input', () => {
                this.clearErrors();
                this.validatePasswordMatch(); // Check submit button state when current password changes
            });
        }

        if (form) {
            form.addEventListener('submit', (e) => this.handleSubmit(e));
        }

        // Add event listeners for password toggle buttons
        this.bindPasswordToggleEvents();
    }

    bindPasswordToggleEvents() {
        // Use a more robust method to bind events, including delayed binding
        const bindEvents = () => {
            const toggleButtons = document.querySelectorAll('.password-toggle');
            
            toggleButtons.forEach((button, index) => {
                // Remove any existing event listeners to avoid duplicates
                const newButton = button.cloneNode(true);
                button.parentNode.replaceChild(newButton, button);
                
                newButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const targetName = newButton.getAttribute('data-target');
                    if (targetName) {
                        this.togglePasswordVisibility(targetName);
                    }
                });
            });
        };
        
        // Bind immediately
        bindEvents();
        
        // Also bind after a short delay to catch any dynamically loaded content
        setTimeout(bindEvents, 100);
    }

    togglePasswordVisibility(inputName) {
        const input = document.querySelector(`input[name="${inputName}"]`);
        if (!input) {
            return;
        }
        
        const button = input.parentNode.querySelector('.password-toggle');
        if (!button) {
            return;
        }
        
        const icon = button.querySelector('i');
        if (!icon) {
            return;
        }
        
        // Store the current value and cursor position
        const currentValue = input.value;
        const cursorPosition = input.selectionStart;
        
        if (input.type === 'password') {
            // Change to text type
            input.setAttribute('type', 'text');
            input.type = 'text';
            icon.className = 'fas fa-eye-slash';
            
            // Force browser to recognize the change
            input.style.fontFamily = 'inherit';
            input.style.webkitTextSecurity = 'none';
            
        } else {
            // Change to password type
            input.setAttribute('type', 'password');
            input.type = 'password';
            icon.className = 'fas fa-eye';
            
            // Remove text security override
            input.style.webkitTextSecurity = '';
        }
        
        // Restore value and cursor position
        input.value = currentValue;
        input.setSelectionRange(cursorPosition, cursorPosition);
        
        // Force a repaint
        input.style.display = 'none';
        input.offsetHeight; // trigger reflow
        input.style.display = '';
    }

    showRequirements() {
        const requirementsDiv = document.getElementById('passwordRequirements');
        const strengthDiv = document.getElementById('passwordStrength');
        
        if (requirementsDiv) {
            requirementsDiv.style.display = 'block';
        }
        if (strengthDiv) {
            strengthDiv.style.display = 'block';
        }
    }

    validateNewPassword() {
        const newPassword = document.querySelector('input[name="newPassword"]').value;
        let validCount = 0;

        // Check each requirement
        Object.keys(this.passwordRequirements).forEach(reqKey => {
            const requirement = this.passwordRequirements[reqKey];
            const element = document.getElementById(requirement.element);
            
            if (element) {
                const isValid = requirement.regex.test(newPassword);
                const icon = element.querySelector('.requirement-icon');
                
                if (isValid) {
                    element.classList.add('valid');
                    element.classList.remove('invalid');
                    if (icon) {
                        icon.className = 'fas fa-check requirement-icon';
                    }
                    validCount++;
                } else {
                    element.classList.remove('valid');
                    element.classList.add('invalid');
                    if (icon) {
                        icon.className = 'fas fa-times requirement-icon';
                    }
                }
            }
        });

        this.updatePasswordStrength(newPassword, validCount);
        
        return validCount === Object.keys(this.passwordRequirements).length;
    }

    // Helper method to check password requirements without UI updates (to avoid recursion)
    checkPasswordRequirements(password) {
        let validCount = 0;
        Object.keys(this.passwordRequirements).forEach(reqKey => {
            const requirement = this.passwordRequirements[reqKey];
            if (requirement.regex.test(password)) {
                validCount++;
            }
        });
        return validCount === Object.keys(this.passwordRequirements).length;
    }

    updatePasswordStrength(password, validCount) {
        const strengthFill = document.getElementById('strengthFill');
        const strengthText = document.getElementById('strengthText');
        
        if (!strengthFill || !strengthText) return;

        let strength = '';
        let className = '';
        let percentage = 0;

        if (password.length === 0) {
            strength = 'Password strength';
            className = '';
            percentage = 0;
        } else if (validCount < 2) {
            strength = 'Very Weak';
            className = 'very-weak';
            percentage = 20;
        } else if (validCount < 3) {
            strength = 'Weak';
            className = 'weak';
            percentage = 40;
        } else if (validCount < 4) {
            strength = 'Fair';
            className = 'fair';
            percentage = 60;
        } else if (validCount < 5) {
            strength = 'Good';
            className = 'good';
            percentage = 80;
        } else {
            strength = 'Strong';
            className = 'strong';
            percentage = 100;
        }

        strengthFill.className = `strength-fill ${className}`;
        strengthFill.style.width = `${percentage}%`;
        strengthText.textContent = strength;
    }

    validatePasswordMatch() {
        const newPassword = document.querySelector('input[name="newPassword"]').value;
        const confirmPassword = document.querySelector('input[name="confirmPassword"]').value;
        const matchDiv = document.getElementById('passwordMatch');
        const matchIndicator = document.getElementById('matchIndicator');
        const submitBtn = document.getElementById('changePasswordBtn');
        
        if (!confirmPassword) {
            if (matchDiv) matchDiv.style.display = 'none';
            if (submitBtn) submitBtn.disabled = true;
            return false;
        }

        if (matchDiv) matchDiv.style.display = 'block';

        const isMatch = newPassword === confirmPassword;
        // Check password validity without calling validateNewPassword to avoid recursion
        const isPasswordValid = this.checkPasswordRequirements(newPassword);
        const currentPasswordValue = document.querySelector('input[name="currentPassword"]').value;

        if (matchIndicator) {
            if (isMatch) {
                matchIndicator.innerHTML = '<i class="fas fa-check-circle"></i> Passwords match';
                matchIndicator.className = 'match-indicator success';
            } else {
                matchIndicator.innerHTML = '<i class="fas fa-times-circle"></i> Passwords do not match';
                matchIndicator.className = 'match-indicator error';
            }
        }

        // Enable submit button only if all conditions are met
        if (submitBtn) {
            submitBtn.disabled = !(isMatch && isPasswordValid && currentPasswordValue.length > 0);
        }

        return isMatch;
    }

    clearErrors() {
        // Clear any existing error messages
        const errorElements = document.querySelectorAll('.error-message');
        errorElements.forEach(el => el.remove());
    }

    async handleSubmit(event) {
        event.preventDefault();
        
        const currentPassword = document.querySelector('input[name="currentPassword"]').value;
        const newPassword = document.querySelector('input[name="newPassword"]').value;
        const confirmPassword = document.querySelector('input[name="confirmPassword"]').value;

        // Final validation
        if (!this.validateInputs(currentPassword, newPassword, confirmPassword)) {
            return;
        }

        this.setLoadingState(true);

        try {
            const response = await fetch(this.apiEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    action: 'change_password',
                    currentPassword: currentPassword,
                    newPassword: newPassword
                })
            });

            const result = await response.json();

            if (result.success) {
                this.handleSuccess();
            } else {
                this.handleError(result.message);
            }
        } catch (error) {
            console.error('Password change error:', error);
            this.handleError('Network error occurred. Please try again.');
        }
    }

    validateInputs(currentPassword, newPassword, confirmPassword) {
        if (!currentPassword || !newPassword || !confirmPassword) {
            this.showNotification('Please fill in all fields', 'error');
            return false;
        }

        if (newPassword !== confirmPassword) {
            this.showNotification('New passwords do not match', 'error');
            return false;
        }

        if (!this.validateNewPassword()) {
            this.showNotification('Password does not meet requirements', 'error');
            return false;
        }

        return true;
    }

    handleSuccess() {
        this.setLoadingState(false);
        this.showNotification('Password changed successfully!', 'success');
        
        setTimeout(() => {
            this.closeModal();
            this.resetForm();
        }, 2000);
    }

    handleError(message) {
        this.setLoadingState(false);
        this.showNotification(message, 'error');
        
        // Focus on current password if it's incorrect
        if (message.toLowerCase().includes('current password')) {
            const currentPasswordInput = document.querySelector('input[name="currentPassword"]');
            if (currentPasswordInput) {
                currentPasswordInput.focus();
                currentPasswordInput.select();
            }
        }
    }

    setLoadingState(isLoading) {
        const submitBtn = document.getElementById('changePasswordBtn');
        
        if (!submitBtn) return;

        if (isLoading) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Changing Password...';
        } else {
            submitBtn.innerHTML = '<i class="fas fa-key"></i> Change Password';
        }
    }

    resetForm() {
        const form = document.getElementById('changePasswordForm');
        if (form) {
            form.reset();
        }

        // Reset validation states
        document.querySelectorAll('.requirement-item').forEach(item => {
            item.classList.remove('valid', 'invalid');
            const icon = item.querySelector('.requirement-icon');
            if (icon) {
                icon.className = 'fas fa-times requirement-icon';
            }
        });

        // Reset strength indicator
        const strengthFill = document.getElementById('strengthFill');
        const strengthText = document.getElementById('strengthText');
        if (strengthFill) {
            strengthFill.className = 'strength-fill';
            strengthFill.style.width = '0%';
        }
        if (strengthText) {
            strengthText.textContent = 'Password strength';
        }

        // Hide elements
        const hideElements = ['passwordRequirements', 'passwordStrength', 'passwordMatch'];
        hideElements.forEach(id => {
            const element = document.getElementById(id);
            if (element) element.style.display = 'none';
        });

        // Reset submit button
        const submitBtn = document.getElementById('changePasswordBtn');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-key"></i> Change Password';
        }
    }

    closeModal() {
        const modal = document.getElementById('changePasswordModal');
        if (modal) {
            modal.classList.remove('active');
        }
    }

    showNotification(message, type = 'info') {
        // Remove existing notifications
        const existing = document.querySelectorAll('.notification');
        existing.forEach(notif => notif.remove());

        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        
        const icon = type === 'success' ? 'fa-check-circle' : 
                     type === 'error' ? 'fa-times-circle' : 'fa-info-circle';

        notification.innerHTML = `
            <i class="fas ${icon}"></i>
            <span>${message}</span>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.add('fade-out');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 4000);
    }

    addStyles() {
        if (document.getElementById('password-manager-styles')) return;

        const style = document.createElement('style');
        style.id = 'password-manager-styles';
        style.textContent = `
            .notification {
                position: fixed;
                top: 20px;
                right: 20px;
                background: #fff;
                color: #333;
                padding: 15px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 10000;
                display: flex;
                align-items: center;
                gap: 10px;
                max-width: 350px;
                animation: slideIn 0.3s ease;
            }
            
            .notification.success {
                background: linear-gradient(135deg, #10b981, #059669);
                color: white;
            }
            
            .notification.error {
                background: linear-gradient(135deg, #dc2626, #b91c1c);
                color: white;
            }
            
            .notification.fade-out {
                animation: slideOut 0.3s ease;
            }
            
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
            
            .requirement-item.valid {
                color: #10b981;
            }
            
            .requirement-item.invalid {
                color: #dc2626;
            }
            
            .strength-fill {
                height: 6px;
                border-radius: 3px;
                transition: all 0.3s ease;
                width: 0%;
            }
            
            .strength-fill.very-weak { background: #dc2626; }
            .strength-fill.weak { background: #ea580c; }
            .strength-fill.fair { background: #d97706; }
            .strength-fill.good { background: #059669; }
            .strength-fill.strong { background: #10b981; }
            
            .match-indicator.success {
                color: #10b981;
            }
            
            .match-indicator.error {
                color: #dc2626;
            }
        `;
        
        document.head.appendChild(style);
    }
}

// Global functions for modal usage
window.togglePassword = function(inputName) {
    // Try to use the instance method if available
    if (window.passwordManager && typeof window.passwordManager.togglePasswordVisibility === 'function') {
        window.passwordManager.togglePasswordVisibility(inputName);
        return;
    }
    
    // Fallback to direct implementation
    const input = document.querySelector(`input[name="${inputName}"]`);
    if (!input) {
        return;
    }
    
    const button = input.parentNode.querySelector('.password-toggle');
    if (!button) {
        return;
    }
    
    const icon = button.querySelector('i');
    if (!icon) {
        return;
    }
    
    const currentValue = input.value;
    const cursorPosition = input.selectionStart;
    
    if (input.type === 'password') {
        input.setAttribute('type', 'text');
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
        
        input.style.fontFamily = 'inherit';
        input.style.webkitTextSecurity = 'none';
        
    } else {
        input.setAttribute('type', 'password');
        input.type = 'password';
        icon.className = 'fas fa-eye';
        
        input.style.webkitTextSecurity = '';
    }
    
    input.value = currentValue;
    input.setSelectionRange(cursorPosition, cursorPosition);
    
    // Force a repaint
    input.style.display = 'none';
    input.offsetHeight; // trigger reflow
    input.style.display = '';
};

window.checkPasswordStrength = function(password) {
    if (window.passwordManager) {
        window.passwordManager.validateNewPassword();
    }
};

window.checkPasswordMatch = function() {
    if (window.passwordManager) {
        window.passwordManager.validatePasswordMatch();
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.passwordManager = new PasswordManager();
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PasswordManager;
}
