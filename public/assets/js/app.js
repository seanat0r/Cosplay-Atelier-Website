// Mobile navigation
const menuToggle = document.querySelector('.burger-menu-toggle');
const mainNav = document.querySelector('#main-navigation');

if (menuToggle && mainNav) {
    document.documentElement.classList.add('menu-ready');

    const closeMenu = (restoreFocus = false) => {
        mainNav.classList.remove('is-open');
        menuToggle.setAttribute('aria-expanded', 'false');
        menuToggle.setAttribute('aria-label', 'Menü öffnen');
        if (restoreFocus) menuToggle.focus();
    };

    menuToggle.addEventListener('click', () => {
        document.documentElement.classList.add('menu-animated');
        const isOpen = mainNav.classList.toggle('is-open');
        menuToggle.setAttribute('aria-expanded', String(isOpen));
        menuToggle.setAttribute('aria-label', isOpen ? 'Menü schliessen' : 'Menü öffnen');
        if (isOpen) mainNav.querySelector('a')?.focus();
    });

    mainNav.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => closeMenu());
    });

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && mainNav.classList.contains('is-open')) {
            closeMenu(true);
        }
    });

    document.addEventListener('click', event => {
        if (mainNav.classList.contains('is-open') && !event.target.closest('.main-header')) {
            closeMenu();
        }
    });

    window.matchMedia('(min-width: 1100px)').addEventListener('change', () => closeMenu());
}

// Photogalerie.php Animation
document.addEventListener("DOMContentLoaded", function() {

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {

            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');

                observer.unobserve(entry.target);
            }
        });
    }, {
        rootMargin: "0px",
        threshold: 0.1
    });

    const galleryItems = document.querySelectorAll('.gallery-item');
    galleryItems.forEach(item => {
        observer.observe(item);
    });
});

// plz automatic city input for password manager
const plzInput = document.querySelector("#plz") ?? null;

async function checkPlz() {
    const plz = plzInput.value.trim();
    if (!/^\d{4}$/.test(plz)) return;

    try {
        const response = await fetch(`https://openplzapi.org/ch/Localities?postalCode=${encodeURIComponent(plz)}`)
        if (!response.ok)  throw new Error("No connection to OpenPLZ API");

        const data = await response.json();
        if (!data || !data.length > 0) return;

        if (plzInput.value.trim() !== plz) return;

        const town = data[0].name;
        plzInput.value = `${plz} ${town}`;
    } catch (e) {
        console.error("Could not automatically filling the plz form input.");
    }
}
if (plzInput !== null) {
    plzInput?.addEventListener("input", checkPlz);
    plzInput?.addEventListener("change", checkPlz);
    window.addEventListener("pageshow", checkPlz);
}
//Photogalerie.php Load more Img
const loadMoreBtn = document.getElementById('load-gallery');

if (loadMoreBtn) {
    loadMoreBtn.addEventListener('click', function() {
        const hiddenItems = document.querySelectorAll('.gallery-item.hidden');

        for (let i = 0; i < 12; i++) {
            if (hiddenItems[i]) {
                hiddenItems[i].classList.remove('hidden');
                hiddenItems[i].classList.add('is-visible');
            }
        }

        if (document.querySelectorAll('.gallery-item.hidden').length === 0) {
            this.style.display = 'none';
        }
    });
}