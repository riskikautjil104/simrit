import './bootstrap';
import { initializeTiptapEditors, teardownTiptapEditors } from './tiptap-editor';

// ── Mobile sidebar toggle ──────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    // ── Tiptap rich text editors (e.g. Berita content) ───
    initializeTiptapEditors();
    document.addEventListener('livewire:navigated', () => initializeTiptapEditors());
    document.addEventListener('livewire:morphed', () => initializeTiptapEditors());

    new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.removedNodes.forEach((node) => {
                if (node.nodeType !== 1) return;
                if (node.matches?.('[data-tiptap]')) teardownTiptapEditors(node.parentNode ?? document);
                node.querySelectorAll?.('[data-tiptap]').forEach((el) => {
                    if (el._tiptapEditor) {
                        el._tiptapEditor.destroy();
                        delete el._tiptapEditor;
                        delete el.dataset.tiptapInitialized;
                    }
                });
            });
        });
        initializeTiptapEditors();
    }).observe(document.body, { childList: true, subtree: true });

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
