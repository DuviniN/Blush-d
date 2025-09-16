<!-- Sidebar Component -->
<nav class="sidebar">
    <div class="logo">
        <h2>BLUSH-D</h2>
        <p>Manager Dashboard</p>
    </div>
    
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="#" class="nav-link active" onclick="showSection('dashboard')">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link" onclick="showSection('profile')">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link" onclick="showSection('reports')">
                <i class="fas fa-chart-bar"></i>
                <span>Inventory</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link" onclick="showModal('addProductModal')">
                <i class="fas fa-plus-circle"></i>
                <span>Add Product</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link" onclick="window.location.href='../../../index.php'">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</nav>
