import './bootstrap';

// ── Mobile sidebar toggle ──────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('admin-sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    if (toggle && sidebar) {
        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            overlay?.classList.toggle('hidden');
        });
        overlay?.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });
    }

    // ── Auto-dismiss alerts ──────────────────────────────
    document.querySelectorAll('[data-auto-dismiss]').forEach(el => {
        const delay = parseInt(el.dataset.autoDismiss) || 4000;
        setTimeout(() => {
            el.style.transition = 'opacity 0.4s ease';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 420);
        }, delay);
    });
});
