// Dashboard Section Component JavaScript
class DashboardManager {
    constructor() {
        this.stats = {
            totalProducts: 0,
            lowStock: 0,
            totalOrders: 0,
            revenue: '$0'
        };
        this.init();
    }

    init() {
        this.fetchDashboardStats();
        this.renderPopularProducts();
        this.setupQuickActions();
    }

    async fetchDashboardStats() {
        try {
            // Fetch total products
            const totalProductsResponse = await fetch('../../../server/ReportController.php?action=total_products');
            const totalProductsData = await totalProductsResponse.json();
            if (totalProductsData.success) {
                this.updateStatCard('total-products', totalProductsData.data.total || 0);
            }

            // Fetch low stock items
            const lowStockResponse = await fetch('../../../server/ReportController.php?action=low_stock');
            const lowStockData = await lowStockResponse.json();
            if (lowStockData.success) {
                this.updateStatCard('low-stock', lowStockData.data.count || 0);
            }

            // Fetch total orders
            const totalOrdersResponse = await fetch('../../../server/ReportController.php?action=total_orders');
            const totalOrdersData = await totalOrdersResponse.json();
            if (totalOrdersData.success) {
                this.updateStatCard('total-orders', totalOrdersData.data.total || 0);
            }

            // Set revenue (mock data for now)
            this.updateStatCard('revenue', '$197');

        } catch (error) {
            console.error('Error fetching dashboard stats:', error);
            this.setDefaultStats();
        }
    }

    updateStatCard(type, value) {
        const cardSelectors = {
            'total-products': '.card:nth-child(1) .stats-number',
            'low-stock': '.card:nth-child(2) .stats-number',
            'total-orders': '.card:nth-child(3) .stats-number',
            'revenue': '.card:nth-child(4) .stats-number'
        };

        const selector = cardSelectors[type];
        if (selector) {
            const element = document.querySelector(selector);
            if (element) {
                element.textContent = value;
                
                // Add animation
                element.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    element.style.transform = 'scale(1)';
                }, 200);
            }
        }
    }

    setDefaultStats() {
        this.updateStatCard('total-products', '0');
        this.updateStatCard('low-stock', '2');
        this.updateStatCard('total-orders', '4');
        this.updateStatCard('revenue', '$197');
    }

    async renderPopularProducts() {
        try {
            const response = await fetch('../../../server/ReportController.php?action=popular_products');
            const data = await response.json();
            
            if (data.success && data.data.length > 0) {
                this.displayPopularProducts(data.data);
            } else {
                this.displayPlaceholderProducts();
            }
        } catch (error) {
            console.error('Error fetching popular products:', error);
            this.displayPlaceholderProducts();
        }
    }

    displayPopularProducts(products) {
        const container = document.querySelector('.popular-products');
        if (!container) return;

        container.innerHTML = '';
        
        products.slice(0, 3).forEach(product => {
            const productElement = this.createProductElement(product);
            container.appendChild(productElement);
        });
    }

    displayPlaceholderProducts() {
        const container = document.querySelector('.popular-products');
        if (!container) return;

        const placeholderProducts = [
            {
                product_name: 'Advanced Night Repair',
                brand: 'Estée Lauder',
                price: 59.99,
                stock: 15,
                image: '../../../assets/pictures/Estee_Lauder/images.jpeg'
            },
            {
                product_name: 'Revitalift Moisturizer',
                brand: 'L\'Oréal',
                price: 24.99,
                stock: 8,
                image: '../../../assets/pictures/L\'Oreal/images (1).jpeg'
            },
            {
                product_name: 'Fit Me Foundation',
                brand: 'Maybelline',
                price: 7.99,
                stock: 23,
                image: '../../../assets/pictures/Maybelline/images.jpeg'
            }
        ];

        container.innerHTML = '';
        placeholderProducts.forEach(product => {
            const productElement = this.createProductElement(product);
            container.appendChild(productElement);
        });
    }

    createProductElement(product) {
        const productDiv = document.createElement('div');
        productDiv.className = 'product-item';
        
        productDiv.innerHTML = `
            <div class="product-image">
                <img src="${product.image || '../../../assets/pictures/default.png'}" 
                     alt="${product.product_name}" 
                     onerror="this.src='../../../assets/pictures/default.png'">
            </div>
            <div class="product-info">
                <h4>${product.product_name}</h4>
                <p class="product-brand">${product.brand}</p>
                <span class="product-price">$${parseFloat(product.price).toFixed(2)}</span>
            </div>
            <div class="product-stats">
                <span class="stock-count">${product.stock} left</span>
            </div>
        `;

        return productDiv;
    }

    setupQuickActions() {
        // Add Product button
        const addProductBtn = document.querySelector('.btn-primary');
        if (addProductBtn) {
            addProductBtn.addEventListener('click', (e) => {
                e.preventDefault();
                window.showModal('addProductModal');
            });
        }

        // Update Stock button
        const updateStockBtn = document.querySelector('.btn-outline:first-of-type');
        if (updateStockBtn) {
            updateStockBtn.addEventListener('click', (e) => {
                e.preventDefault();
                window.showModal('addStockModal');
            });
        }

        // View Reports button
        const viewReportsBtn = document.querySelector('.btn-outline:last-of-type');
        if (viewReportsBtn) {
            viewReportsBtn.addEventListener('click', (e) => {
                e.preventDefault();
                window.showSection('reports');
            });
        }
    }

    refreshStats() {
        this.fetchDashboardStats();
        this.renderPopularProducts();
    }

    showSuccessMessage(message) {
        // Create and show success notification
        const notification = document.createElement('div');
        notification.className = 'dashboard-notification success';
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-check-circle"></i>
                <span>${message}</span>
            </div>
        `;

        // Add styles
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            z-index: 10000;
            animation: slideIn 0.3s ease;
        `;

        document.body.appendChild(notification);

        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('dashboard-section')) {
        window.dashboardManager = new DashboardManager();
    }
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = DashboardManager;
}
