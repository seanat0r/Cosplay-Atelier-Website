<?php
declare(strict_types=1);
if (!isset($isKibacon)) {
    $isKibacon = false;
}
$files = glob(BASE_PATH . '/content/news/*.md');
if (!empty($files)):
    rsort($files);

    foreach ($files as $file):
        $articleData = $this->parser->parseFile($file);
        $finalHtml = $articleData['htmlContent'];
        $finalHtml = str_replace('<img', '<img loading="lazy"', $finalHtml);

        // skip non kiba news
        if ($isKibacon && ($articleData['kibacon'] ?? 'false') !== 'true') {
            continue;
        }

        // display kiba news
        if ($isKibacon):?>
            <a href="/news/<?= rawurlencode((pathinfo($file, PATHINFO_FILENAME))) ?>" aria-label="<?= htmlspecialchars($articleData['title'] ?? 'News Beitrag', ENT_QUOTES, 'UTF-8') ?>">
            <article class="news-description">
                <header class="news-description-header">
                    <span class="news-description-icon" aria-hidden="true">&#10142;</span>
                    <h2><?= htmlspecialchars($articleData['title'] ?? 'News Beitrag') ?></h2>
                    <time class="news-date"><?= htmlspecialchars($articleData['date'] ?? '') ?></time>
                </header>
                <div class="news-description-body">
                    <p><?= htmlspecialchars($articleData['description']) ?></p>
                </div>
            </article>
            </a>
            <?php continue; ?>
        <?php endif; ?>

        <!-- if it's not a kiba news, display all news -->
        <article class="news-card" id="<?= htmlspecialchars(pathinfo($file, PATHINFO_FILENAME)) ?>">
            <header class="news-header">
                <h2><?= htmlspecialchars($articleData['title'] ?? 'News Beitrag') ?></h2>
                <time class="news-date"><?= htmlspecialchars($articleData['date'] ?? '') ?></time>
            </header>

            <div class="news-body">
                <?= $finalHtml ?>
            </div>
        </article>
        <?php
    endforeach;
 else:
    echo "<p class='no-news'>Aktuell gibt es keine Neuigkeiten. Schau bald wieder vorbei!</p>";
 endif;
?>
