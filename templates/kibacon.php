<?php
declare(strict_types=1);
$pageTitle = "Kibacon";
$isKibacon = true;
?>
<!DOCTYPE html>
<html lang="de">
<?php include BASE_PATH . '/includes/head.php'; ?>
<body>
<?php include BASE_PATH . '/includes/header.php'; ?>

<main class="kibacon-page">
    <?php include BASE_PATH . '/includes/hero.php';
    $dataPath = BASE_PATH . '/content/kibacon/kibacon.md';
    $pageData = $this->parser->parseFile($dataPath);
    ?>

    <section class="kibacon-layout">
        <aside class="kibacon-aside">
            <figure>
                <img src="/assets/img/Kiba.png" alt="Kiba">
                <figcaption>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eos, laudantium.</figcaption>
            </figure>
        </aside>
        <div class="kibacon-content">
            <section class="kibacon-news" aria-labelledby="kibacon-news-title">
                <div class="kibacon-news-heading">
                    <h2 id="kibacon-news-title">News zur Kibacon</h2>
                    <div class="kibacon-news-controls">
                        <button type="button" id="kibacon-news-prev" aria-label="Links: vorherige News" aria-controls="kibacon-news-track"><span aria-hidden="true">&larr;</span>Links</button>
                        <button type="button" id="kibacon-news-next" aria-label="Rechts: nächste News" aria-controls="kibacon-news-track"><span aria-hidden="true">&rarr;</span>Rechts</button>
                    </div>
                </div>
                <div class="kibacon-news-track" id="kibacon-news-track" role="region" aria-label="Kibacon-News" tabindex="0">
                    <?php include BASE_PATH . '/includes/newsArticle.php'; ?>
                </div>
            </section>
            <article class="kibacon-story">
                <h2><?= htmlspecialchars(trim($pageData['title'], "'")) ?></h2>
                <?= $pageData['htmlContent'] ?>
            </article>
        </div>
    </section>
    <section class="kibacon-sponsors">
        <article class="sponsor-section">
            <?php
            include BASE_PATH . "/includes/sponsor.php";
            ?>
        </article>
    </section>


</main>
<?php include BASE_PATH . '/includes/footer.php'; ?>
</body>
</html>
