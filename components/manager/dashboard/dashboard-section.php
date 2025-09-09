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
            <!-- Sample data for design preview -->
            <div class="popular-product-card">
                <div class="popular-product-header">
                    <div class="popular-product-category">Skincare</div>
                    <div class="popular-product-rank">#1</div>
                </div>
                <div class="popular-product-image">
                    <i class="fas fa-spa"></i>
                </div>
                <div class="popular-product-info">
                    <div class="popular-product-name">Moisturizing Cream</div>
                    <div class="popular-product-stats">
                        <div class="popular-product-revenue">$1,299.80</div>
                        <div class="popular-product-sold">45 sold</div>
                    </div>
                </div>
            </div>
            
            <div class="popular-product-card">
                <div class="popular-product-header">
                    <div class="popular-product-category">Makeup</div>
                    <div class="popular-product-rank">#1</div>
                </div>
                <div class="popular-product-image">
                    <i class="fas fa-palette"></i>
                </div>
                <div class="popular-product-info">
                    <div class="popular-product-name">Foundation Liquid</div>
                    <div class="popular-product-stats">
                        <div class="popular-product-revenue">$955.00</div>
                        <div class="popular-product-sold">32 sold</div>
                    </div>
                </div>
            </div>
            
            <div class="popular-product-card">
                <div class="popular-product-header">
                    <div class="popular-product-category">Fragrances</div>
                    <div class="popular-product-rank">#1</div>
                </div>
                <div class="popular-product-image">
                    <i class="fas fa-wind"></i>
                </div>
                <div class="popular-product-info">
                    <div class="popular-product-name">Eau de Parfum</div>
                    <div class="popular-product-stats">
                        <div class="popular-product-revenue">$749.97</div>
                        <div class="popular-product-sold">18 sold</div>
                    </div>
                </div>
            </div>
            
            <div class="popular-product-card">
                <div class="popular-product-header">
                    <div class="popular-product-category">Haircare</div>
                    <div class="popular-product-rank">#1</div>
                </div>
                <div class="popular-product-image">
                    <i class="fas fa-cut"></i>
                </div>
                <div class="popular-product-info">
                    <div class="popular-product-name">Argan Oil Shampoo</div>
                    <div class="popular-product-stats">
                        <div class="popular-product-revenue">$502.00</div>
                        <div class="popular-product-sold">28 sold</div>
                    </div>
                </div>
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
