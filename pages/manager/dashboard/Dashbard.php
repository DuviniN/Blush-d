<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard - Beauty Hub</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Main Dashboard Styles -->
    <link rel="stylesheet" href="Dashboard.css?v=<?php echo time(); ?>">
    
    <!-- Component Styles -->
    <link rel="stylesheet" href="../../../components/manager/sidebar/sidebar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../../components/manager/dashboard/dashboard-section.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../../components/manager/reports/reports-section.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../../components/manager/profile/profile-section.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../../components/manager/modals/modals.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../../components/manager/footer/footer.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../../assets/hide-scrollbar.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar Component -->
        <?php include '../../../components/manager/sidebar/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Dashboard Section Component -->
            <?php include '../../../components/manager/dashboard/dashboard-section.php'; ?>

            <!-- Profile Section Component -->
            <?php include '../../../components/manager/profile/profile-section.php'; ?>

            <!-- Reports Section Component -->
            <?php include '../../../components/manager/reports/reports-section.php'; ?>
            
            <!-- Manager Footer Component -->
            <?php include '../../../components/manager/footer/footer.php'; ?>
        </main>
    </div>

    <!-- Modal Components -->
    <?php include '../../../components/manager/modals/add-product-modal.php'; ?>
    <?php include '../../../components/manager/modals/add-stock-modal.php'; ?>
    <?php include '../../../components/manager/modals/change-password-modal.php'; ?>

    <!-- Main Dashboard Script -->
    <script src="Dashboard.js?v=<?php echo time(); ?>"></script>
    
    <!-- Component Scripts -->
    <script src="../../../components/manager/sidebar/sidebar.js?v=<?php echo time(); ?>"></script>
    <script src="../../../components/manager/dashboard/dashboard-section.js?v=<?php echo time(); ?>"></script>
    <script src="../../../components/manager/reports/reports-section.js?v=<?php echo time(); ?>"></script>
    <script src="../../../components/manager/profile/profile-section.js?v=<?php echo time(); ?>"></script>
    <script src="../../../components/manager/modals/modals.js?v=<?php echo time(); ?>"></script>
    <script src="../../../components/manager/modals/password-manager.js?v=<?php echo time(); ?>"></script>
    <script src="../../../components/manager/footer/footer.js?v=<?php echo time(); ?>"></script>
    
    <!-- Initialize Components -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize only the components that don't auto-initialize
            const profileManager = new ProfileManager();
            const modalManager = new ModalManager();
            
            // Wait a bit for other components to auto-initialize, then make them globally available
            setTimeout(() => {
                window.Components = {
                    sidebar: window.sidebarManager,
                    dashboard: window.dashboardManager, 
                    reports: window.reportsManager,
                    profile: profileManager,
                    modals: modalManager,
                    footer: window.managerFooter
                };
                console.log('All components initialized:', window.Components);
            }, 100);
        });
    </script>
</body>
</html>
