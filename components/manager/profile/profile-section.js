// Profile Section Component JavaScript
class ProfileManager {
    constructor() {
        this.activeTab = 'details';
        this.editingSection = null;
        this.originalData = {};
        this.init();
    }

    init() {
        this.setupTabNavigation();
        this.setupProfileImageHandler();
        this.setupFormHandlers();
        this.setupToggleSwitches();
    }

    setupTabNavigation() {
        const tabs = document.querySelectorAll('.profile-tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', (e) => {
                e.preventDefault();
                const tabName = tab.getAttribute('data-tab');
                if (tabName) {
                    this.switchTab(tabName);
                }
            });
        });
    }

    switchTab(tabName) {
        // Remove active class from all tabs and content
        document.querySelectorAll('.profile-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        document.querySelectorAll('.profile-tab-content').forEach(content => {
            content.classList.remove('active');
        });

        // Add active class to selected tab and content
        const selectedTab = document.querySelector(`[data-tab="${tabName}"]`);
        const selectedContent = document.getElementById(`${tabName}-tab`);

        if (selectedTab) selectedTab.classList.add('active');
        if (selectedContent) selectedContent.classList.add('active');

        this.activeTab = tabName;

        // Load tab-specific content
        this.loadTabContent(tabName);
    }

    loadTabContent(tabName) {
        switch (tabName) {
            case 'activity':
                this.loadActivityData();
                break;
            case 'settings':
                this.loadSettingsData();
                break;
            default:
                // Details tab - no additional loading needed
                break;
        }
    }

    loadActivityData() {
        // Simulate loading activity data
        const timeline = document.querySelector('.profile-activity-timeline');
        if (timeline && timeline.children.length <= 3) {
            // Add more activity items if needed
            const additionalActivities = [
                {
                    icon: 'fa-trash',
                    iconClass: 'warning',
                    text: 'Removed expired product "Vitamin E Cream"',
                    time: '2 days ago'
                },
                {
                    icon: 'fa-user-plus',
                    iconClass: 'success',
                    text: 'Added new team member to inventory team',
                    time: '3 days ago'
                }
            ];

            additionalActivities.forEach(activity => {
                const activityElement = this.createActivityElement(activity);
                timeline.appendChild(activityElement);
            });
        }
    }

    createActivityElement(activity) {
        const div = document.createElement('div');
        div.className = 'profile-activity-item';
        div.innerHTML = `
            <div class="profile-activity-icon ${activity.iconClass}">
                <i class="fas ${activity.icon}"></i>
            </div>
            <div class="profile-activity-content">
                <div class="profile-activity-text">${activity.text}</div>
                <div class="profile-activity-time">${activity.time}</div>
            </div>
        `;
        return div;
    }

    loadSettingsData() {
        // Load current settings state
        console.log('Loading settings data...');
    }

    setupProfileImageHandler() {
        const profileImageInput = document.getElementById('profileImageInput');
        if (profileImageInput) {
            profileImageInput.addEventListener('change', (e) => {
                this.handleProfileImageChange(e);
            });
        }
    }

    handleProfileImageChange(event) {
        const file = event.target.files[0];
        if (file) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const profileImage = document.getElementById('profileImage');
                    const profileInitials = document.querySelector('.profile-initials');
                    
                    if (profileImage && profileInitials) {
                        profileImage.src = e.target.result;
                        profileImage.style.display = 'block';
                        profileInitials.style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
                this.showNotification('Profile image updated successfully!', 'success');
            } else {
                this.showNotification('Please select a valid image file.', 'error');
            }
        }
    }

    setupFormHandlers() {
        // Setup edit buttons
        const editButtons = document.querySelectorAll('[onclick*="enableEdit"]');
        editButtons.forEach(button => {
            const onclickValue = button.getAttribute('onclick');
            const match = onclickValue.match(/enableEdit\('(.+?)'\)/);
            if (match) {
                const section = match[1];
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.enableEdit(section);
                });
            }
        });

        // Setup save buttons
        const saveButtons = document.querySelectorAll('[onclick*="saveChanges"]');
        saveButtons.forEach(button => {
            const onclickValue = button.getAttribute('onclick');
            const match = onclickValue.match(/saveChanges\('(.+?)'\)/);
            if (match) {
                const section = match[1];
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.saveChanges(section);
                });
            }
        });

        // Setup cancel buttons
        const cancelButtons = document.querySelectorAll('[onclick*="cancelEdit"]');
        cancelButtons.forEach(button => {
            const onclickValue = button.getAttribute('onclick');
            const match = onclickValue.match(/cancelEdit\('(.+?)'\)/);
            if (match) {
                const section = match[1];
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.cancelEdit(section);
                });
            }
        });
    }

    enableEdit(section) {
        const sectionMap = {
            'personal': ['fullName', 'email', 'phone', 'birthDate'],
            'work': ['position', 'department', 'employeeId', 'startDate']
        };

        const fields = sectionMap[section];
        if (!fields) return;

        // Store original data
        this.originalData[section] = {};
        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                this.originalData[section][fieldId] = field.value;
                field.removeAttribute('readonly');
                field.classList.add('editable');
                field.style.background = '#fff';
                field.style.borderColor = '#ec4899';
            }
        });

        // Show action buttons
        const actions = document.getElementById(section + 'Actions');
        if (actions) {
            actions.style.display = 'flex';
        }

        // Hide edit button
        const editBtn = document.querySelector(`[onclick*="enableEdit('${section}')"]`);
        if (editBtn) {
            editBtn.style.display = 'none';
        }

        this.editingSection = section;
    }

    async saveChanges(section) {
        const sectionMap = {
            'personal': ['fullName', 'email', 'phone', 'birthDate'],
            'work': ['position', 'department', 'employeeId', 'startDate']
        };

        const fields = sectionMap[section];
        if (!fields) return;

        // Collect form data
        const formData = {};
        let isValid = true;

        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                formData[fieldId] = field.value;
                
                // Basic validation
                if (field.hasAttribute('required') && !field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#ef4444';
                }
            }
        });

        if (!isValid) {
            this.showNotification('Please fill in all required fields.', 'error');
            return;
        }

        // Show loading state
        const saveBtn = document.querySelector(`[onclick*="saveChanges('${section}')"]`);
        const originalText = saveBtn.innerHTML;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        saveBtn.disabled = true;

        try {
            // Simulate API call
            await this.simulateApiCall(formData);

            // Reset fields
            fields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.setAttribute('readonly', 'readonly');
                    field.classList.remove('editable');
                    field.style.background = '#f3f4f6';
                    field.style.borderColor = '#e5e7eb';
                }
            });

            // Hide action buttons
            const actions = document.getElementById(section + 'Actions');
            if (actions) {
                actions.style.display = 'none';
            }

            // Show edit button again
            const editBtn = document.querySelector(`[onclick*="enableEdit('${section}')"]`);
            if (editBtn) {
                editBtn.style.display = 'inline-flex';
            }

            this.editingSection = null;
            this.showNotification('Changes saved successfully!', 'success');

        } catch (error) {
            this.showNotification('Error saving changes. Please try again.', 'error');
        } finally {
            // Reset save button
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
        }
    }

    cancelEdit(section) {
        const sectionMap = {
            'personal': ['fullName', 'email', 'phone', 'birthDate'],
            'work': ['position', 'department', 'employeeId', 'startDate']
        };

        const fields = sectionMap[section];
        if (!fields) return;

        // Restore original values
        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field && this.originalData[section] && this.originalData[section][fieldId] !== undefined) {
                field.value = this.originalData[section][fieldId];
                field.setAttribute('readonly', 'readonly');
                field.classList.remove('editable');
                field.style.background = '#f3f4f6';
                field.style.borderColor = '#e5e7eb';
            }
        });

        // Hide action buttons
        const actions = document.getElementById(section + 'Actions');
        if (actions) {
            actions.style.display = 'none';
        }

        // Show edit button again
        const editBtn = document.querySelector(`[onclick*="enableEdit('${section}')"]`);
        if (editBtn) {
            editBtn.style.display = 'inline-flex';
        }

        this.editingSection = null;
    }

    setupToggleSwitches() {
        const toggleSwitches = document.querySelectorAll('.profile-toggle-switch input');
        toggleSwitches.forEach(toggle => {
            toggle.addEventListener('change', (e) => {
                const setting = e.target.closest('.profile-setting-item');
                const settingName = setting.querySelector('h4').textContent;
                const isEnabled = e.target.checked;
                
                this.showNotification(
                    `${settingName} ${isEnabled ? 'enabled' : 'disabled'}`,
                    'info'
                );
            });
        });
    }

    simulateApiCall(data) {
        return new Promise((resolve) => {
            setTimeout(() => {
                console.log('Saving profile data:', data);
                resolve();
            }, 1000);
        });
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `profile-notification ${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas ${this.getNotificationIcon(type)}"></i>
                <span>${message}</span>
            </div>
        `;

        const colors = {
            success: 'linear-gradient(135deg, #10b981, #059669)',
            error: 'linear-gradient(135deg, #ef4444, #dc2626)',
            info: 'linear-gradient(135deg, #3b82f6, #1d4ed8)'
        };

        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${colors[type] || colors.info};
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            z-index: 10000;
            animation: slideIn 0.3s ease;
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    getNotificationIcon(type) {
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            info: 'fa-info-circle'
        };
        return icons[type] || icons.info;
    }

    // Public methods
    setActiveTab(tabName) {
        this.switchTab(tabName);
    }

    getCurrentTab() {
        return this.activeTab;
    }
}

// Initialize profile manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('profile-section')) {
        window.profileManager = new ProfileManager();
    }
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ProfileManager;
}
