<?php
declare(strict_types=1);
$pageTitle = "Cosplay-Atelier | Kontakt & Mitgliedschaft";
session_start();
// Old session data
$formFailed = $_SESSION["form_error"] ?? null;
$formCode = $_SESSION["form_error_status"] ?? null;
$formOldData = $_SESSION["form_old"] ?? null;

$formSuccess = $_SESSION["form_success"] ?? null;

unset($_SESSION["form_error"], $_SESSION["form_error_status"], $_SESSION["form_old"], $_SESSION["form_success"]);

?>
<!DOCTYPE html>
<html lang="de">
<?php include BASE_PATH . '/includes/head.php'; ?>
<body>
<?php include BASE_PATH . '/includes/header.php'; ?>

<main>
    <?php
    include BASE_PATH . '/includes/hero.php';
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
                        <span class="mail-icon" aria-hidden="true">✉</span> <?= htmlspecialchars($pageData['emailAllgemein'] ?? 'Keine E-Mail hinterlegt') ?>
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
                        <span class="mail-icon" aria-hidden="true">✉</span>
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
                            <a href="mailto:kontakt@cosplay-atelier.ch"><span class="mail-icon" aria-hidden="true">✉</span>kontakt@cosplay-atelier.ch</a> oder wende dich direkt an eines
                            unserer Mitglieder.</p>
                        <p class="greeting-text">Wir freuen uns auf dich!</p>
                    </div>

                    <figure class="mascot-container">
                        <img src="/assets/img/Kiba_Mask.png" alt="Cosplay-Atelier Maskottchen" class="mascot-img">
                    </figure>
                </div>
            </article>

            <article class="form-container">
                <form action="/send_mail" method="POST" class="mitglied-form">
                    <div class="form-group">
                        <label for="name">Name und Vorname<span class="info-symbol">*</span>:</label>
                        <input type="text" id="name" name="name" value="<?= $formOldData["name"] ?? "" ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="adresse">Adresse<span class="info-symbol">*</span>:</label>
                        <input type="text" autocomplete="street-address" id="adresse" name="adresse" value="<?= $formOldData["adresse"] ?? "" ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="plz">PLZ und Ort<span class="info-symbol">*</span>:</label>
                        <input type="text" autocomplete="postal-code"  id="plz" name="plz" value="<?= $formOldData["plz"] ?? "" ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="birthdate">Geburtsdatum<span class="info-symbol">*</span>:</label>
                        <input type="date" autocomplete="bday" id="birthdate" name="birthdate" value="<?= $formOldData["birthdate"] ?? "" ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="telefon">Telefonnummer<span class="info-symbol">*</span>:</label>
                        <input type="tel" autocomplete="tel" id="telefon" name="telefon" value="<?= $formOldData["telefon"] ?? "" ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">E-Mail-Adresse<span class="info-symbol">*</span>:</label>
                        <input type="email" autocomplete="email" id="email" name="email" value="<?= $formOldData["email"] ?? "" ?>" required>
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

    <?php
    $hadFormSuccess = null;
    if (!empty($formFailed) && !empty($formCode) && !empty($formOldData)) {
        $hadFormSuccess = false;
    } else if (!empty($formSuccess)) {
        $hadFormSuccess = true;
    }

    if ($hadFormSuccess !== null):
        if ($hadFormSuccess): ?>
            <section class="form-feedback form-success" role="status" aria-live="polite">
                <div class="feedback-icon" aria-hidden="true">✓</div>
                <div class="feedback-body">
                    <h2>Formular erfolgreich geschickt!</h2>
                    <p>Wir melden uns in ein paar Tagen bei dir!</p>
                </div>
            </section>
        <?php else: ?>
            <section class="form-feedback form-failed" role="alert" aria-live="assertive">
                <div class="feedback-icon" aria-hidden="true">!</div>
                <div class="feedback-body">
                    <h2>Formular konnte nicht geschickt werden!</h2>
                    <p>
                        Schau doch nochmal auf deine Daten oder versuche es später nochmal!<br>
                        Falls es immer noch nicht funktioniert, sende uns deine Nachricht direkt an:
                        <a class="feedback-mail-link" href="mailto:kontakt@cosplay-atelier.ch">
                            <span class="mail-icon" aria-hidden="true">✉</span>
                            <span class="mail-text">kontakt@cosplay-atelier.ch</span>
                        </a>
                    </p>

                    <details class="feedback-details">
                        <summary>
                            <span class="summary-icon" aria-hidden="true">ℹ</span>
                            <span>Technische Details anzeigen</span>
                            <span class="chevron" aria-hidden="true">▾</span>
                        </summary>
                        <ul>
                            <li><strong>HTTP Code:</strong> <code><?= htmlspecialchars((string) $formCode) ?></code></li>
                            <li><strong>Nachricht:</strong> <code><?= htmlspecialchars($formFailed) ?></code></li>
                        </ul>
                    </details>
                </div>
            </section>
        <?php
        endif;
    endif;
    ?>

</main>

<?php
include BASE_PATH . '/includes/footer.php';
?>
</body>
</html>
