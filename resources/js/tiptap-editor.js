import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Placeholder from '@tiptap/extension-placeholder';
import Image from '@tiptap/extension-image';

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

/**
 * Upload an image file to the server and return its public URL.
 * Images are stored via the server (not embedded as base64) to keep
 * the `content` field lightweight and consistent with FILE-MANAGEMENT.md.
 */
async function uploadEditorImage(file) {
    const formData = new FormData();
    formData.append('image', file);

    const response = await fetch('/admin/editor/upload-image', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken(), 'Accept': 'application/json' },
        body: formData,
        credentials: 'same-origin',
    });

    if (!response.ok) {
        const payload = await response.json().catch(() => null);
        const message = payload?.errors?.image?.[0] || payload?.message || 'Gagal mengunggah gambar.';
        throw new Error(message);
    }

    const data = await response.json();
    return data.url;
}

function insertImageFile(editor, file) {
    if (!file || !file.type.startsWith('image/')) return;

    uploadEditorImage(file)
        .then((url) => {
            editor.chain().focus().setImage({ src: url, alt: file.name.replace(/\.[^/.]+$/, '') }).run();
        })
        .catch((error) => {
            window.alert(error.message || 'Gagal mengunggah gambar.');
        });
}

/**
 * Toolbar button definitions: [label, command, isActiveName, options]
 */
const TOOLBAR_BUTTONS = [
    { label: 'B', title: 'Tebal (Ctrl+B)', cmd: (chain) => chain.toggleBold(), active: 'bold', className: 'font-bold' },
    { label: 'I', title: 'Miring (Ctrl+I)', cmd: (chain) => chain.toggleItalic(), active: 'italic', className: 'italic' },
    { label: 'U', title: 'Garis Bawah (Ctrl+U)', cmd: (chain) => chain.toggleUnderline(), active: 'underline', className: 'underline' },
    { label: 'S', title: 'Coret', cmd: (chain) => chain.toggleStrike(), active: 'strike', className: 'line-through' },
    { type: 'separator' },
    { label: 'H2', title: 'Judul Bagian', cmd: (chain) => chain.toggleHeading({ level: 2 }), active: 'heading', activeAttrs: { level: 2 } },
    { label: 'H3', title: 'Sub Judul', cmd: (chain) => chain.toggleHeading({ level: 3 }), active: 'heading', activeAttrs: { level: 3 } },
    { type: 'separator' },
    { label: '• List', title: 'Daftar Poin', cmd: (chain) => chain.toggleBulletList(), active: 'bulletList' },
    { label: '1. List', title: 'Daftar Bernomor', cmd: (chain) => chain.toggleOrderedList(), active: 'orderedList' },
    { label: '“ ”', title: 'Kutipan', cmd: (chain) => chain.toggleBlockquote(), active: 'blockquote' },
    { type: 'separator' },
    { label: 'Link', title: 'Sisipkan Tautan (Ctrl+K)', cmd: null, action: 'link' },
    { label: 'Hapus Link', title: 'Hapus Tautan', cmd: (chain) => chain.unsetLink(), active: null },
    { type: 'separator' },
    { label: '🖼 Gambar', title: 'Sisipkan Gambar', cmd: null, action: 'image' },
    { type: 'separator' },
    { label: '↺', title: 'Urungkan (Ctrl+Z)', cmd: (chain) => chain.undo(), active: null },
    { label: '↻', title: 'Ulangi (Ctrl+Shift+Z)', cmd: (chain) => chain.redo(), active: null },
];

