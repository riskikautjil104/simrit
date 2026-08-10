import './bootstrap';

// ── Mobile sidebar toggle ──────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const initializeNewsShortcuts = () => {
        document.querySelectorAll('#news-content-editor').forEach(editor => {
            if (editor.dataset.shortcutsInitialized === 'true') return;
            editor.dataset.shortcutsInitialized = 'true';
            editor.addEventListener('keydown', event => {
                if (!(event.ctrlKey || event.metaKey)) return;

                const start = editor.selectionStart;
                const end = editor.selectionEnd;
                const selected = editor.value.slice(start, end);
                const key = event.key.toLowerCase();

                if (key === 'k') {
                    event.preventDefault();
                    if (!selected) return;
                    const url = window.prompt('Masukkan URL tautan:', 'https://');
                    if (!url) return;
                    editor.setRangeText(`<a href="${url}" target="_blank" rel="noopener">${selected}</a>`, start, end, 'select');
                    editor.dispatchEvent(new Event('input', { bubbles: true }));
                    return;
                }

                const tags = { b: ['<strong>', '</strong>'], i: ['<em>', '</em>'], u: ['<u>', '</u>'] };
                const tag = tags[key];
                if (!tag || !selected) return;

                event.preventDefault();

                editor.setRangeText(`${tag[0]}${selected}${tag[1]}`, start, end, 'select');
                editor.dispatchEvent(new Event('input', { bubbles: true }));
            });
        });
    };

    initializeNewsShortcuts();
    document.addEventListener('livewire:navigated', initializeNewsShortcuts);
    document.addEventListener('livewire:morphed', initializeNewsShortcuts);
    new MutationObserver(initializeNewsShortcuts).observe(document.body, { childList: true, subtree: true });

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
