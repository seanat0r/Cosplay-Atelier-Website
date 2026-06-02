<html lang="de">
<?php
$pageTitle = "Cosplay-Atelier | Ueber uns";
include "includes/head.php";
include "includes/header.php";
?>

    <main>
        <?php
        include "includes/hero.php";

        require_once './src/markdownPraser.php';
        $dataPathCommittee = __DIR__ . "/content/about_us/vorstand.md";
        $dataPathKiba = __DIR__ . "/content/about_us/kiba.md";

        $pageDataCommittee = markdownParser($dataPathCommittee);
        $pageDataKiba = markdownParser($dataPathKiba);

        ?>

        <section class="about-section">
            <article class="about-article committee">
                <figure>
                    <img src="/assets/img/committee.jpeg" alt="Vorstand">
                    <figcaption>Von links nach rechts:<br>Kevin, Pascal, Florian, Astrid & Michèle</figcaption>
                </figure>
                <div class="content-text-about-us">
                    <h2><?= htmlspecialchars($pageDataCommittee['title'] ?? 'Vorstand') ?></h2>
                    <?= $pageDataCommittee['htmlContent'] ?>
                </div>
            </article>

            <article class="about-article mascot reverse-layout">
                <figure>
                    <img src="/assets/img/Kiba_Gaming.png" alt="Unser Maskottchen beim Gamen">
                    <figcaption>Unser Kiba!</figcaption>
                </figure>
                <div class="content-text-about-us">
                    <h2><?= htmlspecialchars($pageDataKiba['title'] ?? 'Kiba') ?></h2>
                    <?= $pageDataKiba['htmlContent'] ?>
                </div>
            </article>

            <article class="about-article sponsor-section">
                <?php
                include "includes/data/sponsor.php";
                ?>
            </article>
        </section>

    </main>

<?php
include "includes/footer.php";
?>
</html>
