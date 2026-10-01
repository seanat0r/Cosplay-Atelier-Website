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
    <h1><?= htmlspecialchars($pageData["title"]) ?></h1>

    <section class="download-section">
        <p class="download-lead"><?= htmlspecialchars($pageData["description"]) ?></p>

        <div class="download-action">
            <a class="download-btn" href="/download?file=statuten.pdf">
                <span class="btn-icon" aria-hidden="true">&#x2B07; </span>
                <span>Statuten als PDF herunterladen</span>
            </a>
        </div>

        <article class="legal-document">
            <?= $pageData["htmlContent"] ?>
        </article>
    </section>
</main>
<?php include BASE_PATH . '/includes/footer.php'; ?>
</body>
</html>
