// Hide loader when page fully loaded
window.addEventListener('load', () => {
  const loader = document.getElementById('page-loader');
  if (!loader) return;
  loader.classList.add('fade-out');
  setTimeout(() => loader.remove(), 500);
});

// Show loader immediately (use for link clicks / AJAX)
function showLoader() {
  let loader = document.getElementById('page-loader');
  if (!loader) {
    loader = document.createElement('div');
    loader.id = 'page-loader';
    loader.innerHTML = '<div class="loader"></div>';
    document.body.appendChild(loader);
  }
  loader.style.display = 'flex';
}

// Hide loader manually (if needed)
function hideLoader() {
  const loader = document.getElementById('page-loader');
  if (loader) loader.style.display = 'none';
}

// Optional: Attach to all internal links automatically
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('a').forEach(link => {
    // Only for internal links
    if (link.hostname === window.location.hostname) {
      link.addEventListener('click', () => showLoader());
    }
  });
});

