/* ============================================================
   Portfolio — interactions
   Minimal, purposeful, no gimmicks
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {
    navScroll();
    mobileNav();
    revealOnScroll();
    activeLink();
});

/* ── Sticky nav background ── */
function navScroll() {
    const nav = document.getElementById('nav');
    if (!nav) return;

    const check = () => nav.classList.toggle('stuck', window.scrollY > 60);
    window.addEventListener('scroll', check, { passive: true });
    check();
}

/* ── Mobile burger ── */
function mobileNav() {
    const burger = document.getElementById('navBurger');
    const links = document.getElementById('navLinks');
    if (!burger || !links) return;

    burger.addEventListener('click', () => {
        const isOpen = burger.classList.toggle('open');
        links.classList.toggle('open', isOpen);
        burger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        document.body.style.overflow = isOpen ? 'hidden' : '';
    });

    links.querySelectorAll('a').forEach(a => {
        a.addEventListener('click', () => {
            burger.classList.remove('open');
            links.classList.remove('open');
            burger.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        });
    });
}

/* ── Scroll reveal ── */
function revealOnScroll() {
    const els = document.querySelectorAll('[data-reveal]');
    if (!els.length) return;

    const io = new IntersectionObserver(
        entries => entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('is-visible');
                io.unobserve(e.target);
            }
        }),
        { threshold: 0.15, rootMargin: '0px 0px -40px 0px' }
    );

    els.forEach(el => io.observe(el));
}

/* ── Highlight current nav link ── */
function activeLink() {
    const sections = document.querySelectorAll('section[id]');
    const links = document.querySelectorAll('.nav-links a');
    if (!sections.length || !links.length) return;

    const update = () => {
        let current = '';
        sections.forEach(s => {
            if (window.scrollY >= s.offsetTop - 160) {
                current = s.id;
            }
        });
        links.forEach(a => {
            a.classList.toggle('active', a.getAttribute('href') === `#${current}`);
        });
    };

    window.addEventListener('scroll', update, { passive: true });
    update();
}
