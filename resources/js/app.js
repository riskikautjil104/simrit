import './bootstrap';

// ── Mobile sidebar toggle ──────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const initializeRichEditors = () => {
        document.querySelectorAll('[data-rich-editor]').forEach(wrapper => {
            const editor = wrapper.querySelector('[data-rich-content]');
            const textarea = wrapper.querySelector('textarea');
            if (!editor || !textarea || editor.dataset.initialized === 'true') return;

            editor.innerHTML = textarea.value || '';
            editor.dataset.initialized = 'true';
            const syncEditor = () => {
                textarea.value = editor.innerHTML;
                textarea.dispatchEvent(new Event('input', { bubbles: true }));
                textarea.dispatchEvent(new Event('change', { bubbles: true }));
            };

            editor.addEventListener('input', syncEditor);
            editor.closest('form')?.addEventListener('submit', syncEditor, true);

            wrapper.querySelectorAll('[data-command]').forEach(button => {
                button.addEventListener('mousedown', event => event.preventDefault());
                button.addEventListener('click', () => {
                    const command = button.dataset.command;
                    const value = button.dataset.value || null;
                    if (command === 'createLink') {
                        const url = window.prompt('Masukkan URL tautan:');
                        if (url) document.execCommand(command, false, url);
                    } else {
                        document.execCommand(command, false, value);
                    }
                    editor.focus();
                    syncEditor();
                });
            });
        });
    };

    initializeRichEditors();
    document.addEventListener('livewire:init', initializeRichEditors);
    document.addEventListener('livewire:initialized', initializeRichEditors);
    document.addEventListener('livewire:navigated', initializeRichEditors);
    document.addEventListener('livewire:morphed', initializeRichEditors);

    new MutationObserver(initializeRichEditors).observe(document.body, {
        childList: true,
        subtree: true,
    });

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
