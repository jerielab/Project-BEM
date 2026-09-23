import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.store('theme', {
        dark: localStorage.getItem('theme')
            ? localStorage.getItem('theme') === 'dark'
            : window.matchMedia('(prefers-color-scheme: dark)').matches,
        toggle() {
            this.dark = !this.dark;
            localStorage.setItem('theme', this.dark ? 'dark' : 'light');
            document.documentElement.classList.toggle('dark', this.dark);
        },
        init() {
            document.documentElement.classList.toggle('dark', this.dark);
        },
    });
    Alpine.data('loginForm', () => ({
        showPassword: false,
        submitting: false,
        animating: false,
        flashing: false,
        errors: {},

        async submit(event) {
            if (this.submitting) return;
            this.submitting = true;
            this.errors = {};

            const form = event.target;
            const formData = new FormData(form);
            const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: formData,
                });

                if (res.ok) {
                    if (reduceMotion) {
                        window.location.href = '/dashboard';
                        return;
                    }

                    this.animating = true;
                    setTimeout(() => { this.flashing = true; }, 500);
                    setTimeout(() => { window.location.href = '/dashboard'; }, 760);
                    return;
                }

                if (res.status === 422) {
                    const body = await res.json();
                    const fieldErrors = body.errors || {};
                    this.errors = {
                        email: fieldErrors.email?.[0],
                        password: fieldErrors.password?.[0],
                    };
                } else {
                    this.errors = { email: 'Something went wrong. Please try again.' };
                }
            } catch (err) {
                this.errors = { email: 'Network error — check your connection and try again.' };
            } finally {
                this.submitting = false;
            }
        },
    }));
    Alpine.data('taskForm', () => ({
        open: false,
        filesError: '',

        validateFiles(event) {
            const input = event?.target?.type === 'file'
                ? event.target
                : this.$el.querySelector('input[type="file"]');

            if (!input || !input.files) {
                this.filesError = '';
                return;
            }

            const maxBytes = 10 * 1024 * 1024;
            const tooLarge = Array.from(input.files).filter((file) => file.size > maxBytes);

            if (tooLarge.length > 0) {
                this.filesError = `Ukuran file melebihi 10 MB: ${tooLarge.map((file) => file.name).join(', ')}`;
                if (event?.type === 'submit') {
                    event.preventDefault();
                }
                return;
            }

            this.filesError = '';
        },
    }));
});
window.initKanban = function initKanban(projectId) {
    if (typeof Sortable === 'undefined') return;

    document.querySelectorAll('[data-kanban-column]').forEach((column) => {
        Sortable.create(column, {
            group: 'tasks',
            animation: 150,
            ghostClass: 'opacity-40',
            onEnd: async (evt) => {
                const taskId = evt.item.dataset.taskId;
                const newStatus = evt.to.dataset.kanbanColumn;
                const newPosition = evt.newIndex;
                evt.item.classList.remove('task-landed');
                void evt.item.offsetWidth;
                evt.item.classList.add('task-landed');
                evt.item.addEventListener('animationend', () => {
                    evt.item.classList.remove('task-landed');
                }, { once: true });

                await fetch(`/projects/${projectId}/tasks/${taskId}/reorder`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ status: newStatus, position: newPosition }),
                });
            },
        });
    });
};
function initReveal() {
    if (!('IntersectionObserver' in window)) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    document.documentElement.classList.add('js-motion');

    const groupIndex = new Map();

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            const el = entry.target;
            const parent = el.parentElement;
            const i = groupIndex.get(parent) || 0;
            el.style.setProperty('--reveal-i', i);
            groupIndex.set(parent, i + 1);
            el.classList.add('is-revealed');
            observer.unobserve(el);
        });
    }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

    document.querySelectorAll('[data-reveal]').forEach((el) => observer.observe(el));
}
document.addEventListener('DOMContentLoaded', () => {
    const video = document.querySelector('[data-video-guard]');

    if (video) {
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
        const isConstrainedConnection = connection
            ? Boolean(connection.saveData) || ['slow-2g', '2g'].includes(connection.effectiveType)
            : false;

        if (prefersReducedMotion || isConstrainedConnection) {
            video.pause();
            video.removeAttribute('autoplay');
            video.style.display = 'none';
        }
    }

    initReveal();
    Alpine.start();
});
