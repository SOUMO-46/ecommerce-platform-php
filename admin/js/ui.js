document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('.mobile-menu');
    const sidebar = document.querySelector('.sidebar');
    toggle?.addEventListener('click', () => sidebar?.classList.toggle('open'));

    const current = location.pathname.split('/').pop() || 'index.html';
    document.querySelectorAll('.sidebar a').forEach(link => {
        if (link.getAttribute('href') === current) link.classList.add('active');
    });
});

function showToast(message, type = 'default') {
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
    }
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 3200);
}
