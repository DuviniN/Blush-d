class NavigationHandler {
    constructor() {
        this.userDropdownBtn = document.getElementById('userDropdownBtn');
        this.userDropdownMenu = document.getElementById('userDropdownMenu');
        this.logoutBtn = document.getElementById('logoutBtn');
        
        this.init();
    }
    
    init() {
        // Desktop dropdown functionality
        if (this.userDropdownBtn && this.userDropdownMenu) {
            this.userDropdownBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.toggleDropdown();
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.user-dropdown')) {
                    this.closeDropdown();
                }
            });
        }
        
        // Logout functionality
        if (this.logoutBtn) {
            this.logoutBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.handleLogout();
            });
        }
    }
    
    toggleDropdown() {
        const isOpen = this.userDropdownMenu.classList.contains('show');
        if (isOpen) {
            this.closeDropdown();
        } else {
            this.openDropdown();
        }
    }
    
    openDropdown() {
        this.userDropdownMenu.classList.add('show');
        this.userDropdownBtn.classList.add('active');
    }
    
    closeDropdown() {
        this.userDropdownMenu.classList.remove('show');
        this.userDropdownBtn.classList.remove('active');
    }
    
    async handleLogout() {
        try {
            // Show loading state
            const originalText = this.logoutBtn.textContent;
            this.logoutBtn.textContent = 'Logging out...';
            this.logoutBtn.disabled = true;
            
            // Get the base path for API calls
            const basePath = this.getBasePath();
            
            const response = await fetch(basePath + 'server/api.php?endpoint=auth&action=logout', {
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
                    window.location.href = basePath + 'index.php';
                }, 1500);
            } else {
                throw new Error(result.message || 'Logout failed');
            }
        } catch (error) {
            console.error('Logout error:', error);
            this.showLogoutMessage('Logout failed. Please try again.', 'error');
            
            // Reset button state
            this.logoutBtn.textContent = 'Logout';
            this.logoutBtn.disabled = false;
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

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new NavigationHandler();
});
