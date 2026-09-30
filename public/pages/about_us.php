<?php
declare(strict_types=1);
$pageTitle = "Cosplay-Atelier | Ueber uns";
?>
<!DOCTYPE html>
<html lang="de">
<?php include BASE_PATH . '/includes/head.php'; ?>
<body>
<?php include BASE_PATH . '/includes/header.php'; ?>

    <main>
        <?php
        include BASE_PATH . "/includes/hero.php";

        $dataPathCommittee = BASE_PATH . "/content/about_us/vorstand.md";
        $dataPathKiba = BASE_PATH . "/content/about_us/kiba.md";

        $pageDataCommittee = $this->parser->parseFile($dataPathCommittee);
        $pageDataKiba = $this->parser->parseFile($dataPathKiba);

        ?>

        <section class="about-section">
            <article class="about-article committee">
                <figure>
                    <img src="../assets/img/committee.jpeg" alt="Vorstand">
                    <figcaption>Von links nach rechts:<br><?= htmlspecialchars($pageDataCommittee['bildbeschreibung'] ?? 'Vorstand') ?></figcaption>
                </figure>
                <div class="content-text-about-us">
                    <h2><?= htmlspecialchars($pageDataCommittee['title'] ?? 'Vorstand') ?></h2>
                    <?= $pageDataCommittee['htmlContent'] ?>
                </div>
            </article>

            <article class="about-article mascot reverse-layout">
                <figure>
                    <img src= "../assets/img/Kiba_Gaming.png" alt="Unser Maskottchen beim Gamen">
                    <figcaption>Unser Kiba!</figcaption>
                </figure>
                <div class="content-text-about-us">
                    <h2><?= htmlspecialchars($pageDataKiba['title'] ?? 'Kiba') ?></h2>
                    <?= $pageDataKiba['htmlContent'] ?>
                </div>
            </article>

            <article class="about-article sponsor-section">
                <?php
                include BASE_PATH . "/includes/data/sponsor.php";
                ?>
            </article>
        </section>

    </main>

<?php
include BASE_PATH . "/includes/footer.php";
?>
</body>
</html>
