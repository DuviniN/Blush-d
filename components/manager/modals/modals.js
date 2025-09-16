// Modal Components JavaScript
class ModalManager {
    constructor() {
        this.activeModal = null;
        this.isSubmitting = false; // Flag to prevent duplicate submissions
        this.init();
    }

    init() {
        this.setupModalHandlers();
        this.setupFormValidation();
        this.setupPasswordFeatures();
        this.setupProductSearch();
        this.setupFileUpload();
        this.loadCategories();
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
            
            // Load categories when opening add product modal
            if (modalId === 'addProductModal') {
                this.loadCategories();
            }
            
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

    async loadCategories() {
        const categorySelect = document.getElementById('productCategorySelect');
        if (!categorySelect) return;

        try {
            const response = await fetch('../../../server/api.php?endpoint=products&action=categories');
            const result = await response.json();
            if (result.success && result.data) {
                // Clear existing options except the first one
                categorySelect.innerHTML = '<option value="">Select Category</option>';
                
                // Add categories
                result.data.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.category_id;
                    option.textContent = category.name;
                    categorySelect.appendChild(option);
                });

            } else {
                this.showNotification('Failed to load categories', 'warning');
            }
        } catch (error) {
            console.error('Error loading categories:', error);
            this.showNotification('Error loading categories', 'error');
        }
    }

    async handleAddProduct(formData) {
        if (!this.validateAddProductForm(formData)) return;
        
        // Prevent duplicate submissions
        if (this.isSubmitting) {
            console.log('Already submitting, ignoring duplicate request');
            return;
        }
        
        this.isSubmitting = true;

        const submitBtn = document.querySelector('button[form="addProductForm"][type="submit"]');
        if (!submitBtn) {
            this.showNotification('Submit button not found', 'error');
            this.isSubmitting = false;
            return;
        }
        
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding Product...';
        submitBtn.disabled = true;

        try {
            // Send FormData directly (supports file uploads)
            const response = await fetch('../../../server/api.php?endpoint=products&action=add_product', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            if (result.success) {
                this.showNotification('Product added successfully!', 'success');
                this.closeModal('addProductModal');

                //Reset form and clear image preview
                document.getElementById('addProductForm').reset();
                this.clearValidationErrors(document.getElementById('addProductForm'));

                const imagePreview = document.getElementById('productImagePreview');
                if(imagePreview){
                    imagePreview.style.display = 'none';
                }
                
                // Refresh dashboard if available
                if (window.dashboardManager) {
                    window.dashboardManager.refreshStats();
                }
                
                // Refresh products list if available
                if (window.reportsManager) {
                    window.reportsManager.fetchInventoryReport();
                }
            } else {
                this.showNotification(result.message || 'Failed to add product', 'error');
            }
            
        } catch (error) {
            console.error('Error adding product:', error);
            this.showNotification('Error adding product. Please try again.', 'error');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            this.isSubmitting = false; // Reset the flag
        }
    }

    async handleAddStock(formData) {
        if (!this.validateAddStockForm(formData)) return;

        const submitBtn = document.querySelector('button[form="addStockForm"][type="submit"]');
        if (!submitBtn) {
            this.showNotification('Submit button not found', 'error');
            return;
        }
        
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding Stock...';
        submitBtn.disabled = true;

        try {
            // Prepare data for backend
            const stockData = {
                product_id: formData.get('selectedProductId'),
                quantity: parseInt(formData.get('quantityToAdd'))
            };

            // Send request to backend
            const response = await fetch('../../../server/api.php?endpoint=products&action=update_stock', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(stockData)
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification('Stock updated successfully!', 'success');
                this.closeModal('addStockModal');
                
                // Refresh reports if available
                if (window.reportsManager) {
                    window.reportsManager.loadData();
                }
            } else {
                this.showNotification(result.message || 'Error updating stock', 'error');
            }
            
        } catch (error) {
            console.error('Error updating stock:', error);
            this.showNotification('Error updating stock. Please try again.', 'error');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }

    async handleChangePassword(formData) {
        if (!this.validatePasswordForm(formData)) return;

        const submitBtn = document.querySelector('button[form="changePasswordForm"][type="submit"]');
        if (!submitBtn) {
            this.showNotification('Submit button not found', 'error');
            return;
        }
        
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
        const requiredFields = ['product_name', 'category_id', 'price', 'stock'];
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
        console.log('Validating fields:', fields, formData);
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
        // If no search input exists (like in simplified add stock modal), skip setup
    }

    async searchProducts(query) {
        const dropdown = document.getElementById('productSearchDropdown');
        if (!dropdown) return;

        if (!query.trim()) {
            dropdown.style.display = 'none';
            return;
        }

        try {
            // Fetch real products from backend
            const response = await fetch('../../../server/ManagerController.php?action=products');
            const result = await response.json();
            
            if (result.success && result.data) {
                // Filter products based on query
                const filtered = result.data.filter(product => 
                    product.product_name.toLowerCase().includes(query.toLowerCase()) ||
                    (product.brand && product.brand.toLowerCase().includes(query.toLowerCase()))
                );

                this.displaySearchResults(filtered);
            } else {
                dropdown.innerHTML = '<div class="search-dropdown-item">No products found</div>';
                dropdown.style.display = 'block';
            }

        } catch (error) {
            console.error('Error searching products:', error);
            dropdown.innerHTML = '<div class="search-dropdown-item">Error loading products</div>';
            dropdown.style.display = 'block';
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
                    <strong>${product.product_name}</strong><br>
                    <small>${product.brand || 'No brand'} - $${product.price} (${product.stock} in stock)</small>
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
        document.querySelector('input[name="productSearch"]').value = product.product_name;
        document.getElementById('selectedProductId').value = product.product_id;
        document.getElementById('productSearchDropdown').style.display = 'none';

        // Show product info
        this.displaySelectedProduct(product);
    }

    displaySelectedProduct(product) {
        const display = document.getElementById('productInfoDisplay');
        if (display) {
            document.getElementById('displayProductName').textContent = product.product_name;
            document.getElementById('displayBrand').textContent = product.brand || 'No brand';
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
        const input = event.target;
        const previewId = input.id.replace('Input', 'Preview');
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
        if (preview) {
            preview.style.display = 'none';
            const img = preview.querySelector('img');
            if (img) img.src = '';
        }
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
    if (window.modalManager || window.Components?.modals) {
        const manager = window.modalManager || window.Components.modals;
        manager.showModal(modalId);
    }
};

window.closeModal = function(modalId) {
    if (window.modalManager || window.Components?.modals) {
        const manager = window.modalManager || window.Components.modals;
        manager.closeModal(modalId);
    }
};

window.togglePassword = function(inputName) {
    const input = document.querySelector(`input[name="${inputName}"]`);
    const button = input.parentElement.querySelector('.password-toggle');
    if (window.modalManager || window.Components?.modals) {
        const manager = window.modalManager || window.Components.modals;
        manager.togglePasswordVisibility(input, button);
    }
};

window.checkPasswordStrength = function(password) {
    if (window.modalManager || window.Components?.modals) {
        const manager = window.modalManager || window.Components.modals;
        manager.checkPasswordStrength(password);
    }
};

window.checkPasswordMatch = function() {
    if (window.modalManager || window.Components?.modals) {
        const manager = window.modalManager || window.Components.modals;
        manager.checkPasswordMatch();
    }
};

window.searchProducts = function(query) {
    if (window.modalManager || window.Components?.modals) {
        const manager = window.modalManager || window.Components.modals;
        manager.searchProducts(query);
    }
};

window.removeImage = function(inputId, previewId) {
    if (window.modalManager || window.Components?.modals) {
        const manager = window.modalManager || window.Components.modals;
        manager.removeImage(inputId, previewId);
    }
};

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ModalManager;
}
