<?php
declare(strict_types=1);
$pageTitle = "500 | Serverfehler";
?>
<!DOCTYPE html>
<html lang="de">
<?php include BASE_PATH . '/includes/head.php'; ?>
<body>
<?php include BASE_PATH . '/includes/header.php'; ?>

<main class="not-found-page">
    <?php include BASE_PATH . '/includes/hero.php'; ?>

    <section class="not-found-card" aria-labelledby="server-error-title">
        <img class="not-found-image" src="/assets/img/Kiba.png" alt="Kiba, das Maskottchen des Cosplay-Ateliers">
        <h2 id="server-error-title">500 – Hier ist etwas schiefgelaufen</h2>
        <p>Bitte versuche es später erneut.</p>
        <p class="error-message"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></p>
        <a class="not-found-link" href="/">Zur Startseite</a>
    </section>
</main>

<?php
include BASE_PATH . '/includes/footer.php';
?>
</body>
</html>
