<?php
declare(strict_types=1);
$pageTitle = "Cosplay-Atelier | Home";
?>
<!DOCTYPE html>
<html lang="de">
<?php include BASE_PATH . '/includes/head.php'; ?>
<body>
<?php include BASE_PATH . '/includes/header.php'; ?>

<main>
    <?php $heroIsHome = true; ?>
    <?php include BASE_PATH . '/includes/hero.php';
    $heroMainContent = $heroData['htmlContent']; ?>

    <section class="content">
        <?php
        require_once BASE_PATH . '/src/MarkdownParser.php';
        $dataPath = BASE_PATH . "/content/index/index.md";
        $pageData = $this->parser->parseFile($dataPath);
        ?>
        <article class="content-block">
            <figure>
                <img src="/assets/img/Kiba.png" alt="">
            </figure>
            <div class="text-content">
                <h2><?= htmlspecialchars($pageData['title'] ?? 'Verein') ?></h2>
                <?= $pageData['htmlContent'] ?>

                <div class="home-hero-content">
                    <h3>Unser Leitbild</h3>
                    <?= $heroMainContent ?>
                </div>

                <div class="external-link">
                    <p><?=htmlspecialchars($pageData['linkText'] ?? 'Verein') ?></p>
                    <ul>
                        <li><a class="facebook" href="https://www.facebook.com/CosplayAtelier.ch/" target="_blank">Facebook</a></li>
                        <li><a class="instagram" href="https://www.instagram.com/cosplayatelier.ch/" target="_blank">Instagram</a></li>
                        <li><a class="discord" href="https://cosplay-atelier.ch/discord" target="_blank">Discord</a></li>
                        <li><a class="youtube" href="https://www.youtube.com/channel/UCG9ZdVCMMVPZEbnB3jK1YDg/" target="_blank">YouTube</a></li>
                    </ul>
                </div>
            </div>
        </article>
    </section>

</main>

<?php
    include BASE_PATH . '/includes/footer.php';
?>
</body>
</html>
