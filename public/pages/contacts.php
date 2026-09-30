<?php
declare(strict_types=1);
$pageTitle = "Cosplay-Atelier | Kontakt & Mitgliedschaft";
?>
<!DOCTYPE html>
<html lang="de">
<?php include BASE_PATH . '/includes/head.php'; ?>
<body>
<?php include BASE_PATH . '/includes/header.php'; ?>

<main>
    <?php
    include BASE_PATH . '/includes/hero.php';
    require_once BASE_PATH . '/src/MarkdownParser.php';
    $dataPath = BASE_PATH . '/content/contacts/contacts.md';
    $pageData = $this->parser->parseFile($dataPath);
    ?>

    <section class="sub-nav">
        <nav>
            <ul>
                <li><a href="#contacts">Kontakt | Impressum</a></li>
                <li><a href="#forms">Mitglied werden!</a></li>
            </ul>
        </nav>
    </section>

    <section class="contacts-section" id="contacts">
        <h2>Kontakt | Impressum</h2>
        <div class="contact-grid">

            <div class="contact-card">
                <h3>Allgemein</h3>
                <p>Hast du allgemeine Fragen an den Verein? Melde dich jederzeit bei uns.</p>
                <p><strong>E-Mail:</strong><br>
                    <a href="mailto:<?= htmlspecialchars($pageData['emailAllgemein'] ?? '') ?>">
                        <?= htmlspecialchars($pageData['emailAllgemein'] ?? 'Keine E-Mail hinterlegt') ?>
                    </a></p>
            </div>

            <div class="contact-card">
                <h3>Bankverbindung</h3>
                <p><strong><?= htmlspecialchars($pageData['bankName'] ?? 'Bank') ?></strong><br>
                    <?= htmlspecialchars($pageData['bankAdresse'] ?? 'Bank') ?><br>
                    <?= htmlspecialchars($pageData['bankStrasse'] ?? 'Bank') ?></p>
                <p><strong>IBAN:</strong> <?= htmlspecialchars($pageData['iban'] ?? '') ?></p>
                <p><strong>Zu Gunsten von:</strong><br>
                    Cosplay-Atelier<br>
                    <?= htmlspecialchars($pageData['cosplayStrasse'] ?? 'Bank') ?><br>
                    <?= htmlspecialchars($pageData['cosplayAdresse'] ?? 'Bank') ?></p>
            </div>

            <div class="contact-card">
                <h3>Webseitenbetreiber</h3>
                <p>Bei technischen Fragen zur Webseite erreichst du unseren Webmaster.</p>
                <p><strong>E-Mail:</strong><br>
                    <a href="mailto:<?= htmlspecialchars($pageData['emailAdmin'] ?? '') ?>">
                        <?= htmlspecialchars($pageData['emailAdmin'] ?? '') ?>
                    </a></p>
                <p><strong>Tel:</strong> <?= htmlspecialchars($pageData['telefonAdmin'] ?? '') ?></p>
            </div>
        </div>
    </section>

    <section class="membership-section" id="forms">
        <div class="membership-layout">
            <article class="form-info">
                <h2>Mitglied werden!</h2>
                <?= $pageData['htmlContent'] ?>

                <div class="cta-wrapper">
                    <div class="cta-text">
                        <p><strong>Interessiert?</strong></p>
                        <p>Dann melde dich mit dem unten stehenden Formular, über unsere E-Mail
                            (<a href="mailto:kontakt@cosplay-atelier.ch">kontakt@cosplay-atelier.ch</a>) oder wende dich direkt an eines
                            unserer Mitglieder.</p>
                        <p class="greeting-text">Wir freuen uns auf dich!</p>
                    </div>

                    <figure class="mascot-container">
                        <img src="../assets/img/Kiba_Mask.png" alt="Cosplay-Atelier Maskottchen" class="mascot-img">
                    </figure>
                </div>
            </article>

            <article class="form-container">
                <form action="/send_mail" method="POST" class="mitglied-form">
                    <div class="form-group">
                        <label for="name">Name und Vorname<span class="info-symbol">*</span>:</label>
                        <input type="text" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="adresse">Adresse<span class="info-symbol">*</span>:</label>
                        <input type="text" autocomplete="street-address" id="adresse" name="adresse" required>
                    </div>

                    <div class="form-group">
                        <label for="plz">PLZ und Ort<span class="info-symbol">*</span>:</label>
                        <input type="text" autocomplete="postal-code"  id="plz" name="plz" required>
                    </div>

                    <div class="form-group">
                        <label for="birthdate">Geburtsdatum<span class="info-symbol">*</span>:</label>
                        <input type="date" autocomplete="bday" id="birthdate" name="birthdate" required>
                    </div>

                    <div class="form-group">
                        <label for="telefon">Telefonnummer<span class="info-symbol">*</span>:</label>
                        <input type="tel" autocomplete="tel" id="telefon" name="telefon" required>
                    </div>

                    <div class="form-group">
                        <label for="email">E-Mail-Adresse<span class="info-symbol">*</span>:</label>
                        <input type="email" autocomplete="email" id="email" name="email" required>
                    </div>

                    <div class="form-group checkbox">
                        <input type="checkbox" id="datenschutz" name="datenschutz" required>
                        <label for="datenschutz">
                            Ich habe die <a href="/dsgvo" class="checkbox-dsgvo" target="_blank">Datenschutzerklärung</a>
                            gelesen und bin mit der Verarbeitung meiner Daten einverstanden.<span class="info-symbol">*</span>
                        </label>
                    </div>

                    <input type="text" name="website" style="display:none;">

                    <button type="submit" class="submit-btn">Mitgliedsantrag senden</button>
                    <p class="info">Felder mit <strong class="info-symbol">*</strong> markiert sind Pflichtfelder.</p>
                </form>
            </article>
        </div>
    </section>

</main>

<?php
include BASE_PATH . '/includes/footer.php';
?>
</body>
</html>
