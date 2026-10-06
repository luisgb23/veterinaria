(() => {
    const toggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.app-sidebar');
    const backdrop = document.querySelector('.sidebar-backdrop');
    if (!toggle || !sidebar || !backdrop) return;
    const mobile = window.matchMedia('(max-width: 767px)');
    function setOpen(open, restoreFocus = false) {
        document.body.classList.toggle('sidebar-open', open);
        toggle.setAttribute('aria-expanded', String(open));
        backdrop.hidden = !open;
        if (open) sidebar.querySelector('a').focus();
        else if (restoreFocus) toggle.focus();
    }
    toggle.addEventListener('click', () => setOpen(toggle.getAttribute('aria-expanded') !== 'true'));
    backdrop.addEventListener('click', () => setOpen(false, true));
    sidebar.addEventListener('click', event => {
        if (event.target.closest('a') && mobile.matches) setOpen(false);
    });
    document.addEventListener('keydown', event => {
        if (!document.body.classList.contains('sidebar-open')) return;
        if (event.key === 'Escape') setOpen(false, true);
        if (event.key === 'Tab') {
            const items = [toggle, ...sidebar.querySelectorAll('a, button')];
            const first = items[0], last = items[items.length - 1];
            if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
            else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
        }
    });
    mobile.addEventListener('change', () => setOpen(false));
})();
