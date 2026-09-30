<?php
declare(strict_types=1);
$pageTitle = "Statuten";
?>
<!DOCTYPE html>
<html lang="de">
<?php include BASE_PATH . '/includes/head.php'; ?>
<body>
<?php include BASE_PATH . '/includes/header.php'; ?>
<main>
<?php
include BASE_PATH . '/includes/hero.php';
require_once BASE_PATH . '/src/MarkdownParser.php';
$dataPath = BASE_PATH . '/content/bylaws/bylaws.md';
$pageData = $this->parser->parseFile($dataPath);
?>
    <h1><?= htmlspecialchars($pageData["title"])?></h1>
    <section class="download-Section">
        <p><?= htmlspecialchars($pageData["description"])?> <br>
            <a href="/src/download.php?file=statuten.pdf">Hier könnt Ihr unsere Statuten downloaden!</a>
        </p>
        <article>
            <?= $pageData["htmlContent"]?>
        </article>

    </section>
</main>
<?php include BASE_PATH . '/includes/footer.php'; ?>
</body>
</html>
