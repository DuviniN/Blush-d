// Fetch dashboard summary stats on page load
window.addEventListener('DOMContentLoaded', function() {
    fetchDashboardStats();
    renderPopularProductsPlaceholder();
    
    // Add product image preview functionality
    const productImageInput = document.getElementById('productImageInput');
    if (productImageInput) {
        productImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('productImagePreview');
            const previewImg = document.getElementById('previewImg');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    previewImg.src = evt.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });
    }
    
    // Add event listener for category filter
    const reportsSection = document.getElementById('reports-section');
    if (reportsSection) {
        reportsSection.addEventListener('change', function(e) {
            if (e.target && e.target.id === 'categorySelect') {
                fetchInventoryReport();
            }
        });
    }
// Placeholder: Render popular products (to be replaced with real data)
function renderPopularProductsPlaceholder() {
    fetchPopularProducts();
}

// Fetch and render popular products from database
function fetchPopularProducts() {
    
    
    fetch('../../../server/ReportController.php?action=popular_products')
        .then(res => res.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                let html = '';
                data.data.forEach(product => {
                    html += `
                        <div class="popular-product-card">
                            <div class="popular-product-category">${product.category}</div>
                            <div class="popular-product-name">${product.product_name}</div>
                            <div class="popular-product-revenue">$${product.total_revenue}</div>
                            <div class="popular-product-sold">${product.total_sold} sold</div>
                        </div>
                    `;
                });
                list.innerHTML = html;
            } else {
                // Fallback to placeholder data if no database results
                list.innerHTML = `
                    <div class="popular-product-card">
                        <div class="popular-product-category">No Data</div>
                        <div class="popular-product-name">No popular products found</div>
                        <div class="popular-product-revenue">$0.00</div>
                        <div class="popular-product-sold">0 sold</div>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error fetching popular products:', error);
            // Fallback to placeholder data on error
            list.innerHTML = `
                <div class="popular-product-card">
                    <div class="popular-product-category">Error</div>
                    <div class="popular-product-name">Unable to load data</div>
                    <div class="popular-product-revenue">$0.00</div>
                    <div class="popular-product-sold">0 sold</div>
                </div>
            `;
        });
}
});

function fetchDashboardStats() {
    // Total Products
    fetch('../../../server/ReportController.php?action=total_products')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                setDashboardStat('total-products', data.total);
            }
        });
    // Low Stock
    fetch('../../../server/ReportController.php?action=low_stock')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                setDashboardStat('low-stock', data.low_stock);
            }
        });
    // Total Orders
    fetch('../../../server/ReportController.php?action=total_orders')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                setDashboardStat('total-orders', data.total_orders);
            }
        });
    // Revenue (mock, as not implemented in backend)
    setDashboardStat('revenue', '$197');
}

function setDashboardStat(type, value) {
    // Map type to card index or selector
    let selector = '';
    switch(type) {
        case 'total-products': selector = '.dashboard-grid .card:nth-child(1) .stats-number'; break;
        case 'low-stock': selector = '.dashboard-grid .card:nth-child(2) .stats-number'; break;
        case 'total-orders': selector = '.dashboard-grid .card:nth-child(3) .stats-number'; break;
        case 'revenue': selector = '.dashboard-grid .card:nth-child(4) .stats-number'; break;
        default: return;
    }
    const el = document.querySelector(selector);
    if (el) el.textContent = value;
}


function showSection(sectionName) {
    console.log('showSection called with:', sectionName); // Debug log
    // Hide all sections
    document.querySelectorAll('.section').forEach(section => {
        section.style.display = 'none';
    });
    // Show selected section
    const targetSection = document.getElementById(sectionName + '-section');
    if (targetSection) {
        targetSection.style.display = 'block';
        console.log('Section activated:', sectionName); // Debug log
    } else {
        console.error('Section not found:', sectionName + '-section'); // Debug log
    }
    
    // Update active nav link - find the nav link that matches this section
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });
    
    // Find and activate the corresponding nav link
    const targetNavLink = document.querySelector(`.nav-link[onclick*="showSection('${sectionName}')"]`);
    if (targetNavLink) {
        targetNavLink.classList.add('active');
    }

    // If reports section, fetch and render inventory
    if (sectionName === 'reports') {
        fetchInventoryReport();
    }
}

function fetchInventoryReport() {
    // Show loading state
    const tbody = document.getElementById('inventoryTableBody');
    if (tbody) {
        tbody.innerHTML = `
            <tr class="loading-state">
                <td colspan="6" class="loading-cell">
                    <div class="loading-content">
                        <div class="loading-spinner">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                        <span class="loading-text">Loading inventory data...</span>
                    </div>
                </td>
            </tr>
        `;
    }
    
    fetch('../../../server/ReportController.php?action=inventory')
        .then(response => response.json())
        .then(result => {
            let data = result.success ? result.data : [];
            // Filter by category if selected
            const select = document.getElementById('categorySelect');
            let selected = select ? select.value : 'all';
            if (selected && selected !== 'all') {
                // Map UI label to DB category
                let map = {
                    'Skin': 'Skincare',
                    'Hair': 'Haircare',
                    'Makeup': 'Makeup',
                    'Tools': 'Tools'
                };
                let dbCategory = map[selected] || selected;
                data = data.filter(row => row.category === dbCategory);
            }
            renderInventoryTable(data);
        })
        .catch(error => {
            console.error('Error fetching inventory:', error);
            renderInventoryTable([]);
        });
}

// Add category filter event listener
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('categorySelect');
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            if (document.getElementById('reports-section').style.display !== 'none') {
                fetchInventoryReport();
            }
        });
    }
});

function renderInventoryTable(data) {
    const tbody = document.getElementById('inventoryTableBody');
    if (!tbody) return;
    
    // Clear loading state
    tbody.innerHTML = '';
    
    // Update stats in table header
    const totalProductsSpan = document.getElementById('totalProducts');
    const lowStockCountSpan = document.getElementById('lowStockCount');
    
    if (totalProductsSpan) {
        totalProductsSpan.textContent = data ? data.length : 0;
    }
    
    if (lowStockCountSpan) {
        const lowStockItems = data ? data.filter(item => item.stock <= 20).length : 0;
        lowStockCountSpan.textContent = lowStockItems;
    }
    
    if (!data || data.length === 0) {
        tbody.innerHTML = `
            <tr class="loading-state">
                <td colspan="6" class="loading-cell">
                    <div class="loading-content">
                        <div class="loading-spinner">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <span class="loading-text">No inventory data available</span>
                    </div>
                </td>
            </tr>
        `;
        return;
    }
    
    data.forEach(row => {
        const statusClass = row.status === 'Low Stock' ? 'low-stock' : 'in-stock';
        const stockWarning = row.stock <= 20 ? 'style="color: #dc2626; font-weight: 700;"' : '';
        
        tbody.innerHTML += `
            <tr>
                <td class="product-name">${row.product_name || 'N/A'}</td>
                <td class="category-cell">${row.category || 'N/A'}</td>
                <td class="price-cell">$${parseFloat(row.price || 0).toFixed(2)}</td>
                <td class="stock-cell" ${stockWarning}>${row.stock || 0}</td>
                <td class="status-cell">
                    <span class="status ${statusClass}">${row.status || 'Unknown'}</span>
                </td>
                <td class="actions-cell">
                    <button class="add_stock_btn" onclick="showModal('addStockModal')" title="Add Stock">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button class="edit_product_btn" onclick="editProduct(${row.product_id || 0})" title="Edit Product">
                        <i class="fas fa-edit"></i>
                    </button>
                </td>
            </tr>
        `;
    });
}

function showModal(modalId) {
    console.log('showModal called with:', modalId); // Debug log
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        console.log('Modal activated:', modalId); // Debug log
    } else {
        console.error('Modal not found:', modalId); // Debug log
    }
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

// Edit Product Function
function editProduct(productId) {
    console.log('Edit product:', productId);
    // You can implement the edit functionality here
    // For now, just show an alert
    showNotification('Edit functionality coming soon!', 'info');
}

// Close modal when clicking outside
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.remove('active');
        }
    });
});

// Modern Profile Management Functions
function switchProfileTab(tabName) {
    // Remove active class from all tabs and content
    document.querySelectorAll('.profile-tab').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.profile-tab-content').forEach(content => content.classList.remove('active'));
    
    // Add active class to clicked tab and corresponding content
    event.target.classList.add('active');
    document.getElementById(tabName + '-tab').classList.add('active');
}

function enableEdit(section) {
    const sectionMap = {
        'personal': ['fullName', 'email', 'phone', 'birthDate'],
        'work': ['position', 'department', 'employeeId', 'startDate']
    };
    
    const fields = sectionMap[section];
    if (!fields) return;
    
    // Enable input fields
    fields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.removeAttribute('readonly');
            field.style.background = '#fff';
            field.style.borderColor = '#E75480';
        }
    });
    
    // Show action buttons
    const actions = document.getElementById(section + 'Actions');
    if (actions) {
        actions.style.display = 'flex';
    }
    
    // Hide edit button
    const editBtn = document.querySelector(`[onclick="enableEdit('${section}')"]`);
    if (editBtn) {
        editBtn.style.display = 'none';
    }
}

function saveChanges(section) {
    const sectionMap = {
        'personal': ['fullName', 'email', 'phone', 'birthDate'],
        'work': ['position', 'department', 'employeeId', 'startDate']
    };
    
    const fields = sectionMap[section];
    if (!fields) return;
    
    // Collect form data
    const formData = {};
    fields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            formData[fieldId] = field.value;
        }
    });
    
    // Simulate API call
    console.log('Saving changes:', formData);
    
    // Show loading state
    const saveBtn = document.querySelector(`[onclick="saveChanges('${section}')"]`);
    const originalText = saveBtn.textContent;
    saveBtn.textContent = 'Saving...';
    saveBtn.disabled = true;
    
    // Simulate successful save
    setTimeout(() => {
        // Reset fields
        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.setAttribute('readonly', true);
                field.style.background = '#f9fafb';
                field.style.borderColor = '#e5e7eb';
            }
        });
        
        // Hide action buttons
        const actions = document.getElementById(section + 'Actions');
        if (actions) {
            actions.style.display = 'none';
        }
        
        // Show edit button again
        const editBtn = document.querySelector(`[onclick="enableEdit('${section}')"]`);
        if (editBtn) {
            editBtn.style.display = 'flex';
        }
        
        // Reset save button
        saveBtn.textContent = originalText;
        saveBtn.disabled = false;
        
        // Show success notification
        showNotification('Changes saved successfully!', 'success');
    }, 1000);
}

function cancelEdit(section) {
    const sectionMap = {
        'personal': ['fullName', 'email', 'phone', 'birthDate'],
        'work': ['position', 'department', 'employeeId', 'startDate']
    };
    
    const fields = sectionMap[section];
    if (!fields) return;
    
    // Reset fields to readonly
    fields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.setAttribute('readonly', true);
            field.style.background = '#f9fafb';
            field.style.borderColor = '#e5e7eb';
            // Reset to original value (you might want to store original values)
        }
    });
    
    // Hide action buttons
    const actions = document.getElementById(section + 'Actions');
    if (actions) {
        actions.style.display = 'none';
    }
    
    // Show edit button again
    const editBtn = document.querySelector(`[onclick="enableEdit('${section}')"]`);
    if (editBtn) {
        editBtn.style.display = 'flex';
    }
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? 'linear-gradient(135deg, #10b981, #059669)' : 'linear-gradient(135deg, #3b82f6, #1d4ed8)'};
        color: white;
        padding: 15px 20px;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Enhanced profile image functionality
document.addEventListener('DOMContentLoaded', function() {
    const profileImageInput = document.getElementById('profileImageInput');
    if (profileImageInput) {
        profileImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    const profileImage = document.getElementById('profileImage');
                    const profileInitials = document.getElementById('profileInitials');
                    
                    if (profileImage && profileInitials) {
                        profileImage.src = evt.target.result;
                        profileImage.style.display = 'block';
                        profileInitials.style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Add CSS animation for notifications
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        .notification-content {
            display: flex;
            align-items: center;
            gap: 10px;
        }
    `;
    document.head.appendChild(style);
});

