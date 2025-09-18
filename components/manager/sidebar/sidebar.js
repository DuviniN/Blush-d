// Sidebar Component JavaScript
class SidebarManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupNavigation();
        this.restoreActiveSection();
        this.setupLogout();
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
        let activeSectionName = null;
        
        if (onclickValue) {
            // Extract section name from onclick="showSection('sectionName')"
            const match = onclickValue.match(/showSection\('(.+?)'\)/);
            if (match) {
                activeSectionName = match[1];
                this.saveActiveSection(activeSectionName);
                window.showSection(activeSectionName);
            }
            // Extract modal name from onclick="showModal('modalName')"
            const modalMatch = onclickValue.match(/showModal\('(.+?)'\)/);
            if (modalMatch) {
                window.showModal(modalMatch[1]);
            }
        }
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
        
        // Save the active section
        this.saveActiveSection(sectionName);
    }

    saveActiveSection(sectionName) {
        // Save the current active section to localStorage
        try {
            localStorage.setItem('blush_active_section', sectionName);
        } catch (error) {
            console.warn('Could not save active section to localStorage:', error);
        }
    }

    restoreActiveSection() {
        // Restore the previously active section on page load
        try {
            const savedSection = localStorage.getItem('blush_active_section');
            if (savedSection) {
                // Use setTimeout to ensure the DOM is fully ready
                setTimeout(() => {
                    this.setActiveSection(savedSection);
                    // If showSection function exists, call it to display the section
                    if (typeof window.showSection === 'function') {
                        window.showSection(savedSection);
                    }
                }, 100);
            }
        } catch (error) {
            console.warn('Could not restore active section from localStorage:', error);
        }
    }

    setupLogout() {
        // Setup logout functionality
        const logoutBtn = document.getElementById('logoutBtn') || document.querySelector('.logout-btn, [onclick*="logout"]');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.handleLogout();
            });
        }
    }

    async handleLogout() {
        try {
            // Get logout button for loading state
            const logoutBtn = document.getElementById('logoutBtn') || document.querySelector('.logout-btn, [onclick*="logout"]');
            
            // Show loading state
            if (logoutBtn) {
                const originalText = logoutBtn.textContent;
                logoutBtn.textContent = 'Logging out...';
                logoutBtn.disabled = true;
            }
            
            
            
            const response = await fetch('../../../server/api.php?endpoint=auth&action=logout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                credentials: 'same-origin'
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Show success message
                this.showLogoutMessage('Logged out successfully!');
                
                // Redirect to home page after a short delay
                setTimeout(() => {
                    window.location.href = '../../../index.php';
                }, 1500);
            } else {
                throw new Error(result.message || 'Logout failed');
            }
        } catch (error) {
            console.error('Logout error:', error);
            this.showLogoutMessage('Logout failed. Please try again.', 'error');
            
            // Reset button state
            const logoutBtn = document.getElementById('logoutBtn') || document.querySelector('.logout-btn, [onclick*="logout"]');
            if (logoutBtn) {
                logoutBtn.textContent = 'Logout';
                logoutBtn.disabled = false;
            }
        }
    }

    getBasePath() {
        // Get the current path
        const path = window.location.pathname;
        
        // If we're in the main directory
        if (path.includes('/Blush-d-main/') || path.endsWith('/Blush-d-main')) {
            // Count how many levels deep we are from the main directory
            const afterMain = path.split('/Blush-d-main/')[1] || '';
            const levels = afterMain.split('/').filter(p => p !== '').length - 1;
            
            if (levels > 0) {
                return '../'.repeat(levels);
            }
        }
        
        return '';
    }

    showLogoutMessage(message, type = 'success') {
        // Create a temporary message overlay
        const overlay = document.createElement('div');
        overlay.className = `logout-message-overlay ${type}`;
        overlay.innerHTML = `
            <div class="logout-message-content">
                <div class="logout-message-icon">
                    ${type === 'success' ? '✓' : '✕'}
                </div>
                <p>${message}</p>
            </div>
        `;
        
        document.body.appendChild(overlay);
        
        // Remove overlay after a delay
        setTimeout(() => {
            if (overlay.parentNode) {
                overlay.parentNode.removeChild(overlay);
            }
        }, type === 'success' ? 2000 : 3000);
    }
}

// Initialize sidebar when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    if (!window.sidebarManager) {
        window.sidebarManager = new SidebarManager();
    }
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SidebarManager;
}
