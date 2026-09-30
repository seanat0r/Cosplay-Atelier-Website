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