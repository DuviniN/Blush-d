/**
 * Password Management JavaScript Module
 * Handles all password-related functionality including validation, API calls, and UI updates
 */

class PasswordManager {
    constructor() {
        this.apiEndpoint = '../../../server/PasswordController.php';
        this.passwordRequirements = {
            'length-req': { regex: /.{8,}/, message: 'At least 8 characters long' },
            'uppercase-req': { regex: /[A-Z]/, message: 'One uppercase letter (A-Z)' },
            'lowercase-req': { regex: /[a-z]/, message: 'One lowercase letter (a-z)' },
            'number-req': { regex: /\d/, message: 'One number (0-9)' },
            'special-req': { regex: /[!@#$%^&*(),.?":{}|<>]/, message: 'One special character (!@#$%^&*)' }
        };
        this.init();
    }

    init() {
        this.bindEvents();
        this.addAnimationStyles();
    }

    bindEvents() {
        // Bind password validation events
        const newPasswordInput = document.getElementById('newPassword');
        const confirmPasswordInput = document.getElementById('confirmPassword');
        const currentPasswordInput = document.getElementById('currentPassword');

        if (newPasswordInput) {
            newPasswordInput.addEventListener('keyup', () => this.validatePassword());
            newPasswordInput.addEventListener('blur', () => this.validatePassword());
        }

        if (confirmPasswordInput) {
            confirmPasswordInput.addEventListener('keyup', () => this.validatePasswordMatch());
            confirmPasswordInput.addEventListener('blur', () => this.validatePasswordMatch());
        }

        if (currentPasswordInput) {
            currentPasswordInput.addEventListener('input', () => this.clearCurrentPasswordError());
        }

        // Bind form submission
        const passwordForm = document.getElementById('changePasswordForm');
        if (passwordForm) {
            passwordForm.addEventListener('submit', (e) => this.handlePasswordChange(e));
        }
    }

    /**
     * Toggle password visibility for input fields
     */
    togglePasswordVisibility(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector('i');
        
        if (!input || !icon) return;
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fas fa-eye-slash';
            button.setAttribute('aria-label', 'Hide password');
        } else {
            input.type = 'password';
            icon.className = 'fas fa-eye';
            button.setAttribute('aria-label', 'Show password');
        }
    }

    /**
     * Validate password strength and requirements
     */
    validatePassword() {
        const password = document.getElementById('newPassword')?.value || '';
        let validCount = 0;

        // Check each requirement
        Object.keys(this.passwordRequirements).forEach(reqId => {
            const element = document.getElementById(reqId);
            const requirement = this.passwordRequirements[reqId];
            
            if (element && requirement.regex.test(password)) {
                element.classList.add('valid');
                validCount++;
            } else if (element) {
                element.classList.remove('valid');
            }
        });

        // Update password strength indicator
        this.updatePasswordStrength(password, validCount);
        
        // Validate password match if confirm password has value
        this.validatePasswordMatch();
        
        return validCount === Object.keys(this.passwordRequirements).length;
    }

    /**
     * Update password strength indicator
     */
    updatePasswordStrength(password, validCount) {
        const strengthFill = document.getElementById('strengthFill');
        const strengthText = document.getElementById('strengthText');
        
        if (!strengthFill || !strengthText) return;

        let strength = '';
        let className = '';
        let color = '#666';

        if (password.length === 0) {
            strength = 'Password strength';
            className = '';
        } else if (validCount < 3) {
            strength = 'Weak';
            className = 'weak';
            color = '#dc2626';
        } else if (validCount < 4) {
            strength = 'Fair';
            className = 'fair';
            color = '#ea580c';
        } else if (validCount < 5) {
            strength = 'Good';
            className = 'good';
            color = '#ca8a04';
        } else {
            strength = 'Strong';
            className = 'strong';
            color = '#059669';
        }

        strengthFill.className = `strength-fill ${className}`;
        strengthText.textContent = strength;
        strengthText.style.color = color;
    }

    /**
     * Validate password confirmation match
     */
    validatePasswordMatch() {
        const newPassword = document.getElementById('newPassword')?.value || '';
        const confirmPassword = document.getElementById('confirmPassword')?.value || '';
        const feedback = document.getElementById('confirmPasswordFeedback');
        const submitBtn = document.getElementById('submitPasswordBtn');
        
        if (!feedback || !submitBtn) return false;

        if (confirmPassword.length === 0) {
            feedback.textContent = '';
            feedback.className = 'password-feedback';
            submitBtn.disabled = true;
            return false;
        }

        if (newPassword === confirmPassword) {
            feedback.innerHTML = '<i class="fas fa-check-circle"></i> Passwords match';
            feedback.className = 'password-feedback success';
            
            // Check if all requirements are met
            const isPasswordValid = this.validatePassword();
            const currentPassword = document.getElementById('currentPassword')?.value || '';
            
            submitBtn.disabled = !(isPasswordValid && currentPassword.length > 0);
            return true;
        } else {
            feedback.innerHTML = '<i class="fas fa-times-circle"></i> Passwords do not match';
            feedback.className = 'password-feedback error';
            submitBtn.disabled = true;
            return false;
        }
    }

    /**
     * Clear current password error feedback
     */
    clearCurrentPasswordError() {
        const feedback = document.getElementById('currentPasswordFeedback');
        if (feedback) {
            feedback.textContent = '';
            feedback.className = 'password-feedback';
        }
    }

    /**
     * Handle password change form submission
     */
    async handlePasswordChange(event) {
        event.preventDefault();
        
        const currentPassword = document.getElementById('currentPassword')?.value || '';
        const newPassword = document.getElementById('newPassword')?.value || '';
        const confirmPassword = document.getElementById('confirmPassword')?.value || '';

        // Client-side validation
        if (!this.validateInputs(currentPassword, newPassword, confirmPassword)) {
            return;
        }

        // Show loading state
        this.setLoadingState(true);

        try {
            // Send password change request
            const result = await this.changePasswordAPI(currentPassword, newPassword);
            
            if (result.success) {
                this.handlePasswordChangeSuccess();
            } else {
                this.handlePasswordChangeError(result.message);
            }
        } catch (error) {
            console.error('Password change error:', error);
            this.handlePasswordChangeError('Network error occurred. Please try again.');
        }
    }

    /**
     * Validate form inputs before submission
     */
    validateInputs(currentPassword, newPassword, confirmPassword) {
        if (!currentPassword || !newPassword || !confirmPassword) {
            this.showNotification('Please fill in all fields', 'error');
            return false;
        }

        if (newPassword !== confirmPassword) {
            this.showNotification('New passwords do not match', 'error');
            return false;
        }

        if (!this.validatePassword()) {
            this.showNotification('Password does not meet requirements', 'error');
            return false;
        }

        return true;
    }

    /**
     * API call to change password
     */
    async changePasswordAPI(currentPassword, newPassword) {
        const requestData = {
            action: 'change_password',
            currentPassword: currentPassword,
            newPassword: newPassword
        };

        const response = await fetch(this.apiEndpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(requestData)
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return await response.json();
    }

    /**
     * API call to get password history
     */
    async getPasswordHistoryAPI(limit = 5) {
        const requestData = {
            action: 'password_history',
            limit: limit
        };

        try {
            const response = await fetch(this.apiEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(requestData)
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return await response.json();
        } catch (error) {
            console.error('Password history error:', error);
            return { success: false, message: 'Failed to retrieve password history' };
        }
    }

    /**
     * API call to check password expiry
     */
    async checkPasswordExpiryAPI() {
        const requestData = {
            action: 'check_expiry'
        };

        try {
            const response = await fetch(this.apiEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(requestData)
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return await response.json();
        } catch (error) {
            console.error('Password expiry check error:', error);
            return { success: false, message: 'Failed to check password expiry' };
        }
    }

    /**
     * Handle successful password change
     */
    handlePasswordChangeSuccess() {
        this.setLoadingState(false);
        this.showNotification('Password changed successfully!', 'success');
        
        // Add success animation to modal
        const modal = document.getElementById('changePasswordModal');
        if (modal) {
            modal.classList.add('success-animation');
        }

        // Close modal and reset form after delay
        setTimeout(() => {
            this.closeModal('changePasswordModal');
            this.resetPasswordForm();
        }, 1500);
    }

    /**
     * Handle password change error
     */
    handlePasswordChangeError(message) {
        this.setLoadingState(false);
        this.showNotification(message, 'error');

        // Handle specific error types
        if (message.toLowerCase().includes('current password')) {
            const currentPasswordInput = document.getElementById('currentPassword');
            const feedback = document.getElementById('currentPasswordFeedback');
            
            if (currentPasswordInput && feedback) {
                currentPasswordInput.value = '';
                currentPasswordInput.focus();
                feedback.innerHTML = `<i class="fas fa-times-circle"></i> ${message}`;
                feedback.className = 'password-feedback error';
            }
        }
    }

    /**
     * Set loading state for password change button
     */
    setLoadingState(isLoading) {
        const submitBtn = document.getElementById('submitPasswordBtn');
        const btnText = submitBtn?.querySelector('.btn-text');
        const btnLoading = submitBtn?.querySelector('.btn-loading');

        if (!submitBtn || !btnText || !btnLoading) return;

        if (isLoading) {
            btnText.style.display = 'none';
            btnLoading.style.display = 'flex';
            submitBtn.disabled = true;
        } else {
            btnText.style.display = 'inline-flex';
            btnLoading.style.display = 'none';
            submitBtn.disabled = false;
        }
    }

    /**
     * Reset password form to initial state
     */
    resetPasswordForm() {
        // Clear all inputs
        const inputs = ['currentPassword', 'newPassword', 'confirmPassword'];
        inputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            if (input) input.value = '';
        });

        // Reset validation states
        document.querySelectorAll('.requirement').forEach(req => {
            req.classList.remove('valid');
        });

        // Reset strength indicator
        const strengthFill = document.getElementById('strengthFill');
        const strengthText = document.getElementById('strengthText');
        if (strengthFill) strengthFill.className = 'strength-fill';
        if (strengthText) {
            strengthText.textContent = 'Password strength';
            strengthText.style.color = '#666';
        }

        // Clear all feedback
        const feedbacks = ['currentPasswordFeedback', 'confirmPasswordFeedback'];
        feedbacks.forEach(feedbackId => {
            const feedback = document.getElementById(feedbackId);
            if (feedback) {
                feedback.textContent = '';
                feedback.className = 'password-feedback';
            }
        });

        // Disable submit button
        const submitBtn = document.getElementById('submitPasswordBtn');
        if (submitBtn) submitBtn.disabled = true;
    }

    /**
     * Close modal
     */
    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('active', 'success-animation');
        }
    }

