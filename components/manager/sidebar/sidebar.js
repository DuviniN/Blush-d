// Sidebar Component JavaScript
class SidebarManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupNavigation();
        this.setupResponsiveHandling();
    }

    setupNavigation() {
        // Add click handlers for navigation links
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                this.handleNavClick(link);
            });
        });
    }

    handleNavClick(clickedLink) {
        // Remove active class from all links
        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active');
        });

        // Add active class to clicked link
        clickedLink.classList.add('active');

        // Get the section to show from onclick attribute or data attribute
        const onclickValue = clickedLink.getAttribute('onclick');
        if (onclickValue) {
            // Extract section name from onclick="showSection('sectionName')"
            const match = onclickValue.match(/showSection\('(.+?)'\)/);
            if (match) {
                window.showSection(match[1]);
            }
            // Extract modal name from onclick="showModal('modalName')"
            const modalMatch = onclickValue.match(/showModal\('(.+?)'\)/);
            if (modalMatch) {
                window.showModal(modalMatch[1]);
            }
        }
    }

    setupResponsiveHandling() {
        // Handle responsive sidebar behavior
        const sidebar = document.querySelector('.sidebar');
        if (!sidebar) return;

        // Add hover effects for mobile
        if (window.innerWidth <= 768) {
            sidebar.addEventListener('mouseenter', () => {
                sidebar.classList.add('expanded');
            });

            sidebar.addEventListener('mouseleave', () => {
                sidebar.classList.remove('expanded');
            });
        }

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('expanded');
            }
        });
    }

    setActiveSection(sectionName) {
        // Find and activate the corresponding nav link
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.classList.remove('active');
            const onclickValue = link.getAttribute('onclick');
            if (onclickValue && onclickValue.includes(`showSection('${sectionName}')`)) {
                link.classList.add('active');
            }
        });
    }
}

// Initialize sidebar when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new SidebarManager();
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SidebarManager;
}
