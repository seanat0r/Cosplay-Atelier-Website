<?php
$sponsor = [
        [
            "titel" => "uhu Logo",
            "src" => "/assets/img/uhu.png",
            "sponsor-name" => "UHU",
            "link" => "https://www.uhu.com/de-de"
        ],[
            "titel" => "Weisbrod Logo",
            "src" => "/assets/img/weisbrod.webp",
            "sponsor-name" => "weisbrod",
            "link" => "https://www.weisbrod.ch/"
        ],[
            "titel" => "Dubler Garage Wohlen Logo",
            "src" => "/assets/img/dubler_garage_wohlen.webp",
            "sponsor-name" => "Dubler Garage Wohlen",
            "link" => "https://www.garagedubler.ch/"
        ],
];
?>

<div class="sponsor-header">
    <h2>Sponsoren:</h2>
</div>

<div class="sponsor-grid">
    <?php foreach ($sponsor as $item): ?>
        <a class="sponsor-card" href="<?= $item['link'] ?>" target="_blank">
            <?php if (is_file(BASE_PATH . '/public' . $item['src'])): ?>
                <img src="<?= htmlspecialchars($item['src'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($item['titel'], ENT_QUOTES, 'UTF-8') ?>">
            <?php endif; ?>
            <h3><?= $item['sponsor-name']?></h3>
        </a>
    <?php endforeach; ?>
</div>
