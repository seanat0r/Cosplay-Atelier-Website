<html lang="de">
<?php
    $pageTitle = "Cosplay-Atelier | Home";
    include "includes/head.php";
    include "includes/header.php";
   ?>

<main>
    <?php
        include 'includes/hero.php';
    ?>

    <section class="content">
        <?php
        require_once './src/markdownPraser.php';
        $dataPath = __DIR__ . "/content/index/index.md";
        $pageData = markdownParser($dataPath);
        ?>
        <article class="content-block">
            <figure>
                <img src="assets/img/Kiba.png" alt="">
            </figure>
            <div class="text-content">
                <p><?=htmlspecialchars($pageData['title'] ?? 'Verein') ?>   </p>
                <br>
                <?= $pageData['htmlContent'] ?>

                <div class="external-link">
                    <p><?=htmlspecialchars($pageData['linkText'] ?? 'Verein') ?></p>
                    <ul>
                        <li><a class="facebook" href="https://www.facebook.com/CosplayAtelier.ch/" target="_blank">Facebook</a></li>
                        <li><a class="instagram" href="https://www.instagram.com/cosplayatelier.ch/" target="_blank">Instagram</a></li>
                        <li><a class="discord" href="https://cosplay-atelier.ch/discord" target="_blank">Discord</a></li>
                        <li><a class="youtube" href="https://www.youtube.com/channel/UCG9ZdVCMMVPZEbnB3jK1YDg/" target="_blank">YouTube</a></li>
                    </ul>
                </div>
            </div>
        </article>
    </section>

</main>

<?php
    include "includes/footer.php";
?>
</html>
