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
        <?php
            require_once BASE_PATH . '/src/MarkdownParser.php';
            $files = glob(BASE_PATH . '/content/news/*.md');
            if (!empty($files)) {
                rsort($files);

                foreach ($files as $file) {
                    $articleData = $this->parser->parseFile($file);
                    $finalHtml = $articleData['htmlContent'];
                    ?>
                    <article class="news-card">
                        <header class="news-header">
                            <h2><?= htmlspecialchars($articleData['title'] ?? 'News Beitrag') ?></h2>
                            <time class="news-date"><?= htmlspecialchars($articleData['date'] ?? '') ?></time>
                        </header>

                        <div class="news-body">
                            <?= $finalHtml ?>
                        </div>
                    </article>
        <?php
                }
            } else {
                echo "<p class='no-news'>Aktuell gibt es keine Neuigkeiten. Schau bald wieder vorbei!</p>";
            }
        ?>
    </section>

</main>

<?php
include BASE_PATH . '/includes/footer.php';
?>
</body>
</html>
