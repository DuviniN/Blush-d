<!-- Reports Section Component -->
<div id="reports-section" class="section" style="display: none;">
    <div class="reports-container">
        <!-- Reports Header -->
        <div class="reports-header">
            <div class="reports-title-section">
                <h2 class="reports-title">
                    <i class="fas fa-chart-bar"></i>
                    Inventory Reports
                </h2>
                <p class="reports-subtitle">Manage and monitor your product inventory</p>
            </div>
            <div class="reports-actions">
                <button class="btn-export" onclick="exportReport()">
                    <i class="fas fa-download"></i>
                    Export Report
                </button>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-container">
                <label for="categorySelect" class="filter-label">
                    <i class="fas fa-filter"></i>
                    Filter by Category
                </label>
                <select id="categorySelect" class="filter-select">
                    <option value="all">All Categories</option>
                    <option value="Skincare">Skincare</option>
                    <option value="Haircare">Haircare</option>
                    <option value="Makeup">Makeup</option>
                    <option value="Tools">Tools</option>
                </select>
            </div>
        </div>

        <!-- Inventory Table -->
        <div class="modern-table-wrapper">
            <div class="table-header-section">
                <h3 class="table-title">
                    <i class="fas fa-boxes"></i>
                    Product Inventory
                </h3>
                <div class="table-stats">
                    <span class="stat-item">
                        <i class="fas fa-cube"></i>
                        <span id="totalProducts">0</span> Products
                    </span>
                    <span class="stat-item warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span id="lowStockCount">0</span> Low Stock
                    </span>
                </div>
            </div>
            
            <div class="modern-table-container">
                <table class="modern-table">
                    <thead class="table-head">
                        <tr>
                            <th class="th-product">Product Name</th>
                            <th class="th-category">Category</th>
                            <th class="th-price">Price</th>
                            <th class="th-stock">Stock</th>
                            <th class="th-status">Status</th>
                            <th class="th-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="inventoryTableBody" class="table-body">
                        <!-- Inventory rows will be dynamically inserted by Dashboard.js -->
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
