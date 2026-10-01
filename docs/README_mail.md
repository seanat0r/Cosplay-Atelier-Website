# Mailversand konfigurieren

Die Datei `../.env` liegt im Projektverzeichnis ausserhalb von `public/` und wird nicht in Git eingecheckt. Servervariablen haben Vorrang vor den Werten in `.env`. Der Versandmodus wird mit `MAIL_AUTH_MODE` gewählt. Falls dieser Eintrag fehlt, wird der bisherige Eintrag `MAIL_AUTH=false` als Passwortmodus erkannt.

## SMTP mit Benutzername und Passwort, zum Beispiel Proton

```dotenv
MAIL_AUTH_MODE=password
MAIL_EMAIL=absender@example.org
MAIL_USERNAME=absender@example.org
MAIL_PASSWORD=SMTP-Passwort-oder-Token
MAIL_RECIPIENT=empfaenger@example.org
MAIL_HOST=smtp.protonmail.ch
MAIL_PORT=587
MAIL_ENCRYPTION=starttls
```

`MAIL_USERNAME` ist optional und fällt auf `MAIL_EMAIL` zurück. Ohne `MAIL_RECIPIENT` wird an `MAIL_EMAIL` gesendet. `MAIL_ENCRYPTION=starttls` passt zu Port 587; für Anbieter mit implizitem TLS auf Port 465 verwende `MAIL_ENCRYPTION=smtps`.

## Microsoft 365 / Exchange Online mit OAuth2

```dotenv
MAIL_AUTH_MODE=oauth2
MAIL_EMAIL=absender@example.org
MAIL_RECIPIENT=empfaenger@example.org
MAIL_HOST=smtp.office365.com
MAIL_PORT=587
MAIL_ENCRYPTION=starttls
MAIL_OAUTH_TENANT=deine-tenant-id
MAIL_OAUTH_CLIENT_ID=deine-client-id
MAIL_OAUTH_CLIENT_SECRET=dein-client-secret
```

Dieser Modus verwendet OAuth2 **Client Credentials**. Die Anwendung holt für den Versand selbstständig ein Zugriffstoken. `MAIL_PASSWORD`, `MAIL_AUTH_TYPE` und `MAIL_AUTH_TOKEN` werden dabei nicht verwendet. Die unter `MAIL_EMAIL` angegebene Adresse muss eine freigegebene Exchange-Online-Mailbox sein.

In Microsoft Entra muss eine Anwendung registriert sein. Richte für die Anwendung SMTP-Zugriff auf die Absender-Mailbox ein. Microsoft beschreibt dafür [SMTP-Onboarding mit App RBAC](https://learn.microsoft.com/en-us/exchange/client-developer/legacy-protocols/smtp-app-rbac-onboarding) und [SMTP-Authentifizierung mit OAuth2](https://learn.microsoft.com/en-us/exchange/client-developer/legacy-protocols/how-to-authenticate-an-imap-pop-smtp-application-by-using-oauth). Der Exchange-Administrator muss die Anwendung und ihre Berechtigungen einrichten; Code und `../.env` allein reichen nicht aus.
