class ReportsManager {
    constructor() {
        this.currentData = [];
        this.filteredData = [];
        this.sortColumn = 0;
        this.sortDirection = 'asc';
        this.init();
    }

    init() {
        this.setupFilterHandlers();
        this.setupTableSorting();
        this.setupExportHandler();
        this.fetchInventoryReport(); // Load data when component initializes
    }

    setupFilterHandlers() {
        // Category filter only
        const categoryFilter = document.getElementById('categorySelect');
        if (categoryFilter) {
            categoryFilter.addEventListener('change', () => this.applyFilters());
        }

        // Reset filters button
        const resetBtn = document.querySelector('.reports-btn-outline');
        if (resetBtn) {
            resetBtn.addEventListener('click', () => this.resetFilters());
        }
    }

    populateCategoryFilter() {
        if (this.currentData && this.currentData.length > 0) {
            const categorySelect = document.getElementById('categorySelect');
            if (categorySelect) {
                // Get unique categories from the data
                const categories = [...new Set(this.currentData.map(item => item.category).filter(cat => cat))];
                
                // Clear existing options except "All Categories"
                categorySelect.innerHTML = '<option value="all">All Categories</option>';
                
                // Add category options
                categories.sort().forEach(category => {
                    const option = document.createElement('option');
                    option.value = category;
                    option.textContent = category;
                    categorySelect.appendChild(option);
                });
            }
        }
    }

    setupTableSorting() {
        const headers = document.querySelectorAll('.modern-table-header th.sortable');
        headers.forEach((header, index) => {
            header.addEventListener('click', () => this.sortTable(index));
        });
    }

