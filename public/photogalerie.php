<html lang="de">
<?php
require_once __DIR__ . '/../config.php';
$pageTitle = "Cosplay-Atelier | Fotogalerie";
include BASE_PATH . '/includes/head.php';
include BASE_PATH . '/includes/header.php';
?>

<main>
    <?php
    include BASE_PATH . '/includes/hero.php';

    // 1. Hole alle Bilder
    $allGalleryImg = glob(__DIR__ . '/assets/img/gallery/*.{jpg,jpeg,png,svg,webp}', GLOB_BRACE);
    rsort($allGalleryImg);
    ?>

    <section class="photo-gallery" id="galerie">
        <h2>Fotogalerie</h2>
        <div class="gallery-grid">
            <?php
            $i = 0;

            foreach ($allGalleryImg as $imgItem):
                $i++;
                $hiddenClass = ($i > 12) ? 'hidden' : '';
                $visibleClass = ($i <= 12) ? 'is-visible' : '';
                ?>
                <figure class="gallery-item <?= $hiddenClass ?> <?= $visibleClass ?>" style="--animation-order: <?= $i; ?>">
                    <img src="/assets/img/gallery/<?= rawurlencode(basename($imgItem)) ?>" alt="Galerie Bild <?= $i ?>" loading="lazy">
                </figure>
            <?php endforeach ?>
        </div>

        <?php if (count($allGalleryImg) > 12): ?>
            <button id="load-gallery">Weitere Bilder laden</button>
        <?php endif; ?>
    </section>
</main>

<?php
include BASE_PATH . '/includes/footer.php';
?>
</html>
