// assets/js/main.js
document.addEventListener('DOMContentLoaded', function(){
  // deletion confirmation
  window.confirmDelete = function(){
    return confirm('Delete this product? This action cannot be undone.');
  }

  // Logout functionality
  const logoutBtn = document.getElementById('logoutBtn');
  const sidebarLogoutBtn = document.getElementById('sidebarLogoutBtn');
  const logoutModal = document.getElementById('logoutModal');
  const cancelLogout = document.getElementById('cancelLogout');
  const confirmLogout = document.getElementById('confirmLogout');

  // Show logout modal when logout button is clicked (header)
  if (logoutBtn) {
    logoutBtn.addEventListener('click', () => {
      logoutModal.style.display = 'flex';
    });
  }

  // Show logout modal when logout button is clicked (sidebar)
  if (sidebarLogoutBtn) {
    sidebarLogoutBtn.addEventListener('click', (e) => {
      e.preventDefault();
      logoutModal.style.display = 'flex';
    });
  }

  // Hide modal when cancel is clicked
  if (cancelLogout) {
    cancelLogout.addEventListener('click', () => {
      logoutModal.style.display = 'none';
    });
  }

  // Handle logout confirmation
  if (confirmLogout) {
    confirmLogout.addEventListener('click', () => {
      // Call the API endpoint for logout
      fetch('../../../server/api.php?endpoint=auth&action=logout', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
          // Redirect to admin login page regardless of API response
          window.location.href = '../../auth/login/login.php';
        })
        .catch(error => {
          console.error('Logout error:', error);
          // Still redirect to login page even if there's an error
          window.location.href = '../../auth/login/login.php';
        });
    });
  }

  // Close modal when clicking outside of it
  if (logoutModal) {
    logoutModal.addEventListener('click', (e) => {
      if (e.target === logoutModal) {
        logoutModal.style.display = 'none';
      }
    });
  }

  // theme toggle
  /*const themeToggleEls = document.querySelectorAll('#themeToggle');
  function applyTheme(isDark){
    if(isDark) document.body.classList.add('dark');
    else document.body.classList.remove('dark');
    localStorage.setItem('blush_theme_dark', isDark ? '1' : '0');
  }
  let saved = localStorage.getItem('blush_theme_dark');
  applyTheme(saved === '1');

  themeToggleEls.forEach(btn => btn.addEventListener('click', () => {
    applyTheme(!document.body.classList.contains('dark'));
  }));

  // search box basic behavior (for demo only)
  const searchInput = document.getElementById('searchInput');
  if (searchInput) {
    searchInput.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        alert('Search for: ' + searchInput.value + '\n(Implement server-side search as needed)');
      }
    });
  }*/
});
// Example: handle sidebar active link or alerts auto-hide
document.addEventListener("DOMContentLoaded", () => {
  const alerts = document.querySelectorAll(".alert");
  setTimeout(() => {
    alerts.forEach(a => a.style.display = "none");
  }, 4000);
});

// Toggle Theme (light/dark)
/*document.addEventListener("DOMContentLoaded", function () {
  const toggleBtn = document.querySelector("#toggle-theme");
  toggleBtn?.addEventListener("click", () => {
    document.body.classList.toggle("dark-mode");
  });
});*/

// Confirmation before deleting
function confirmDelete() {
  return confirm("Are you sure you want to delete this item?");
}
// Add more JS functionalities as needed
// Example: Chart.js initialization (if needed)
