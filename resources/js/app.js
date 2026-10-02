const sidebar = document.getElementById('sidebar');
const backdrop = document.getElementById('backdrop');
const drawerToggles = document.querySelectorAll('[data-drawer-toggle]');

function setDrawer(open) {
    if (!sidebar || !backdrop) return;
    sidebar.classList.toggle('is-open', open);
    backdrop.classList.toggle('is-visible', open);
    document.body.classList.toggle('drawer-open', open);
    drawerToggles.forEach((btn) => btn.setAttribute('aria-expanded', open ? 'true' : 'false'));
}

drawerToggles.forEach((btn) =>
    btn.addEventListener('click', () => setDrawer(!sidebar.classList.contains('is-open')))
);
backdrop?.addEventListener('click', () => setDrawer(false));
document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') setDrawer(false);
});
sidebar?.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setDrawer(false)));

document.querySelectorAll('[data-dismiss]').forEach((btn) => {
    btn.addEventListener('click', () => btn.closest('[data-alert]')?.remove());
});

const confirmDialog = document.getElementById('confirmDialog');
if (confirmDialog) {
    const titleEl = confirmDialog.querySelector('[data-confirm-title-el]');
    const bodyEl = confirmDialog.querySelector('[data-confirm-body-el]');
    const actionEl = confirmDialog.querySelector('[data-confirm-action-el]');
    let pendingForm = null;

    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            pendingForm = form;
            titleEl.textContent = form.dataset.confirmTitle || 'Konfirmasi';
            bodyEl.textContent = form.dataset.confirm || 'Lanjutkan tindakan ini?';
            actionEl.textContent = form.dataset.confirmAction || 'Ya, lanjutkan';
            confirmDialog.showModal();
        });
    });

    confirmDialog.addEventListener('close', () => {
        if (confirmDialog.returnValue === 'confirm' && pendingForm) {
            pendingForm.submit();
        }
        pendingForm = null;
    });
}

document.querySelectorAll('form[data-guard]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (event.defaultPrevented) return;
        const btn = form.querySelector('[data-guard-btn]');
        if (!btn || btn.disabled) return;
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner" aria-hidden="true"></span> ${btn.dataset.loadingLabel || 'Menyimpan…'}`;
    });
});

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const counters = document.querySelectorAll('[data-countup]');

if (counters.length && !reduceMotion) {
    const formatter = new Intl.NumberFormat('id-ID');
    const animate = (el) => {
        const target = Number(el.dataset.countup) || 0;
        const prefix = el.dataset.prefix || '';
        const duration = 1200;
        const start = performance.now();
        const tick = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = prefix + formatter.format(Math.round(target * eased));
            if (progress < 1) requestAnimationFrame(tick);
        };
        requestAnimationFrame(tick);
    };
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                animate(entry.target);
                observer.unobserve(entry.target);
            }
        });
    });
    counters.forEach((el) => observer.observe(el));
}
