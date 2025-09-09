// Modal Components JavaScript
class ModalManager {
    constructor() {
        this.activeModal = null;
        this.init();
    }

    init() {
        this.setupModalHandlers();
        this.setupFormValidation();
        this.setupPasswordFeatures();
        this.setupProductSearch();
        this.setupFileUpload();
    }

    setupModalHandlers() {
        // Close modal when clicking outside
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal')) {
                this.closeModal(e.target.id);
            }
        });

        // Close modal with close button
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('close')) {
                const modal = e.target.closest('.modal');
                if (modal) {
                    this.closeModal(modal.id);
                }
            }
        });

        // Handle form submissions
        this.setupFormSubmissions();
    }

    setupFormSubmissions() {
        // Add Product Form
        const addProductForm = document.getElementById('addProductForm');
        if (addProductForm) {
            addProductForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleAddProduct(new FormData(addProductForm));
            });
        }

        // Add Stock Form
        const addStockForm = document.getElementById('addStockForm');
        if (addStockForm) {
            addStockForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleAddStock(new FormData(addStockForm));
            });
        }

        // Change Password Form
        const changePasswordForm = document.getElementById('changePasswordForm');
        if (changePasswordForm) {
            changePasswordForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleChangePassword(new FormData(changePasswordForm));
            });
        }
    }

    showModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('active');
            this.activeModal = modalId;
            document.body.style.overflow = 'hidden';
            
            // Focus first input
            const firstInput = modal.querySelector('input, select, textarea');
            if (firstInput) {
                setTimeout(() => firstInput.focus(), 100);
            }
        }
    }

    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('active');
            this.activeModal = null;
            document.body.style.overflow = '';
            
            // Reset form
            const form = modal.querySelector('form');
            if (form) {
                form.reset();
                this.clearValidationErrors(form);
            }
        }
    }

    async handleAddProduct(formData) {
        if (!this.validateAddProductForm(formData)) return;

        const submitBtn = document.querySelector('#addProductForm button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding Product...';
        submitBtn.disabled = true;

        try {
            // Simulate API call
            await this.simulateApiCall();
            
            this.showNotification('Product added successfully!', 'success');
            this.closeModal('addProductModal');
            
            // Refresh dashboard if available
            if (window.dashboardManager) {
                window.dashboardManager.refreshStats();
            }
            
        } catch (error) {
            this.showNotification('Error adding product. Please try again.', 'error');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }

    async handleAddStock(formData) {
        if (!this.validateAddStockForm(formData)) return;

        const submitBtn = document.querySelector('#addStockForm button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding Stock...';
        submitBtn.disabled = true;

        try {
            await this.simulateApiCall();
            
            this.showNotification('Stock updated successfully!', 'success');
            this.closeModal('addStockModal');
            
            // Refresh reports if available
            if (window.reportsManager) {
                window.reportsManager.loadData();
            }
            
        } catch (error) {
            this.showNotification('Error updating stock. Please try again.', 'error');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }

    async handleChangePassword(formData) {
        if (!this.validatePasswordForm(formData)) return;

        const submitBtn = document.querySelector('#changePasswordForm button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Changing Password...';
        submitBtn.disabled = true;

        try {
            await this.simulateApiCall();
            
            this.showNotification('Password changed successfully!', 'success');
            this.closeModal('changePasswordModal');
            
        } catch (error) {
            this.showNotification('Error changing password. Please try again.', 'error');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }

    validateAddProductForm(formData) {
        const requiredFields = ['productName', 'brand', 'category', 'price', 'stock'];
        return this.validateRequiredFields(requiredFields, formData);
    }

    validateAddStockForm(formData) {
        const requiredFields = ['selectedProductId', 'quantityToAdd'];
        return this.validateRequiredFields(requiredFields, formData);
    }

    validatePasswordForm(formData) {
        const currentPassword = formData.get('currentPassword');
        const newPassword = formData.get('newPassword');
        const confirmPassword = formData.get('confirmPassword');

        if (!currentPassword || !newPassword || !confirmPassword) {
            this.showNotification('Please fill in all password fields.', 'error');
            return false;
        }

        if (newPassword !== confirmPassword) {
            this.showNotification('New passwords do not match.', 'error');
            return false;
        }

        if (!this.isPasswordStrong(newPassword)) {
            this.showNotification('Password does not meet requirements.', 'error');
            return false;
        }

        return true;
    }

    validateRequiredFields(fields, formData) {
        for (const field of fields) {
            if (!formData.get(field)) {
                this.showNotification(`Please fill in the ${field.replace(/([A-Z])/g, ' $1').toLowerCase()} field.`, 'error');
                return false;
            }
        }
        return true;
    }

    setupFormValidation() {
        // Real-time validation for required fields
        document.addEventListener('input', (e) => {
            if (e.target.hasAttribute('required')) {
                this.validateField(e.target);
            }
        });
    }

    validateField(field) {
        if (field.value.trim()) {
            field.style.borderColor = '#10b981';
        } else {
            field.style.borderColor = '#ef4444';
        }
    }

    clearValidationErrors(form) {
        const fields = form.querySelectorAll('input, select, textarea');
        fields.forEach(field => {
            field.style.borderColor = '#e5e7eb';
        });
    }

    setupPasswordFeatures() {
        // Password strength checker
        const newPasswordInput = document.querySelector('input[name="newPassword"]');
        if (newPasswordInput) {
            newPasswordInput.addEventListener('input', (e) => {
                this.checkPasswordStrength(e.target.value);
            });
        }

        // Password match checker
        const confirmPasswordInput = document.querySelector('input[name="confirmPassword"]');
        if (confirmPasswordInput) {
            confirmPasswordInput.addEventListener('input', () => {
                this.checkPasswordMatch();
            });
        }

        // Password toggle buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('.password-toggle')) {
                const button = e.target.closest('.password-toggle');
                const input = button.parentElement.querySelector('input');
                this.togglePasswordVisibility(input, button);
            }
        });
    }

    checkPasswordStrength(password) {
        const strengthIndicator = document.getElementById('passwordStrength');
        const strengthFill = document.getElementById('strengthFill');
        const strengthText = document.getElementById('strengthText');
        const requirements = document.getElementById('passwordRequirements');

        if (!strengthIndicator || !password) {
            if (strengthIndicator) strengthIndicator.style.display = 'none';
            return;
        }

        strengthIndicator.style.display = 'block';

        const strength = this.calculatePasswordStrength(password);
        const percentage = (strength.score / 5) * 100;

        strengthFill.style.width = `${percentage}%`;
        strengthFill.style.background = this.getStrengthColor(strength.score);
        strengthText.textContent = this.getStrengthText(strength.score);
        strengthText.style.color = this.getStrengthColor(strength.score);

        // Update requirements
        this.updatePasswordRequirements(password);

        // Enable/disable submit button
        const submitBtn = document.getElementById('changePasswordBtn');
        if (submitBtn) {
            submitBtn.disabled = strength.score < 4;
        }
    }

    calculatePasswordStrength(password) {
        let score = 0;
        const checks = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[^A-Za-z0-9]/.test(password)
        };

        Object.values(checks).forEach(check => {
            if (check) score++;
        });

        return { score, checks };
    }

    getStrengthColor(score) {
        const colors = ['#ef4444', '#f59e0b', '#eab308', '#84cc16', '#10b981'];
        return colors[score - 1] || '#ef4444';
    }

    getStrengthText(score) {
        const texts = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
        return texts[score - 1] || 'Very Weak';
    }

    updatePasswordRequirements(password) {
        const requirements = {
            'req-length': password.length >= 8,
            'req-uppercase': /[A-Z]/.test(password),
            'req-lowercase': /[a-z]/.test(password),
            'req-number': /[0-9]/.test(password),
            'req-special': /[^A-Za-z0-9]/.test(password)
        };

        Object.entries(requirements).forEach(([id, met]) => {
            const element = document.getElementById(id);
            if (element) {
                const icon = element.querySelector('.requirement-icon');
                if (met) {
                    element.classList.add('valid');
                    icon.className = 'fas fa-check requirement-icon';
                } else {
                    element.classList.remove('valid');
                    icon.className = 'fas fa-times requirement-icon';
                }
            }
        });
    }

    checkPasswordMatch() {
        const newPassword = document.querySelector('input[name="newPassword"]').value;
        const confirmPassword = document.querySelector('input[name="confirmPassword"]').value;
        const matchIndicator = document.getElementById('passwordMatch');
        const matchElement = document.getElementById('matchIndicator');

        if (!confirmPassword) {
            matchIndicator.style.display = 'none';
            return;
        }

        matchIndicator.style.display = 'block';

        if (newPassword === confirmPassword) {
            matchElement.textContent = '✓ Passwords match';
            matchElement.className = 'match-indicator match';
        } else {
            matchElement.textContent = '✗ Passwords do not match';
            matchElement.className = 'match-indicator no-match';
        }
    }

    isPasswordStrong(password) {
        const strength = this.calculatePasswordStrength(password);
        return strength.score >= 4;
    }

    togglePasswordVisibility(input, button) {
        const icon = button.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'fas fa-eye';
        }
    }

    setupProductSearch() {
        const searchInput = document.querySelector('input[name="productSearch"]');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.searchProducts(e.target.value);
                }, 300);
            });
        }
    }

    async searchProducts(query) {
        const dropdown = document.getElementById('productSearchDropdown');
        if (!dropdown) return;

        if (!query.trim()) {
            dropdown.style.display = 'none';
            return;
        }

        try {
            // Simulate product search
            const mockProducts = [
                { id: 1, name: 'Advanced Night Repair', brand: 'Estée Lauder', stock: 15, price: 59.99 },
                { id: 2, name: 'Revitalift Moisturizer', brand: 'L\'Oréal', stock: 8, price: 24.99 },
                { id: 3, name: 'Fit Me Foundation', brand: 'Maybelline', stock: 23, price: 7.99 }
            ];

            const filtered = mockProducts.filter(product => 
                product.name.toLowerCase().includes(query.toLowerCase()) ||
                product.brand.toLowerCase().includes(query.toLowerCase())
            );

            this.displaySearchResults(filtered);

        } catch (error) {
            console.error('Error searching products:', error);
        }
    }

    displaySearchResults(products) {
        const dropdown = document.getElementById('productSearchDropdown');
        dropdown.innerHTML = '';

        if (products.length === 0) {
            dropdown.innerHTML = '<div class="search-dropdown-item">No products found</div>';
        } else {
            products.forEach(product => {
                const item = document.createElement('div');
                item.className = 'search-dropdown-item';
                item.innerHTML = `
                    <strong>${product.name}</strong><br>
                    <small>${product.brand} - $${product.price} (${product.stock} in stock)</small>
                `;
                item.addEventListener('click', () => {
                    this.selectProduct(product);
                });
                dropdown.appendChild(item);
            });
        }

        dropdown.style.display = 'block';
    }

    selectProduct(product) {
        document.querySelector('input[name="productSearch"]').value = product.name;
        document.getElementById('selectedProductId').value = product.id;
        document.getElementById('productSearchDropdown').style.display = 'none';

        // Show product info
        this.displaySelectedProduct(product);
    }

    displaySelectedProduct(product) {
        const display = document.getElementById('productInfoDisplay');
        if (display) {
            document.getElementById('displayProductName').textContent = product.name;
            document.getElementById('displayBrand').textContent = product.brand;
            document.getElementById('displayCurrentStock').textContent = product.stock;
            document.getElementById('displayPrice').textContent = `$${product.price}`;
            display.style.display = 'block';
        }
    }

    setupFileUpload() {
        const fileInputs = document.querySelectorAll('.file-input');
        fileInputs.forEach(input => {
            input.addEventListener('change', (e) => {
                this.handleFileUpload(e);
            });
        });
    }

    handleFileUpload(event) {
        const file = event.target.files[0];
        const previewId = event.target.id + 'Preview';
        const preview = document.getElementById(previewId);

        if (file && preview) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = preview.querySelector('img');
                    if (img) {
                        img.src = e.target.result;
                        preview.style.display = 'block';
                    }
                };
                reader.readAsDataURL(file);
            }
        }
    }

    removeImage(inputId, previewId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        
        if (input) input.value = '';
        if (preview) preview.style.display = 'none';
    }

    simulateApiCall() {
        return new Promise((resolve) => {
            setTimeout(resolve, 1500);
        });
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `modal-notification ${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas ${this.getNotificationIcon(type)}"></i>
                <span>${message}</span>
            </div>
        `;

        const colors = {
            success: 'linear-gradient(135deg, #10b981, #059669)',
            error: 'linear-gradient(135deg, #ef4444, #dc2626)',
            info: 'linear-gradient(135deg, #3b82f6, #1d4ed8)'
        };

        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${colors[type] || colors.info};
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            z-index: 10001;
            animation: slideIn 0.3s ease;
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    getNotificationIcon(type) {
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            info: 'fa-info-circle'
        };
        return icons[type] || icons.info;
    }
}

// Global functions for backward compatibility
window.showModal = function(modalId) {
    if (window.modalManager) {
        window.modalManager.showModal(modalId);
    }
};

window.closeModal = function(modalId) {
    if (window.modalManager) {
        window.modalManager.closeModal(modalId);
    }
};

window.togglePassword = function(inputName) {
    const input = document.querySelector(`input[name="${inputName}"]`);
    const button = input.parentElement.querySelector('.password-toggle');
    if (window.modalManager) {
        window.modalManager.togglePasswordVisibility(input, button);
    }
};

window.checkPasswordStrength = function(password) {
    if (window.modalManager) {
        window.modalManager.checkPasswordStrength(password);
    }
};

window.checkPasswordMatch = function() {
    if (window.modalManager) {
        window.modalManager.checkPasswordMatch();
    }
};

window.searchProducts = function(query) {
    if (window.modalManager) {
        window.modalManager.searchProducts(query);
    }
};

window.removeImage = function(inputId, previewId) {
    if (window.modalManager) {
        window.modalManager.removeImage(inputId, previewId);
    }
};

// Initialize modal manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.modalManager = new ModalManager();
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ModalManager;
}
