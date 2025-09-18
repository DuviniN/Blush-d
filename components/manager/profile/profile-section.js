// Profile Section Component JavaScript
class ProfileManager {
    constructor() {
        this.activeTab = 'details';
        this.editingSection = null;
        this.originalData = {};
        this.isSaving = false; // Flag to prevent double saves
        this.init();
    }

    init() {
        this.setupTabNavigation();
        this.setupProfileImageHandler();
        this.setupFormHandlers();
        this.setupToggleSwitches();
        this.loadManagerProfile(); // Load profile data on init
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
    }

    async loadManagerProfile() {
        // Show loading indicator
        this.showLoading();
        
        try {
            const response = await fetch('../../../server/api.php?endpoint=profile&action=manager_profile');
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                const profile = data.data;
                this.populateProfileData(profile);
            } else {
                this.showNotification('Failed to load profile: ' + data.message, 'error');
            }
        } catch (error) {
            console.error('Error loading profile:', error);
            if (error.name === 'TypeError' && error.message.includes('fetch')) {
                this.showNotification('Network error: Unable to connect to server', 'error');
            } else {
                this.showNotification('Error loading profile data', 'error');
            }
        } finally {
            // Hide loading indicator
            this.hideLoading();
        }
    }

    populateProfileData(profile) {
        // Update profile display
        const profileName = document.querySelector('.profile-name-display');
        if (profileName) {
            profileName.textContent = `${profile.first_name} ${profile.last_name}`;
        }

        // Update initials
        const profileInitials = document.getElementById('profileInitials');
        if (profileInitials) {
            const initials = (profile.first_name.charAt(0) + profile.last_name.charAt(0)).toUpperCase();
            profileInitials.textContent = initials;
        }

        // Populate personal information fields
        const personalFields = ['firstName', 'lastName', 'email', 'phone'];
        const personalMapping = {
            firstName: profile.first_name,
            lastName: profile.last_name,
            email: profile.email,
            phone: profile.phone_number
        };

        personalFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field && personalMapping[fieldId]) {
                field.value = personalMapping[fieldId];
            }
        });

        // Populate work information fields
        const workFields = ['position', 'department', 'employeeId', 'startDate'];
        const workMapping = {
            position: profile.role || 'MANAGER',
            department: profile.department,
            employeeId: profile.employee_id,
            startDate: profile.start_day
        };

        workFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field && workMapping[fieldId]) {
                field.value = workMapping[fieldId];
            }
        });
    }

    async saveManagerProfile(formData) {
        try {
            const response = await fetch('../../../server/api.php?endpoint=profile&action=update_manager_profile', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const responseText = await response.text();
            
            let data;
            try {
                data = JSON.parse(responseText);
            } catch (parseError) {
                console.error('Response text:', responseText);
                throw new Error('Invalid server response format');
            }
        
            if (data.success) {
                this.showNotification('Profile updated successfully!', 'success');
                // Reload profile data
                await this.loadManagerProfile();
            } else {
                // Only show error if it's not just "no changes made"
                if (data.message && !data.message.includes('No changes made')) {
                    this.showNotification(data.message, 'error');
                    throw new Error(data.message);
                } else {
                    this.showNotification('No changes were made to your profile.', 'info');
                }
            }
        } catch (error) {
            console.error('Error saving profile:', error);
            if (error.name === 'TypeError' && error.message.includes('fetch')) {
                this.showNotification('Network error: Unable to save profile', 'error');
            } else {
                this.showNotification('Error saving profile: ' + error.message, 'error');
            }
            throw error;
        }
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
        // Use event delegation to handle all profile section clicks
        const profileSection = document.getElementById('profile-section');
        if (profileSection) {
            // Remove existing event listener if any
            profileSection.removeEventListener('click', this.handleProfileClick);
            
            // Add single event listener using event delegation
            this.handleProfileClick = this.handleProfileClick.bind(this);
            profileSection.addEventListener('click', this.handleProfileClick);
        }
    }

    handleProfileClick(e) {
        const target = e.target.closest('[data-edit-section], [data-save-section], [data-cancel-section]');
        if (!target) return;

        e.preventDefault();
        e.stopPropagation();

        if (target.hasAttribute('data-edit-section')) {
            const section = target.getAttribute('data-edit-section');
            this.enableEdit(section);
        } else if (target.hasAttribute('data-save-section')) {
            const section = target.getAttribute('data-save-section');
            this.saveChanges(section);
        } else if (target.hasAttribute('data-cancel-section')) {
            const section = target.getAttribute('data-cancel-section');
            this.cancelEdit(section);
        }
    }

    enableEdit(section) {
        const sectionMap = {
            'personal': ['firstName', 'lastName', 'email', 'phone']
        };

        const fields = sectionMap[section];
        if (!fields) return;

        // Store original data
        this.originalData[section] = {};
        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                this.originalData[section][fieldId] = field.value;
                // Don't allow editing of employeeId as it's auto-generated
                if (fieldId !== 'employeeId') {
                    field.removeAttribute('readonly');
                    field.classList.add('editable');
                    field.style.background = '#fff';
                    field.style.borderColor = '#ec4899';
                }
            }
        });

        // Show action buttons
        const actions = document.getElementById(section + 'Actions');
        if (actions) {
            actions.style.display = 'flex';
        }

        // Hide edit button
        const editBtn = document.querySelector(`[data-edit-section="${section}"]`);
        if (editBtn) {
            editBtn.style.display = 'none';
        }

        this.editingSection = section;
    }

    async saveChanges(section) {
        // Prevent double saves
        if (this.isSaving) {
            return;
        }

        const sectionMap = {
            'personal': ['firstName', 'lastName', 'email', 'phone']
        };

        const fields = sectionMap[section];
        if (!fields) return;

        // Set saving flag
        this.isSaving = true;

        // Collect form data
        const formData = {};
        let isValid = true;

        if (section === 'personal') {
            // Handle personal information save
            fields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    // Map field IDs to backend field names
                    const fieldMapping = {
                        firstName: 'first_name',
                        lastName: 'last_name',
                        email: 'email',
                        phone: 'phone_number'
                    };
                    
                    const fieldValue = field.value.trim();
                    formData[fieldMapping[fieldId] || fieldId] = fieldValue;
                    
                    // Basic validation
                    if (['firstName', 'lastName', 'email'].includes(fieldId) && !fieldValue) {
                        isValid = false;
                        field.style.borderColor = '#ef4444';
                    } else {
                        // Reset border color for valid fields
                        field.style.borderColor = '#e5e7eb';
                    }
                    
                    // Email validation
                    if (fieldId === 'email' && fieldValue) {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(fieldValue)) {
                            isValid = false;
                            field.style.borderColor = '#ef4444';
                        }
                    }
                    
                    // Phone validation (optional but if provided, should be valid)
                    if (fieldId === 'phone' && fieldValue) {
                        const phoneRegex = /^[\+]?[0-9\s\-\(\)]{10,}$/;
                        if (!phoneRegex.test(fieldValue)) {
                            isValid = false;
                            field.style.borderColor = '#ef4444';
                        }
                    }
                }
            });

            if (!isValid) {
                this.showNotification('Please fill in all required fields correctly.', 'error');
                this.isSaving = false; // Reset saving flag
                return;
            }

            // Show loading state
            const saveBtn = document.querySelector(`[data-save-section="${section}"]`);
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            saveBtn.disabled = true;

            try {
                // Call backend API
                await this.saveManagerProfile(formData);

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
                const actions = document.getElementById(`${section}Actions`);
                if (actions) actions.style.display = 'none';

                // Show edit button again
                const editBtn = document.querySelector(`[data-edit-section="${section}"]`);
                if (editBtn) {
                    editBtn.style.display = 'inline-flex';
                }

                this.editingSection = null;

            } catch (error) {
                this.showNotification('Failed to save changes. Please try again.', 'error');
            } finally {
                // Reset button
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
                // Reset saving flag
                this.isSaving = false;
            }
        } else {
            // Only personal section is editable
            this.showNotification('This section is read-only.', 'info');
            // Reset saving flag
            this.isSaving = false;
        }
    }

    cancelEdit(section) {
        const sectionMap = {
            'personal': ['firstName', 'lastName', 'email', 'phone']
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
        const editBtn = document.querySelector(`[data-edit-section="${section}"]`);
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
    
    showLoading() {
        const loadingOverlay = document.getElementById('profile-loading');
        if (loadingOverlay) {
            loadingOverlay.style.display = 'flex';
        }
    }
    
    hideLoading() {
        const loadingOverlay = document.getElementById('profile-loading');
        if (loadingOverlay) {
            loadingOverlay.style.display = 'none';
        }
    }

    // Public methods
    setActiveTab(tabName) {
        this.switchTab(tabName);
    }

    getCurrentTab() {
        return this.activeTab;
    }
    
    refreshProfile() {
        this.loadManagerProfile();
    }
    
    isEditing() {
        return this.editingSection !== null;
    }
    
    getProfileData() {
        return {
            firstName: document.getElementById('firstName')?.value || '',
            lastName: document.getElementById('lastName')?.value || '',
            email: document.getElementById('email')?.value || '',
            phone: document.getElementById('phone')?.value || '',
            position: document.getElementById('position')?.value || '',
            department: document.getElementById('department')?.value || '',
            employeeId: document.getElementById('employeeId')?.value || '',
            startDate: document.getElementById('startDate')?.value || ''
        };
    }
}

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ProfileManager;
}
