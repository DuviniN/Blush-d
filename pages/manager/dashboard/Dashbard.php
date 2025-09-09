<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard - Beauty Hub</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Main Dashboard Styles -->
    <link rel="stylesheet" href="Dashboard.css?v=<?php echo time(); ?>">
    
    <!-- Component Styles -->
    <link rel="stylesheet" href="../../../components/manager/sidebar/sidebar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../../components/manager/dashboard/dashboard-section.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../../components/manager/reports/reports-section.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../../components/manager/profile/profile-section.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../../components/manager/modals/modals.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../../components/manager/footer/footer.css?v=<?php echo time(); ?>">>
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
    <script src="../../../components/manager/footer/footer.js?v=<?php echo time(); ?>"></script>
    <script src="PasswordManager.js?v=<?php echo time(); ?>"></script>
    
    <!-- Initialize Components -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all component managers
            const sidebarManager = new SidebarManager();
            const dashboardManager = new DashboardManager();
            const reportsManager = new ReportsManager();
            const profileManager = new ProfileManager();
            const modalManager = new ModalManager();
            const footerManager = new ManagerFooter();
            
            // Make them available globally if needed
            window.Components = {
                sidebar: sidebarManager,
                dashboard: dashboardManager,
                reports: reportsManager,
                profile: profileManager,
                modals: modalManager,
                footer: footerManager
            };
        });
    </script>
</body>
</html>
