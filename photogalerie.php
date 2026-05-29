<html lang="de">
<?php
$pageTitle = "Cosplay-Atelier | Fotogalerie";
include "includes/head.php";
include "includes/header.php";
?>

<main>
    <?php
    include 'includes/hero.php';

    $delayCounter = 1;
    $img = [[
            "img" => "/assets/img/black.webp",
    ],[
            "img" => "/assets/img/black.webp",
    ],[
            "img" => "/assets/img/black.webp",
    ],[
            "img" => "/assets/img/black.webp",
    ],[
            "img" => "/assets/img/black.webp",
    ],[
            "img" => "/assets/img/black.webp",
    ],[
            "img" => "/assets/img/black.webp",
    ],[
            "img" => "/assets/img/black.webp",
    ],[
            "img" => "/assets/img/black.webp",
    ],]
    ?>

    <section class="photo-gallery" id="galerie">
        <h2>Fotogalerie</h2>
        <div class="gallery-grid">
            <?php foreach ($img as $imgItem): ?>

            <figure class="gallery-item" style="--animation-order: <?= $delayCounter++; ?>">
                <img src="<?= $imgItem['img'] ?>" alt="Galerie Bild <?= $delayCounter ?>" loading="lazy">
            </figure>

            <?php endforeach ?>
        </div>
    </section>
</main>

<?php
include "includes/footer.php";
?>
</html>