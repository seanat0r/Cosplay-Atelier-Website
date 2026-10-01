<?php
declare(strict_types=1);
$pageTitle = "Cosplay-Atelier | News";
?>
<!DOCTYPE html>
<html lang="de">
<?php include BASE_PATH . '/includes/head.php'; ?>
<body>
<?php include BASE_PATH . '/includes/header.php'; ?>

<main>
    <?php
    include BASE_PATH . '/includes/hero.php';
    ?>
    <section class="instagram-news">
        <!-- TODO: Instagram embedding -->
    </section>
    <section class="local-news">
        <?php include BASE_PATH . '/includes/newsArticle.php'; ?>
    </section>

</main>

<?php
include BASE_PATH . '/includes/footer.php';
?>
</body>
</html>