// Enhanced Profile Management Functions
function switchProfileTab(tabName) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.profile-tab-content');
    tabContents.forEach(content => {
        content.classList.remove('active');
    });

    // Remove active class from all tabs
    const tabs = document.querySelectorAll('.profile-tab');
    tabs.forEach(tab => {
        tab.classList.remove('active');
    });

    // Show selected tab content
    const selectedTab = document.getElementById(`${tabName}-tab`);
    if (selectedTab) {
        selectedTab.classList.add('active');
    }

    // Add active class to clicked tab
    const clickedTab = document.querySelector(`[data-tab="${tabName}"]`);
    if (clickedTab) {
        clickedTab.classList.add('active');
    }
}

// Export Report Function
function exportReport() {
    showNotification('Generating report...', 'info');
    
    // Simulate report generation
    setTimeout(() => {
        showNotification('Report exported successfully!', 'success');
        
        // Create and download a sample report file
        const reportData = {
            timestamp: new Date().toISOString(),
            manager: 'Duvini Weerasinghe',
            reportType: 'Inventory Report',
            totalProducts: document.getElementById('totalProducts')?.textContent || '0',
            lowStockItems: document.getElementById('lowStockCount')?.textContent || '0',
            generatedBy: 'BLUSH-D Management System'
        };
        
        const blob = new Blob([JSON.stringify(reportData, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `inventory-report-${new Date().toISOString().split('T')[0]}.json`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }, 1500);
}

function enableEdit(section) {
    const sectionElement = document.querySelector(`[id*="${section}"]`).closest('.profile-modern-card');
    const fields = sectionElement.querySelectorAll('input');
    
    fields.forEach(field => {
        field.removeAttribute('readonly');
        field.classList.add('editable');
    });

    const actions = document.getElementById(`${section}Actions`);
    if (actions) {
        actions.style.display = 'flex';
    }

    const editBtn = sectionElement.querySelector('.profile-btn-secondary');
    if (editBtn) {
        editBtn.style.display = 'none';
    }
}

function saveChanges(section) {
    const sectionElement = document.querySelector(`[id*="${section}"]`).closest('.profile-modern-card');
    const fields = sectionElement.querySelectorAll('input');
    const data = {};
    
    fields.forEach(field => {
        data[field.id] = field.value;
        field.setAttribute('readonly', 'readonly');
        field.classList.remove('editable');
    });

    // Here you would typically send data to the server
    console.log('Saving changes for', section, data);

    const actions = document.getElementById(`${section}Actions`);
    if (actions) {
        actions.style.display = 'none';
    }

    const editBtn = sectionElement.querySelector('.profile-btn-secondary');
    if (editBtn) {
        editBtn.style.display = 'inline-flex';
    }

    showSuccessNotification('Changes saved successfully!');
}

function cancelEdit(section) {
    const sectionElement = document.querySelector(`[id*="${section}"]`).closest('.profile-modern-card');
    const fields = sectionElement.querySelectorAll('input');
    
    fields.forEach(field => {
        field.setAttribute('readonly', 'readonly');
        field.classList.remove('editable');
        // Reset to original values here if needed
    });

    const actions = document.getElementById(`${section}Actions`);
    if (actions) {
        actions.style.display = 'none';
    }

    const editBtn = sectionElement.querySelector('.profile-btn-secondary');
    if (editBtn) {
        editBtn.style.display = 'inline-flex';
    }
}

function generateReport() {
    showSuccessNotification('Generating report...');
    
    // Simulate report generation
    setTimeout(() => {
        showSuccessNotification('Report exported successfully!');
        // Create and download a sample report file
        const reportData = {
            timestamp: new Date().toISOString(),
            manager: 'Duvini Weerasinghe',
            totalSales: '$24,580',
            totalOrders: '156',
            totalCustomers: '89'
        };
        
        const blob = new Blob([JSON.stringify(reportData, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `manager-report-${new Date().toISOString().split('T')[0]}.json`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }, 2000);
}

// Initialize profile functionality
document.addEventListener('DOMContentLoaded', function() {
    // Set default profile tab to overview
    if (document.getElementById('profile-section')) {
        switchProfileTab('overview');
    }
    
    // Add toggle switch functionality
    const toggleSwitches = document.querySelectorAll('.profile-toggle-switch input');
    toggleSwitches.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const setting = this.closest('.profile-setting-item').querySelector('h4').textContent;
            const status = this.checked ? 'enabled' : 'disabled';
            showSuccessNotification(`${setting} ${status}`);
        });
    });
});






