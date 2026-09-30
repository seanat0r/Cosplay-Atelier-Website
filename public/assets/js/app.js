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

const plzInput = document.querySelector("#plz");

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

plzInput?.addEventListener("input", checkPlz);
plzInput?.addEventListener("change", checkPlz);
window.addEventListener("pageshow", checkPlz);

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