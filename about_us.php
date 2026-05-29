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

        <section class="about-section">
            <article class="about-article committee">
                <figure>
                    <img src="/assets/img/committee.jpeg" alt="Vorstand">
                    <figcaption>Von links nach rechts:<br>Kevin, Pascal, Florian, Astrid & Dimija</figcaption>
                </figure>
                <div class="content-text-about-us">
                    <h2>Unser Vorstand</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad blanditiis commodi cumque id iste
                        maxime omnis quidem soluta ullam vitae. Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                        Commodi dicta dignissimos error laboriosam magnam quaerat sequi similique vero voluptatibus!
                        Quam. </p>
                </div>
            </article>

            <article class="about-article mascot reverse-layout">
                <figure>
                    <img src="/assets/img/Kiba_Gaming.png" alt="Unser Maskottchen beim Gamen">
                    <figcaption>Unser Kiba!</figcaption>
                </figure>
                <div class="content-text-about-us">
                    <h2>Unser Maskottchen Kiba</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus aliquid commodi debitis deserunt
                        dolores dolorum enim error est facere illum ipsum, laborum nemo non porro praesentium quae quia?
                        Adipisci aliquam dicta eligendi impedit laboriosam modi omnis recusandae reiciendis sunt
                        voluptatum.</p>
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
