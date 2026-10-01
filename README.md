# Vereins-Webseite: Anleitung für Inhalte

## Projektaufbau und Hosting

`public/` ist das Webverzeichnis. Die Domain muss auf diesen Ordner zeigen; nur seine Dateien sollen direkt über den Browser erreichbar sein. Die PHP-Seiten und `public/assets/` liegen darin. `content/` enthält die Markdown-Texte, `includes/` die gemeinsamen Seitenteile, `src/` die Verarbeitung und `lib/` die Markdown-Bibliothek. Diese Ordner sowie `config.php` liegen ausserhalb des Webverzeichnisses.

Lokal starten: `php -S localhost:8000 -t public` im Projektordner. Auf Hostpoint muss entweder das Document Root auf `public/` zeigen oder der Inhalt von `public/` in das dort konfigurierte Document Root kopiert werden, während die internen Ordner eine Ebene darüber liegen. In letzterem Fall müssen die relativen PHP-Pfade zur tatsächlichen Verzeichnisstruktur passen.

`content/`, `public/assets/img/`, `src/send_mail.php` und `config.php` sind nicht versioniert. Diese Dateien müssen beim Bereitstellen separat mitkopiert werden.

## 1. Texte und Inhalte ändern
Die meisten Texte der Webseite können selbstständig angepasst werden. Alle editierbaren Daten liegen im Ordner 
`content/`. Markdown-Formatierung kann nur unterhalb der 2. der 3 Striche (`---`) verwendet werden!

### Bereich: Über uns (`content/about_us/`)
* `kiba.md`: Enthält den Text für unser Maskottchen.
* `vorstand.md`: Enthält den Text für den Vorstand.
* *Regel:* Oben bei `title:` steht die Überschrift. Der eigentliche Text kommt unter die drei Striche (`---`). Der
Vorstand hat noch `bildbeschreibung:` Um die Namen anzupassen

### Bereich: Kontakt & Impressum (`content/contacts/contacts.md`)
Öffnet diese Datei, um die Stammdaten des Vereins anzupassen. **Wichtig:** Ändert nur den Text *nach* dem Doppelpunkt 
und lasst die Begriffe (z. B. `iban:`) unverändert!
* `emailAllgemein`: Kontakt-E-Mail des Vereins
* `emailAdmin`: E-Mail des Webmasters
* `telefonAdmin`: Telefonnummer des Webmasters
* `bankName` / `bankAdresse` / `bankStrasse`: Daten der Bank
* `iban`: IBAN-Nummer des Vereins
* `cosplayAdresse` / `cosplayStrasse`: Offizielle Vereinsadresse
* *Haupttext:* Alles, was unter den drei Strichen (`---`) steht, wird als Text für "Mitglied werden" angezeigt. 
(Der Abschnitt "Interessiert?" ganz unten auf der Webseite ist fest verbaut und muss hier nicht eingetippt werden).

### Bereich: Startseite (`content/index/index.md`)
* `title`: Der Titel des Fliesstext.
* `linkText`: Der Aufruf-Text, der direkt über den Social-Media-Buttons steht.
* *Haupttext:* Der Fliesstext unterhalb von `---`.

---

## 2. Fotogalerie
Alle Bilder für die Galerie werden in den Ordner `public/assets/img/gallery/` hochgeladen. Die Webseite zeigt automatisch immer
die neuesten 12 Fotos an. Beim Klick auf "Weitere Bilder laden" folgen die nächsten 12.

**Bitte beachtet folgende Regeln für Galeriebilder:**
1.  **Das Format:** Nutzt quadratische Bilder.
2.  **Der Dateityp:** Erlaubt sind nur `.webp`, `.svg`, `.jpeg` / `.jpg` und `.png`. (Nutzt `.png` bitte nur, wenn das 
Bild einen transparenten Hintergrund hat. `.webp` ist für normale Fotos die beste Wahl für schnelle Ladezeiten).
3.  **Die Benennung (Extrem wichtig für die richtige Reihenfolge):**
    Benennt die Dateien strikt nach diesem Schema: `<YYYY-MM-DD>-<kurzeBeschreibung>.<format>`
    * `YYYY`: Das Jahr (vierstellig)
    * `MM`: Der Monat (zweistellig, z. B. `05` statt `5`)
    * `DD`: Der Tag (zweistellig)
    * `<kurzeBeschreibung>`: Eine kleine Beschreibung des Fotos
    * `<format>`: Die Dateiendung des Bildes (z. B. `webp` oder `png`)
    * *Beispiel:* `2026-05-14-fantasy-basel.webp`

---

## 3. News-Artikel schreiben
Um einen neuen News-Beitrag zu erstellen, müsst ihr eine neue Textdatei anlegen. Wir empfehlen dafür das kostenlose 
Programm **Obsidian**, da es sehr einsteigerfreundlich ist.

### Schritt 1: Datei erstellen
Erstellt eine neue Datei im Ordner `content/news/`. Der Dateiname bestimmt die Sortierung auf der Webseite. 
Nutzt auch hier das Datums-Format, am Ende steht jedoch immer die Endung `.md` für Markdown:
`JAHR-MONAT-TAG-titel-des-beitrags.md` (z. B. `2026-10-01.md`).

### Schritt 2: Aufbau der Datei
Jede News-Datei muss zwingend mit den Metadaten (Titel und Datum) starten, gefolgt von drei Bindestrichen. 
Hier ist ein exaktes Beispiel, wie der Inhalt aussehen muss:

```markdown
---
title: Unser Wochenende an der Fantasy Basel!
date: 01.06.2026
---
Es war ein absolut grossartiges Wochenende! Wir haben unglaublich viele tolle Cosplayer getroffen und unser 
Kiba-Maskottchen kam riesig an.

Hier ist ein kleiner Eindruck von unserem Gruppenfoto:

![Die ganze Truppe in Basel](/assets/img/news/basel-foto.webp)

Danke an alle, die an unserem Stand vorbeigeschaut haben. Wir sehen uns nächstes Jahr!
```

### Schritt 3: Bilder in News einfügen

Wenn ihr ein Bild im News-Beitrag anzeigen wollt, ladet das Foto in den Ordner `public/assets/img/news/` hoch. Im Text bettet ihr das Bild dann mit dieser Schreibweise ein:

`![Hier eine kurze Bildbeschreibung](/assets/img/news/name-des-fotos.webp)`

## FTP

Beim Hochladen auf Hostpoint ist `public/` das Webverzeichnis. Die internen Ordner und die Konfiguration dürfen nicht im Document Root liegen.

---

## Error Code

- `#001`: Mail angaben, sind leer (nicht passwort)
- `#002`: Mail passwort ist leer
- `#003`: Microsoft OAUTH2 konnte sich nicht initialisieren.