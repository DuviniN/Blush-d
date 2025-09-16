// assets/js/main.js
document.addEventListener('DOMContentLoaded', function(){
  // deletion confirmation
  window.confirmDelete = function(){
    return confirm('Delete this product? This action cannot be undone.');
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
