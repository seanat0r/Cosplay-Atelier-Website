<html lang="de">
<?php
$pageTitle = "Cosplay-Atelier | Ueber uns";
include "includes/head.php";
include "includes/header.php";
?>

<main>
    <?php
    include "includes/hero.php";
    ?>
    <section class="instagram-news">
        <!-- TODO: Instagram embedding -->
    </section>
    <section class="local-news">
        <?php
            require_once __DIR__ . "/src/markdownPraser.php";
            $files = glob("content/news/*.md");
            if (!empty($files)) {
                rsort($files);

                foreach ($files as $file) {
                    $articleData = markdownParser($file);
                    $finalHtml = str_replace('src="', 'src="content/news/', $articleData['htmlContent']);
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
include "includes/footer.php";
?>
</html>
