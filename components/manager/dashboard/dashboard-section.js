// Dashboard Section Component JavaScript
class DashboardManager {
    constructor() {
        this.stats = {
            totalProducts: 0,
            lowStock: 0,
            totalOrders: 0,
            revenue: '$0'
        };
        this.charts = {};
        this.init();
    }

    init() {
        // Chart.js should already be loaded by the main page
        if (window.Chart) {
            this.fetchDashboardStats();
            this.renderPopularProducts();
            this.setupQuickActions();
            this.initializeCharts();
        } else {
            // Fallback: load Chart.js if not available
            this.loadChartLibrary().then(() => {
                this.fetchDashboardStats();
                this.renderPopularProducts();
                this.setupQuickActions();
                this.initializeCharts();
            }).catch((error) => {
                console.error('Failed to load Chart.js library:', error);
                this.showChartLibraryError();
            });
        }
    }

    showChartLibraryError() {
        const chartContainers = ['categoryPieChart', 'monthlySalesChart', 'stockOverviewChart'];
        chartContainers.forEach(containerId => {
            const canvas = document.getElementById(containerId);
            if (canvas) {
                const container = canvas.parentElement;
                container.innerHTML = `
                    <div class="chart-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Failed to load chart library</span>
                    </div>
                `;
            }
        });
    }

    async loadChartLibrary() {
        return new Promise((resolve) => {
            if (window.Chart) {
                resolve();
                return;
            }

            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
            script.onload = resolve;
            document.head.appendChild(script);
        });
    }

