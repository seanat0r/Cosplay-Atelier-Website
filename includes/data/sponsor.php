<?php
$sponsor = [[
        "titel" => "uhu Logo",
        "src" => "/assets/img/uhu.png",
        "sponsor-name" => "UHU",
        "link" => "https://www.uhu.com/de-de"
],[
        "titel" => "Weissbrot Logo",
        "src" => "/assets/img/black.webp",
        "sponsor-name" => "weissbrot",
        "link" => "#"
],];
?>

<div class="sponsor-header">
    <h2>Sponsoren:</h2>
</div>

<div class="sponsor-grid">
    <?php foreach ($sponsor as $item): ?>
        <a class="sponsor-card" href="<?= $item['link'] ?>" target="_blank">
            <img src="<?= $item['src'] ?>" alt="<?= $item['titel'] ?>">
            <h3><?= $item['sponsor-name']?></h3>
        </a>
    <?php endforeach; ?>
</div>