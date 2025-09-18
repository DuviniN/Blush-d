
<!-- Dashboard Section Component -->
<div id="dashboard-section" class="section">
    <header class="header">
        <div class="welcome">
            <h1>Welcome back, Duvini!</h1>
            <p>Here's what's happening with your store today</p>
        </div>
    </header>
    
    <div class="dashboard-grid">
        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">Total Products</h3>
                    <p class="card-subtitle">All active products</p>
                </div>
                <div class="card-icon">
                    <i class="fas fa-box"></i>
                </div>
            </div>
            <div class="stats-number">0</div>
            <div class="stats-trend">
                <i class="fas fa-arrow-up"></i> +2 this month
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">Low Stock Items</h3>
                    <p class="card-subtitle">Items below 20 units</p>
                </div>
                <div class="card-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
            <div class="stats-number">2</div>
            <div class="stats-trend" style="color: #ef4444;">
                <i class="fas fa-arrow-down"></i> Needs attention
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">Total Orders</h3>
                    <p class="card-subtitle">Orders this month</p>
                </div>
                <div class="card-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
            <div class="stats-number">4</div>
            <div class="stats-trend">
                <i class="fas fa-arrow-up"></i> +25% from last month
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">Revenue</h3>
                    <p class="card-subtitle">Total sales this month</p>
                </div>
                <div class="card-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
            <div class="stats-number">$197</div>
            <div class="stats-trend">
                <i class="fas fa-arrow-up"></i> +15% from last month
            </div>
        </div>
    </div>

    <!-- Popular Products by Category -->
    <div class="popular-products-section">
        <div class="popular-products-header">
            <h3 class="popular-products-title">
                Popular Products by Category
            </h3>
            <p class="popular-products-subtitle">Best selling products in each category</p>
        </div>
        <div id="popular-products-list" class="popular-products-grid">
            <!-- Dynamic content will be loaded here -->
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
        <div class="charts-grid">
            <!-- Sales by Category Pie Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Sales by Category</h3>
                    <p class="chart-subtitle">Revenue distribution across categories</p>
                </div>
                <div class="chart-container">
                    <canvas id="categoryPieChart"></canvas>
                </div>
            </div>

            <!-- Monthly Sales Line Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Monthly Sales Trend</h3>
                    <p class="chart-subtitle">Sales performance over last 6 months</p>
                </div>
                <div class="chart-container">
                    <canvas id="monthlySalesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Overview Chart -->
    <div class="stock-overview-section">
        <div class="chart-card full-width">
            <div class="chart-header">
                <h3 class="chart-title">Stock Overview by Category</h3>
                <p class="chart-subtitle">Current inventory levels across categories</p>
            </div>
            <div class="chart-container">
                <canvas id="stockOverviewChart"></canvas>
            </div>
        </div>
    </div>

    <div class="card">
        <h3 class="card-title">Quick Actions</h3>
        <p class="card-subtitle">Manage your inventory and products</p>
        <div style="margin-top: 20px; display: flex; gap: 15px; flex-wrap: wrap;">
            <button class="btn btn-primary" onclick="console.log('Add Product clicked'); showModal('addProductModal')">
                <i class="fas fa-plus"></i>
                Add New Product
            </button>
            <button class="btn btn-outline" onclick="console.log('Update Stock clicked'); showModal('addStockModal')">
                <i class="fas fa-boxes"></i>
                Update Stock
            </button>
            <button class="btn btn-outline" onclick="console.log('View Reports clicked'); showSection('reports')">
                <i class="fas fa-chart-bar"></i>
                View Reports
            </button>
        </div>
    </div>
</div>
