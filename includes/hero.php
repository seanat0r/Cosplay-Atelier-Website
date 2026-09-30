<section class="hero">
    <div class="hero-content">
        <figure>
            <img src="/assets/img/hero.jpeg" alt="Platzhalter Bild 1">
        </figure>
        <div class="hero-text">
            <?php
            require_once __DIR__ . '/../src/MarkdownParser.php';
            $dataPath = __DIR__ . "/../content/hero/hero.md";
            $pageData = $this->parser->parseFile($dataPath);
            ?>
            <h1><?= htmlspecialchars($pageData['title'] ?? 'Cosplay-Atelier') ?></h1>
            <?= $pageData['htmlContent'] ?>
        </div>
    </div>
</section>