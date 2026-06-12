import { Core } from '../core/core.js';

export const StaticEditors = {
        init() {
            const textareas = document.querySelectorAll('textarea.tinymce-editor');
            if (!textareas.length) return;

            const tryInit = (attempt = 0) => {
                if (typeof tinymce === 'undefined') {
                    if (attempt < 20) setTimeout(() => tryInit(attempt + 1), 150);
                    return;
                }
                textareas.forEach(ta => {
                    if (!ta.id) ta.id = 'editor-' + ta.name.replace(/[^a-zA-Z0-9_-]/g, '_');
                    if (!tinymce.get(ta.id)) Core.initTinyMCE('#' + ta.id);
                });
                document.querySelectorAll('form').forEach(f => {
                    if (f.dataset.tinymceSubmitBound || !f.querySelector('textarea.tinymce-editor')) return;
                    f.dataset.tinymceSubmitBound = 'true';
                    f.addEventListener('submit', Core.tinymceSave);
                });
            };
            tryInit();
        },
    };