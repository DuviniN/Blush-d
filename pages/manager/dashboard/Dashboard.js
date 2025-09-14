// Legacy Dashboard.js - Compatibility layer for component-based architecture
// This file maintains backward compatibility while components handle the main functionality

// Global functions for backward compatibility
window.showSection = function(sectionName) {
    // Hide all sections first
    const sections = document.querySelectorAll('.section');
    
    sections.forEach(section => {
        section.style.display = 'none';
    });
    
    // Show the requested section
    const targetSection = document.getElementById(sectionName + '-section');
    
    if (targetSection) {
        targetSection.style.display = 'block';
        
        // Refresh data for reports section
        if (sectionName === 'reports' && window.Components && window.Components.reports) {
            window.Components.reports.refreshData();
        }
    } else {
        console.error('Section not found:', sectionName + '-section');
    }
    
    // Update sidebar active state
    if (window.Components && window.Components.sidebar) {
        window.Components.sidebar.setActiveSection(sectionName);
    }
};

window.showModal = function(modalId) {
    if (window.Components && window.Components.modals) {
        window.Components.modals.showModal(modalId);
    }
};

window.closeModal = function(modalId) {
    if (window.Components && window.Components.modals) {
        window.Components.modals.closeModal(modalId);
    }
};

window.editProduct = function(productId) {
    if (window.Components && window.Components.modals) {
        window.Components.modals.editProduct(productId);
    }
};

// Legacy initialization for any remaining functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize default section (dashboard)
    setTimeout(() => {
        window.showSection('dashboard');
    }, 100);
});
