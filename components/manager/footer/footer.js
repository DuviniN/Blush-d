// Manager Footer Component JavaScript
class ManagerFooter {
    constructor() {
        this.init();
    }

    init() {
        this.updateCopyright();
        this.addInteractivity();
    }

    updateCopyright() {
        // Update copyright year if needed
        const currentYear = new Date().getFullYear();
        const copyrightElements = document.querySelectorAll('.footer-info p');
        
        copyrightElements.forEach(element => {
            if (element.textContent.includes('©')) {
                element.textContent = element.textContent.replace(/\d{4}/, currentYear);
            }
        });
    }

    addInteractivity() {
        // Add subtle hover effects and interactions
        const statItems = document.querySelectorAll('.footer-stats .stat-item');
        
        statItems.forEach(item => {
            item.addEventListener('mouseenter', () => {
                item.style.transform = 'translateY(-2px) scale(1.02)';
            });
            
            item.addEventListener('mouseleave', () => {
                item.style.transform = 'translateY(0) scale(1)';
            });
        });
    }

    // Method to update footer stats if needed
    updateStats(data) {
        // This can be used to update footer statistics if needed in the future
        console.log('Footer stats updated:', data);
    }
}

// Initialize footer when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.manager-footer')) {
        window.managerFooter = new ManagerFooter();
    }
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ManagerFooter;
}
