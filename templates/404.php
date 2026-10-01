<?php
declare(strict_types=1);
$pageTitle = "404 | Seite nicht gefunden";
?>
<!DOCTYPE html>
<html lang="de">
<?php include BASE_PATH . '/includes/head.php'; ?>
<body>
<?php include BASE_PATH . '/includes/header.php'; ?>

<main class="not-found-page">
    <?php include BASE_PATH . '/includes/hero.php'; ?>

    <section class="not-found-card" aria-labelledby="not-found-title">
        <img class="not-found-image" src="/assets/img/Kiba.png" alt="Kiba, das Maskottchen des Cosplay-Ateliers">
        <h2 id="not-found-title">404 – Diese Seite hat sich verirrt</h2>
        <p>Auch Kiba konnte diese Seite nicht finden. Vielleicht ist der Link veraltet oder die Adresse hat sich geändert.</p>
        <p class="error-message"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></p>
        <a class="not-found-link" href="/">Zur Startseite</a>
    </section>
</main>

<?php
include BASE_PATH . '/includes/footer.php';
?>
</body>
</html>