    /**
     * Show notification
     */
    showNotification(message, type = 'info') {
        // Remove any existing notifications
        const existingNotifications = document.querySelectorAll('.notification');
        existingNotifications.forEach(notif => notif.remove());

        const notification = document.createElement('div');
        notification.className = `notification ${type}`;

        const icon = type === 'success' ? 'fa-check-circle' : 
                     type === 'error' ? 'fa-times-circle' : 
                     'fa-info-circle';

        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas ${icon}"></i>
                <span>${message}</span>
            </div>
        `;

        const backgroundColor = type === 'success' ? 'linear-gradient(135deg, #10b981, #059669)' : 
                               type === 'error' ? 'linear-gradient(135deg, #dc2626, #b91c1c)' :
                               'linear-gradient(135deg, #3b82f6, #1d4ed8)';

        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${backgroundColor};
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            z-index: 10000;
            animation: slideIn 0.3s ease;
            max-width: 350px;
            font-weight: 500;
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 4000);
    }

    /**
     * Add animation styles to document head
     */
    addAnimationStyles() {
        if (document.getElementById('password-animations')) return;

        const style = document.createElement('style');
        style.id = 'password-animations';
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
            @keyframes successPulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.02); }
                100% { transform: scale(1); }
            }
            .notification-content {
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .success-animation {
                animation: successPulse 0.6s ease-in-out;
            }
        `;
        document.head.appendChild(style);
    }

