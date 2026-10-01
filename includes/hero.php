<?php $isHomeHero = !empty($heroIsHome); ?>
<section class="hero<?= $isHomeHero ? '' : ' hero--compact' ?>">
    <div class="hero-content">
        <figure>
            <img src="/assets/img/hero.jpeg" alt="Platzhalter Bild 1">
        </figure>
        <div class="hero-text">
            <?php
            $dataPath = __DIR__ . "/../content/hero/hero.md";
            $heroData = $this->parser->parseFile($dataPath);
            ?>
            <h1><?= htmlspecialchars($heroData['title'] ?? 'Cosplay-Atelier') ?></h1>
            <?php if ($isHomeHero): ?>
                <div class="hero-main-content"><?= $heroData['htmlContent'] ?></div>
            <?php endif; ?>
        </div>
    </div>
</section>