function buildToolbar(editor, toolbarEl) {
    toolbarEl.innerHTML = '';

    TOOLBAR_BUTTONS.forEach((btn) => {
        if (btn.type === 'separator') {
            const sep = document.createElement('span');
            sep.className = 'tiptap-toolbar-sep';
            toolbarEl.appendChild(sep);
            return;
        }

        const button = document.createElement('button');
        button.type = 'button';
        button.title = btn.title;
        button.className = `tiptap-toolbar-btn ${btn.className || ''}`;
        button.textContent = btn.label;
        button.dataset.active = btn.active || '';

        button.addEventListener('click', (e) => {
            e.preventDefault();

            if (btn.action === 'link') {
                const previousUrl = editor.getAttributes('link').href;
                const url = window.prompt('Masukkan URL tautan:', previousUrl || 'https://');
                if (url === null) return;
                if (url === '') {
                    editor.chain().focus().unsetLink().run();
                    return;
                }
                editor.chain().focus().extendMarkRange('link').setLink({ href: url, target: '_blank', rel: 'noopener' }).run();
                return;
            }

            if (btn.action === 'image') {
                const input = document.createElement('input');
                input.type = 'file';
                input.accept = 'image/png,image/jpeg,image/webp';
                input.addEventListener('change', () => {
                    if (input.files?.[0]) insertImageFile(editor, input.files[0]);
                });
                input.click();
                return;
            }

            btn.cmd(editor.chain().focus()).run();
        });

        toolbarEl.appendChild(button);
    });

    editor.on('selectionUpdate', () => updateToolbarState(editor, toolbarEl));
    editor.on('transaction', () => updateToolbarState(editor, toolbarEl));
    updateToolbarState(editor, toolbarEl);
}

function updateToolbarState(editor, toolbarEl) {
    toolbarEl.querySelectorAll('.tiptap-toolbar-btn').forEach((button, index) => {
        const btn = TOOLBAR_BUTTONS.filter((b) => b.type !== 'separator')[index];
        if (!btn || !btn.active) return;
        const isActive = editor.isActive(btn.active, btn.activeAttrs || undefined);
        button.classList.toggle('is-active', isActive);
    });
}

/**
 * Initialize a Tiptap editor bound to a hidden textarea that Livewire tracks via wire:model.
 * Container markup expected:
 *   <div data-tiptap wire:ignore>
 *       <div class="tiptap-toolbar"></div>
 *       <div class="tiptap-content"></div>
 *       <textarea class="tiptap-source" hidden></textarea>
 *   </div>
 */
function initTiptapEditor(container) {
    if (container.dataset.tiptapInitialized === 'true') return;
    container.dataset.tiptapInitialized = 'true';

    const toolbarEl = container.querySelector('.tiptap-toolbar');
    const contentEl = container.querySelector('.tiptap-content');
    const sourceEl = container.querySelector('.tiptap-source');

    if (!toolbarEl || !contentEl || !sourceEl) return;

    const placeholder = container.dataset.placeholder || 'Tulis isi berita di sini...';

    const editor = new Editor({
        element: contentEl,
        extensions: [
            StarterKit,
            Placeholder.configure({ placeholder }),
            Image.configure({ inline: false, HTMLAttributes: { loading: 'lazy' } }),
        ],
        content: sourceEl.value || '',
        editorProps: {
            attributes: {
                class: 'tiptap-prose prose max-w-none focus:outline-none',
            },
            handlePaste: (view, event) => {
                const items = Array.from(event.clipboardData?.items || []);
                const imageItem = items.find((item) => item.type.startsWith('image/'));
                if (!imageItem) return false;

                const file = imageItem.getAsFile();
                if (!file) return false;

                event.preventDefault();
                insertImageFile(editor, file);
                return true;
            },
            handleDrop: (view, event) => {
                const files = Array.from(event.dataTransfer?.files || []);
                const imageFile = files.find((file) => file.type.startsWith('image/'));
                if (!imageFile) return false;

                event.preventDefault();
                insertImageFile(editor, imageFile);
                return true;
            },
        },
        onUpdate: ({ editor }) => {
            const html = editor.getHTML();
            sourceEl.value = html;
            // Notify Livewire (and any listeners) that the underlying textarea changed.
            sourceEl.dispatchEvent(new Event('input', { bubbles: true }));
        },
    });

    container._tiptapEditor = editor;

    buildToolbar(editor, toolbarEl);
}

function destroyTiptapEditor(container) {
    if (container._tiptapEditor) {
        container._tiptapEditor.destroy();
        delete container._tiptapEditor;
        delete container.dataset.tiptapInitialized;
    }
}

export function initializeTiptapEditors(root = document) {
    root.querySelectorAll('[data-tiptap]').forEach((container) => initTiptapEditor(container));
}

export function teardownTiptapEditors(root = document) {
    root.querySelectorAll('[data-tiptap]').forEach((container) => destroyTiptapEditor(container));
}