    async fetchDashboardStats() {
        try {
            // Fetch total products
            const totalProductsResponse = await fetch('../../../server/api.php?endpoint=reports&action=total_products');
            const totalProductsData = await totalProductsResponse.json();
            if (totalProductsData.success) {
                this.updateStatCard('total-products', totalProductsData.data.total || 0);
            }

            // Fetch low stock items
            const lowStockResponse = await fetch('../../../server/api.php?endpoint=reports&action=low_stock');
            const lowStockData = await lowStockResponse.json();
            if (lowStockData.success) {
                this.updateStatCard('low-stock', lowStockData.data.count || 0);
            }

            // Fetch total orders
            const totalOrdersResponse = await fetch('../../../server/api.php?endpoint=reports&action=total_orders');
            const totalOrdersData = await totalOrdersResponse.json();
            if (totalOrdersData.success) {
                this.updateStatCard('total-orders', totalOrdersData.data.total || 0);
            }

            // Fetch monthly revenue
            const revenueResponse = await fetch('../../../server/api.php?endpoint=reports&action=monthly_revenue');
            const revenueData = await revenueResponse.json();
            if (revenueData.success) {
                this.updateStatCard('revenue', '$' + parseFloat(revenueData.data.revenue || 0).toFixed(2));
            }

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
            const response = await fetch('../../../server/api.php?endpoint=reports&action=popular_products');
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
        const container = document.querySelector('#popular-products-list');
        if (!container) return;

        container.innerHTML = '';
        
        products.slice(0, 4).forEach((product, index) => {
            const productElement = this.createProductCardElement(product, index + 1);
            container.appendChild(productElement);
        });
    }

    displayPlaceholderProducts() {
        const container = document.querySelector('#popular-products-list');
        if (!container) return;

        const placeholderProducts = [
            {
                product_name: 'Moisturizing Cream',
                category_name: 'Skincare',
                revenue: 45.50,
                total_sold: 15
            },
            {
                product_name: 'Foundation',
                category_name: 'Makeup',
                revenue: 25.50,
                total_sold: 8
            },
            {
                product_name: 'Shampoo',
                category_name: 'Haircare',
                revenue: 12.75,
                total_sold: 12
            },
            {
                product_name: 'Body Lotion',
                category_name: 'Tools',
                revenue: 15.00,
                total_sold: 5
            }
        ];

        container.innerHTML = '';
        placeholderProducts.forEach((product, index) => {
            const productElement = this.createProductCardElement(product, index + 1);
            container.appendChild(productElement);
        });
    }

    createProductCardElement(product, rank) {
        const productDiv = document.createElement('div');
        productDiv.className = 'popular-product-card';
        
        const categoryIcons = {
            'Skincare': 'fas fa-spa',
            'Makeup': 'fas fa-palette',
            'Haircare': 'fas fa-cut',
            'Tools': 'fas fa-tools'
        };

        const icon = categoryIcons[product.category_name] || 'fas fa-box';
        
        productDiv.innerHTML = `
            <div class="popular-product-header">
                <div class="popular-product-category">${product.category_name}</div>
                <div class="popular-product-rank">#${rank}</div>
            </div>
            <div class="popular-product-image">
                <i class="${icon}"></i>
            </div>
            <div class="popular-product-info">
                <div class="popular-product-name">${product.product_name}</div>
                <div class="popular-product-stats">
                    <div class="popular-product-revenue">$${parseFloat(product.revenue || 0).toFixed(2)}</div>
                    <div class="popular-product-sold">${product.total_sold || 0} sold</div>
                </div>
            </div>
        `;

        return productDiv;
    }

    async initializeCharts() {
        // Destroy existing charts first to prevent canvas reuse errors
        this.destroyAllCharts();
        
        await this.createCategoryPieChart();
        await this.createMonthlySalesChart();
        await this.createStockOverviewChart();
    }

    destroyAllCharts() {
        Object.values(this.charts).forEach(chart => {
            if (chart && typeof chart.destroy === 'function') {
                chart.destroy();
            }
        });
        this.charts = {};
    }

    async createCategoryPieChart() {
        try {
            const response = await fetch('../../../server/api.php?endpoint=reports&action=sales_by_category');
            const data = await response.json();
            
            const ctx = document.getElementById('categoryPieChart');
            if (!ctx) return;

            // Destroy existing chart if it exists
            if (this.charts.categoryPie) {
                this.charts.categoryPie.destroy();
                this.charts.categoryPie = null;
            }

            let chartData = [];
            if (data.success && data.data.length > 0) {
                chartData = data.data.map(item => ({
                    label: item.category_name,
                    value: parseFloat(item.total_revenue || 0)
                }));
            } else {
                // Placeholder data
                chartData = [
                    { label: 'Skincare', value: 45.50 },
                    { label: 'Makeup', value: 25.50 },
                    { label: 'Haircare', value: 12.75 },
                    { label: 'Tools', value: 15.00 }
                ];
            }

            this.charts.categoryPie = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: chartData.map(item => item.label),
                    datasets: [{
                        data: chartData.map(item => item.value),
                        backgroundColor: [
                            '#ec4899',
                            '#f472b6',
                            '#fb7185',
                            '#fda4af'
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': $' + context.parsed.toFixed(2);
                                }
                            }
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error creating category pie chart:', error);
            this.showChartError('categoryPieChart');
        }
    }

    async createMonthlySalesChart() {
        try {
            const response = await fetch('../../../server/api.php?endpoint=reports&action=monthly_sales_chart');
            const data = await response.json();
            
            const ctx = document.getElementById('monthlySalesChart');
            if (!ctx) return;

            // Destroy existing chart if it exists
            if (this.charts.monthlySales) {
                this.charts.monthlySales.destroy();
                this.charts.monthlySales = null;
            }

            let chartData = [];
            if (data.success && data.data.length > 0) {
                chartData = data.data;
            } else {
                // Placeholder data
                chartData = [
                    { month_name: 'Mar 2024', revenue: 150, order_count: 8 },
                    { month_name: 'Apr 2024', revenue: 200, order_count: 12 },
                    { month_name: 'May 2024', revenue: 180, order_count: 10 },
                    { month_name: 'Jun 2024', revenue: 220, order_count: 15 },
                    { month_name: 'Jul 2024', revenue: 190, order_count: 11 },
                    { month_name: 'Aug 2024', revenue: 197, order_count: 4 }
                ];
            }

            this.charts.monthlySales = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.map(item => item.month_name),
                    datasets: [{
                        label: 'Revenue ($)',
                        data: chartData.map(item => parseFloat(item.revenue || 0)),
                        borderColor: '#ec4899',
                        backgroundColor: 'rgba(236, 72, 153, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value;
                                }
                            }
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error creating monthly sales chart:', error);
            this.showChartError('monthlySalesChart');
        }
    }

    async createStockOverviewChart() {
        try {
            const response = await fetch('../../../server/api.php?endpoint=reports&action=category_distribution');
            const data = await response.json();
            
            const ctx = document.getElementById('stockOverviewChart');
            if (!ctx) return;

            // Destroy existing chart if it exists
            if (this.charts.stockOverview) {
                this.charts.stockOverview.destroy();
                this.charts.stockOverview = null;
            }

            let chartData = [];
            if (data.success && data.data.length > 0) {
                chartData = data.data;
            } else {
                // Placeholder data
                chartData = [
                    { category_name: 'Skincare', product_count: 3, total_stock: 160 },
                    { category_name: 'Makeup', product_count: 1, total_stock: 100 },
                    { category_name: 'Haircare', product_count: 2, total_stock: 101 },
                    { category_name: 'Tools', product_count: 1, total_stock: 60 }
                ];
            }

            this.charts.stockOverview = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.map(item => item.category_name),
                    datasets: [{
                        label: 'Products',
                        data: chartData.map(item => parseInt(item.product_count || 0)),
                        backgroundColor: 'rgba(236, 72, 153, 0.8)',
                        borderColor: '#ec4899',
                        borderWidth: 1,
                        yAxisID: 'y'
                    }, {
                        label: 'Total Stock',
                        data: chartData.map(item => parseInt(item.total_stock || 0)),
                        backgroundColor: 'rgba(244, 114, 182, 0.8)',
                        borderColor: '#f472b6',
                        borderWidth: 1,
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Number of Products'
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Stock Quantity'
                            },
                            grid: {
                                drawOnChartArea: false
                            }
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error creating stock overview chart:', error);
            this.showChartError('stockOverviewChart');
        }
    }

    showChartError(chartId) {
        const canvas = document.getElementById(chartId);
        if (canvas) {
            const container = canvas.parentElement;
            container.innerHTML = `
                <div class="chart-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Error loading chart data</span>
                </div>
            `;
        }
    }

    setupQuickActions() {
        // Add Product button
        const addProductBtn = document.querySelector('.btn-primary');
        if (addProductBtn) {
            addProductBtn.addEventListener('click', (e) => {
                e.preventDefault();
                if (window.showModal) {
                    window.showModal('addProductModal');
                }
            });
        }

        // Update Stock button
        const updateStockBtn = document.querySelector('.btn-outline:first-of-type');
        if (updateStockBtn) {
            updateStockBtn.addEventListener('click', (e) => {
                e.preventDefault();
                if (window.showModal) {
                    window.showModal('addStockModal');
                }
            });
        }

        // View Reports button
        const viewReportsBtn = document.querySelector('.btn-outline:last-of-type');
        if (viewReportsBtn) {
            viewReportsBtn.addEventListener('click', (e) => {
                e.preventDefault();
                if (window.showSection) {
                    window.showSection('reports');
                }
            });
        }
    }

    refreshStats() {
        this.fetchDashboardStats();
        this.renderPopularProducts();
        
        // Refresh charts using the cleanup method
        this.destroyAllCharts();
        this.initializeCharts();
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
    if (document.getElementById('dashboard-section') && !window.dashboardManager) {
        window.dashboardManager = new DashboardManager();
    }
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = DashboardManager;
}
