<?php
/* ============================================================
   send_mail.php — Mitgliedsantrag Mailversand
   Sicherheitsfeatures:
   - Honeypot-Schutz
   - Rate Limiting (Session)
   - Input Validierung & Sanitisierung
   - CSRF-Schutz (Origin Check)
   - Kein Header-Injection
   - Nur POST erlaubt
   ============================================================ */

use JetBrains\PhpStorm\NoReturn;

session_start();

/* ============================================================
   KONFIGURATION — hier anpassen
   ============================================================ */
const EMPFAENGER_EMAIL = 'vorstand@cosplay-atelier.ch';
const EMPFAENGER_NAME = 'Cosplay-Atelier Vorstand';
const ABSENDER_DOMAIN = 'cosplay-atelier.ch';
const RATE_LIMIT = 3;    // max. Formulare pro Stunde
const ERLAUBTE_ORIGIN = 'https://cosplay-atelier.ch'; // Produktions-URL


/* ============================================================
   HILFSFUNKTIONEN
   ============================================================ */

// Gibt eine JSON-Antwort zurück und beendet das Script
#[NoReturn]
function antwort(bool $erfolg, string $nachricht): void
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['erfolg' => $erfolg, 'nachricht' => $nachricht]);
    exit;
}

// Bereinigt einen String — entfernt gefährliche Zeichen
function bereinigen(string $wert): string
{
    return htmlspecialchars(strip_tags(trim($wert)), ENT_QUOTES, 'UTF-8');
}

// Prüft, ob ein String nur erlaubte Zeichen enthält (kein Header-Injection)
function keineZeilenumbrueche(string $wert): bool
{
    return !preg_match('/[\r\n]/', $wert);
}


/* ============================================================
   SICHERHEITSPRÜFUNGEN
   ============================================================ */

// 1. Nur POST erlaubt
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    antwort(false, 'Methode nicht erlaubt.');
}

// 2. Origin-Prüfung (CSRF-Schutz)
//! Im if ABSENDER_DOMAIN ersetzten durch ERLAUBTE_DOMAIN!
$origin = $_SERVER['HTTP_ORIGIN'] ?? $_SERVER['HTTP_REFERER'] ?? '';
if (!str_contains($origin, ABSENDER_DOMAIN)) {
    http_response_code(403);
    antwort(false, 'Ungültige Herkunft.');
}

// 3. Honeypot — wenn das versteckte Feld ausgefüllt wurde, ist es ein Bot
if (!empty($_POST['website'])) {
    // Stille Ablehnung — Bot denkt, es hat funktioniert
    antwort(true, 'Antrag wurde gesendet.');
}

// 4. Rate Limiting — max. 3 Sendungen pro Stunde pro Session
$jetzt = time();
if (!isset($_SESSION['formular_sends'])) {
    $_SESSION['formular_sends'] = [];
}
// Alte Einträge (älter als 1 Stunde) entfernen
$_SESSION['formular_sends'] = array_filter(
    $_SESSION['formular_sends'],
    fn($zeit) => ($jetzt - $zeit) < 3600
);
if (count($_SESSION['formular_sends']) >= RATE_LIMIT) {
    http_response_code(429);
    antwort(false, 'Zu viele Anfragen. Bitte warte eine Stunde und versuche es erneut.');
}


/* ============================================================
   EINGABEN VALIDIEREN
   ============================================================ */

$fehler = [];

// Name
$name = bereinigen($_POST['name'] ?? '');
if (empty($name) || mb_strlen($name) < 2 || mb_strlen($name) > 100) {
    $fehler[] = 'Name ist ungültig.';
}
if (!keineZeilenumbrueche($name)) {
    $fehler[] = 'Name enthält ungültige Zeichen.';
}

// Adresse
$adresse = bereinigen($_POST['adresse'] ?? '');
if (empty($adresse) || mb_strlen($adresse) > 200) {
    $fehler[] = 'Adresse ist ungültig.';
}

// PLZ und Ort
$plz = bereinigen($_POST['plz'] ?? '');
if (empty($plz) || mb_strlen($plz) > 100) {
    $fehler[] = 'PLZ/Ort ist ungültig.';
}

// Geburtsdatum
$geburtsdatum_raw = $_POST['geburtsdatum'] ?? '';
$geburtsdatum_formatiert = 'Nicht angegeben / Ungültig';
$geburtsdatum = DateTime::createFromFormat('Y-m-d', $geburtsdatum_raw);
if (!$geburtsdatum || $geburtsdatum->format('Y-m-d') !== $geburtsdatum_raw) {
    $fehler[] = 'Geburtsdatum ist ungültig.';
} else {
    $alter = $geburtsdatum->diff(new DateTime())->y;
    if ($alter < 5 || $alter > 120) {
        $fehler[] = 'Geburtsdatum ist unrealistisch.';
    }
    $geburtsdatum_formatiert = $geburtsdatum->format('d.m.Y');
}

// Telefon
$telefon = bereinigen($_POST['telefon'] ?? '');
if (!preg_match('/^[\d\s+\-()]{7,20}$/', $telefon)) {
    $fehler[] = 'Telefonnummer ist ungültig.';
}

// E-Mail
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
if (!$email || mb_strlen($email) > 254) {
    $fehler[] = 'E-Mail-Adresse ist ungültig.';
}
if (!keineZeilenumbrueche($email)) {
    $fehler[] = 'E-Mail enthält ungültige Zeichen.';
}

// Datenschutz Checkbox
if (empty($_POST['datenschutz'])) {
    $fehler[] = 'Datenschutzerklärung muss akzeptiert werden.';
}

// Fehler zurückgeben, falls vorhanden
if (!empty($fehler)) {
    http_response_code(422);
    antwort(false, implode(' ', $fehler));
}


/* ============================================================
   MAIL SENDEN
   ============================================================ */

$betreff = 'Neuer Mitgliedsantrag: ' . $name;

// Plain-Text Mail — kein HTML, sicherer
$nachricht = "NEUER MITGLIEDSANTRAG\n";
$nachricht .= str_repeat('=', 40) . "\n\n";
$nachricht .= "Name:          $name\n";
$nachricht .= "Adresse:       $adresse\n";
$nachricht .= "PLZ / Ort:     $plz\n";
$nachricht .= "Geburtsdatum:  $geburtsdatum_formatiert\n";
$nachricht .= "Telefon:       $telefon\n";
$nachricht .= "E-Mail:        $email\n\n";
$nachricht .= str_repeat('=', 40) . "\n";
$nachricht .= "Gesendet am: " . date('d.m.Y H:i') . "\n";

// Mail-Header — sicher aufgebaut, kein Injection möglich
$headers = "From: noreply@" . ABSENDER_DOMAIN . "\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";

$gesendet = mail(EMPFAENGER_EMAIL, $betreff, $nachricht, $headers);

if (!$gesendet) {
    http_response_code(500);
    antwort(false, 'Fehler beim Senden. Bitte versuche es später erneut oder kontaktiere uns direkt.');
}

// Rate Limit Eintrag hinzufügen
$_SESSION['formular_sends'][] = $jetzt;

antwort(true, 'Dein Mitgliedsantrag wurde erfolgreich gesendet! Wir melden uns bald bei dir.');