    setupExportHandler() {
        const exportBtn = document.querySelector('.reports-btn-primary');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => this.exportReport());
        }
    }

    async fetchInventoryReport() {
        // Show loading state
        this.showLoadingState();
        
        try {
            const response = await fetch('../../../server/api.php?endpoint=products&action=products');
            const result = await response.json();
            if (result.success && result.data) {
                this.currentData = result.data;
                this.filteredData = [...this.currentData];
                this.renderInventoryTable();
                this.updateTableStats();
                this.populateCategoryFilter(); // Populate category dropdown after data is loaded
            } else {
                this.showNoDataState();
            }
        } catch (error) {
            console.error('Error fetching inventory:', error);
            this.showErrorState();
        }
    }

    showLoadingState() {
        const tbody = document.getElementById('inventoryTableBody');
        if (tbody) {
            tbody.innerHTML = `
                <tr class="loading-state">
                    <td colspan="6" class="loading-cell">
                        <div class="loading-content">
                            <div class="loading-spinner">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                            <span class="loading-text">Loading inventory data...</span>
                        </div>
                    </td>
                </tr>
            `;
        }
    }

    showNoDataState() {
        const tbody = document.getElementById('inventoryTableBody');
        if (tbody) {
            tbody.innerHTML = `
                <tr class="loading-state">
                    <td colspan="6" class="loading-cell">
                        <div class="loading-content">
                            <div class="loading-spinner">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <span class="loading-text">No inventory data available</span>
                        </div>
                    </td>
                </tr>
            `;
        }
    }

    showErrorState() {
        const tbody = document.getElementById('inventoryTableBody');
        if (tbody) {
            tbody.innerHTML = `
                <tr class="loading-state">
                    <td colspan="6" class="loading-cell">
                        <div class="loading-content">
                            <div class="loading-spinner">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <span class="loading-text">Error loading data. Please try again.</span>
                        </div>
                    </td>
                </tr>
            `;
        }
    }

    renderInventoryTable() {
        const tbody = document.getElementById('inventoryTableBody');
        if (!tbody) return;

        tbody.innerHTML = '';

        if (!this.filteredData || this.filteredData.length === 0) {
            this.showNoDataState();
            return;
        }

        this.filteredData.forEach(row => {
            const statusClass = this.getStatusClass(row.stock);
            const stockWarning = row.stock <= 20 ? 'style="color: #dc2626; font-weight: 700;"' : '';
            
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="product-name">${row.product_name || 'N/A'}</td>
                <td class="category-cell">${row.category || 'N/A'}</td>
                <td class="price-cell">Rs.${parseFloat(row.price || 0).toFixed(2)}</td>
                <td class="stock-cell" ${stockWarning}>${row.stock || 0}</td>
                <td class="status-cell">
                    <span class="status ${statusClass}">${this.getStatusText(row.stock)}</span>
                </td>
                <td class="actions-cell">
                    <button class="add_stock_btn" onclick="openAddStockModal('${row.product_id}', '${row.product_name}')" title="Add Stock">
                        <i class="fas fa-plus"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    getStatusClass(stock) {
        if (stock <= 5) return 'critical-stock';
        if (stock <= 20) return 'low-stock';
        return 'in-stock';
    }

    getStatusText(stock) {
        if (stock <= 5) return 'Critical';
        if (stock <= 20) return 'Low Stock';
        return 'In Stock';
    }

    applyFilters() {
        const categoryFilter = document.getElementById('categorySelect');
        let filtered = [...this.currentData];

        // Apply category filter only
        if (categoryFilter && categoryFilter.value && categoryFilter.value !== 'all') {
            filtered = filtered.filter(item => item.category === categoryFilter.value);
        }

        this.filteredData = filtered;
        this.renderInventoryTable();
        this.updateTableStats();
    }

    resetFilters() {
        // Reset category filter only
        const categoryFilter = document.getElementById('categorySelect');
        if (categoryFilter) {
            categoryFilter.value = 'all';
        }

        // Reset filtered data
        this.filteredData = [...this.currentData];
        this.renderInventoryTable();
        this.updateTableStats();

        // Show notification
        this.showNotification('Category filter reset successfully', 'info');
    }

    sortTable(columnIndex) {
        const headers = document.querySelectorAll('.modern-table-header th.sortable');
        const clickedHeader = headers[columnIndex];

        // Update sort direction
        if (this.sortColumn === columnIndex) {
            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            this.sortDirection = 'asc';
        }
        this.sortColumn = columnIndex;

        // Update UI indicators
        headers.forEach(header => {
            const sortIcon = header.querySelector('.sort-icon');
            if (sortIcon) {
                sortIcon.className = 'fas fa-sort sort-icon';
            }
        });

        const currentSortIcon = clickedHeader.querySelector('.sort-icon');
        if (currentSortIcon) {
            currentSortIcon.className = `fas fa-sort-${this.sortDirection === 'asc' ? 'up' : 'down'} sort-icon`;
        }

        // Sort data
        const columnMap = ['product_name', 'category', 'price', 'stock', 'status', 'actions'];
        const column = columnMap[columnIndex];

        this.filteredData.sort((a, b) => {
            let aVal = a[column] || '';
            let bVal = b[column] || '';

            // Handle numeric columns
            if (column === 'stock' || column === 'price') {
                aVal = parseFloat(aVal) || 0;
                bVal = parseFloat(bVal) || 0;
            } else if (column === 'status') {
                // Sort by stock value for status column
                aVal = parseFloat(a.stock) || 0;
                bVal = parseFloat(b.stock) || 0;
            } else if (column === 'actions') {
                // Actions column is not sortable, return 0
                return 0;
            } else {
                aVal = aVal.toString().toLowerCase();
                bVal = bVal.toString().toLowerCase();
            }

            if (this.sortDirection === 'asc') {
                return aVal > bVal ? 1 : -1;
            } else {
                return aVal < bVal ? 1 : -1;
            }
        });

        this.renderInventoryTable();
    }

    updateTableStats() {
        const totalProductsSpan = document.getElementById('totalProducts');
        const lowStockCountSpan = document.getElementById('lowStockCount');

        if (totalProductsSpan) {
            totalProductsSpan.textContent = this.filteredData.length;
        }

        if (lowStockCountSpan) {
            const lowStockItems = this.filteredData.filter(item => item.stock <= 20).length;
            lowStockCountSpan.textContent = lowStockItems;
        }
    }

    exportReport() {
        this.showNotification('Generating report...', 'info');
        
        setTimeout(() => {
            const reportData = {
                timestamp: new Date().toISOString(),
                manager: 'Duvini Nimethra',
                reportType: 'Inventory Report',
                totalProducts: this.filteredData.length,
                lowStockItems: this.filteredData.filter(item => item.stock <= 20).length,
                data: this.filteredData,
                generatedBy: 'BLUSH-D Management System'
            };
            
            const blob = new Blob([JSON.stringify(reportData, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `inventory-report-${new Date().toISOString().split('T')[0]}.json`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            
            this.showNotification('Report exported successfully!', 'success');
        }, 1500);
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `reports-notification ${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-info-circle'}"></i>
                <span>${message}</span>
            </div>
        `;
        
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? 'linear-gradient(135deg, #10b981, #059669)' : 'linear-gradient(135deg, #3b82f6, #1d4ed8)'};
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            z-index: 10000;
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

    // Public method to load data when section is shown
    loadData() {
        this.fetchInventoryReport();
    }

    // Public method to refresh data when section becomes visible
    refreshData() {
        this.fetchInventoryReport();
    }
}

// Global function to open add stock modal with product info
function openAddStockModal(productId, productName) {
    // Set the product information in the modal
    document.getElementById('selectedProductId').value = productId;
    document.getElementById('displayProductName').textContent = productName;
    
    // Clear the quantity input
    const quantityInput = document.querySelector('input[name="quantityToAdd"]');
    if (quantityInput) {
        quantityInput.value = '';
    }
    
    // Show the modal
    showModal('addStockModal');
}

// Initialize reports manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('reports-section') && !window.reportsManager) {
        window.reportsManager = new ReportsManager();
    }
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ReportsManager;
}

 