    /**
     * Initialize password expiry check on page load
     */
    async checkPasswordExpiry() {
        try {
            const result = await this.checkPasswordExpiryAPI();
            if (result.success && result.data) {
                const data = result.data;
                
                if (data.is_expired) {
                    this.showNotification('Your password has expired. Please change it now.', 'error');
                } else if (data.should_warn) {
                    this.showNotification(`Your password will expire in ${data.days_until_expiry} days.`, 'warning');
                }
            }
        } catch (error) {
            console.error('Password expiry check failed:', error);
        }
    }
}

// Global functions for backward compatibility and direct HTML usage
window.togglePasswordVisibility = function(inputId, button) {
    if (window.passwordManager) {
        window.passwordManager.togglePasswordVisibility(inputId, button);
    }
};

window.validatePassword = function() {
    if (window.passwordManager) {
        return window.passwordManager.validatePassword();
    }
    return false;
};

window.validatePasswordMatch = function() {
    if (window.passwordManager) {
        return window.passwordManager.validatePasswordMatch();
    }
    return false;
};

window.handlePasswordChange = function(event) {
    if (window.passwordManager) {
        window.passwordManager.handlePasswordChange(event);
    }
};

// Initialize password manager when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.passwordManager = new PasswordManager();
    
    // Check password expiry on page load
    window.passwordManager.checkPasswordExpiry();
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PasswordManager;
}
