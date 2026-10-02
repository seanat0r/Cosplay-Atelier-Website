<?php
declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/MicrosoftOAuthTokenProvider.php';

class Mail
{
    private string $recipientName = "Cosplay-Atelier Vorstand";

    private string $rateLimitFilePath = BASE_PATH . "/var/rateLimit.json";

    /**
     * Global Rate limit per Minute
     * @var int
     */
    private int $globalRateLimitPerMinute = 5;

    /**
     * Global time window for Rate limit
     * @var int
     */
    private int $windowInSeconds = 60;

    private array $timestamps = [];
    private string $allowedOrigin = "https://cosplay-atelier.ch";

    public function __construct( private readonly PHPMailer $mailer = new PHPMailer(true))
    {
    }

    private function rateLimit(Response $response): bool|Response
    {
        $dir = dirname($this->rateLimitFilePath);
        if (!is_dir($dir) && !@mkdir($dir, 0755, true) && !is_dir($dir)) {
            return $this->reportError($response, 500, 'Internal Server Error');
        }

        $file = @fopen($this->rateLimitFilePath, 'c+');
        if ($file === false) {
            return $this->reportError($response, 500, 'Internal Server Error');
        }

        $locked = false;
        try {
            $locked = flock($file, LOCK_EX);
            if (!$locked) {
                throw new RuntimeException('Cannot lock rate limit file');
            }

            rewind($file);
            $json = stream_get_contents($file);
            if ($json === false) {
                throw new RuntimeException('Cannot read rate limit file');
            }

            $timestamps = $json === '' ? [] : json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            if (!is_array($timestamps)) {
                throw new RuntimeException('Invalid rate limit file');
            }

            $now = time();
            $this->timestamps = array_values(array_filter(
                $timestamps,
                fn($time) => is_int($time) && $time <= $now && ($now - $time) < $this->windowInSeconds
            ));

            if (count($this->timestamps) >= $this->globalRateLimitPerMinute) {
                return $this->reportError($response, 429, 'Too many requests');
            }

            $this->timestamps[] = $now;
            $updated = json_encode($this->timestamps, JSON_THROW_ON_ERROR);
            rewind($file);
            if (!ftruncate($file, 0) || fwrite($file, $updated) !== strlen($updated) || !fflush($file)) {
                throw new RuntimeException('Cannot write rate limit file');
            }

            return true;
        } catch (Throwable $e) {
            return $this->reportError($response, 500, 'Internal Server Error');
        } finally {
            if ($locked) {
                flock($file, LOCK_UN);
            }
            fclose($file);
        }
    }

    /**
     * Sends an error message back (200 for honeypots)
     * @param Response $response
     * @param int $code http code
     * @param string $message error message
     * @param bool $isHoneypot if true, all other parameters has no effects
     * @return Response http response
     */
    private function reportError(Response $response, int $code, string $message, bool $isHoneypot = false): Response
    {
        // bots gets a rest api
        if ($isHoneypot) {
            $response->getBody()->write(json_encode(["Success" => true, "Message" => "Your mail was send!"]));
            return $response
                ->withHeader("Content-Type", "application/json")
                ->withStatus(200);
        }
        $_SESSION['form_error'] = $message;
        $_SESSION['form_error_status'] = $code;
        $_SESSION['form_old'] = [];

        // limit the size of this session and set the value (if its to high set it to null)
        foreach (['name' => 400, 'adresse' => 800, 'plz' => 800, 'birthdate' => 10, 'telefon' => 80, 'email' => 254] as $field => $maxBytes) {
            $value = $_POST[$field] ?? null;
            $_SESSION['form_old'][$field] = is_string($value) && strlen($value) <= $maxBytes ? $value : null;
        }

        return $response
            ->withHeader('Location', "/contacts#forms")
            ->withStatus(303);


    }

    /**
     * @param mixed $input value to clean
     * @return string clean string
     */
    private function cleanStringInput(mixed $input): string
    {
        if (!is_string($input)) {
            return '';
        }

        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Checks if the string contains \r or \n
     * @param string $input value to check
     * @return bool true if the string contains \r or \n
     */
    private  function hasStringBraks(string $input): bool {
        return !preg_match('/[\r\n]/', $input);
}

    /**
     * Check the origin from the sender
     * @param Response $response
     * @return bool|Response true if successfull
     */
    private function originCheck(Response $response): bool|Response
    {
        $origin = $_SERVER['HTTP_ORIGIN'] ?? $_SERVER['HTTP_REFERER'] ?? '';
        $originHost = parse_url($origin, PHP_URL_HOST);
        $allowedHost = parse_url($this->allowedOrigin, PHP_URL_HOST);
        $requestHost = parse_url('http://' . ($_SERVER['HTTP_HOST'] ?? ''), PHP_URL_HOST);
        $localRequest = in_array($requestHost, ['localhost', '127.0.0.1'], true);

        if ($originHost !== $allowedHost && !($localRequest && $originHost === $requestHost)) {
            return $this->reportError($response, 303, "Unknown Origin");
        }
        return true;
    }

    /**
     * Honeypot check from forms
     * @param Response $response
     * @return bool|Response true if successfull
     */
    private function honeypotCheck(Response $response): bool|Response
    {
        if (!empty($_POST['website'])) {
            return $this->reportError($response, 200, "", true);
        }
        return true;
    }

    /**
     * Validate the Body from $_POST
     * @param Response $response
     * @return array|Response if successfull return an arry with the body elements
     */
    private function inputValidationCheck(Response $response): array|Response
    {
        $failed = [];

        // name (first- and lastname)
        $name = $this->cleanStringInput($_POST['name'] ?? null);
        if (empty($name) || mb_strlen($name) < 3 || mb_strlen($name) > 100) {
            $failed[] = "name is invalid";
        }
        if (!$this->hasStringBraks($name)) {
            $failed[] = "name has invalid characters";
        }

        // plz
        $plz = $this->cleanStringInput($_POST['plz'] ?? null);
        if (empty($plz) || mb_strlen($plz) < 4 || mb_strlen($plz) > 200) {
            $failed[] = "plz/city is invalid";
        }

        //address
        $address = $this->cleanStringInput($_POST['adresse'] ?? null);
        if (empty($address) || mb_strlen($address) > 200) {
            $failed[] = "address is invalid";
        }
        if (!$this->hasStringBraks($address)) {
            $failed[] = "address has invalid characters";
        }

        // birthday
        $birthday_raw = $this->cleanStringInput($_POST['birthdate'] ?? null);
        $birthday = DateTime::createFromFormat('Y-m-d', $birthday_raw);
        if (!$birthday || $birthday->format('Y-m-d') !== $birthday_raw) {
            $failed[] = "birthday is invalid";
        } else {
            $age = $birthday->diff(new DateTime())->y;
            if ($age < 5 || $age > 120) {
                $failed[] = "birthday is unrealistic";
            }
            if ($birthday > new DateTime('today')) {
                $failed[] = "birthday is unrealistic";
            }
            $birthday = $birthday->format('d.m.Y');
        }

        //tel
        $phone = $this->cleanStringInput($_POST['telefon'] ?? null);
        if (!preg_match('/^[\d\s+\-()]{7,20}$/', $phone)) {
            $failed[] = 'Phone number is invalid.';
        }

        //email
        $emailInput = $_POST['email'] ?? '';
        $email = is_string($emailInput) ? filter_var($emailInput, FILTER_VALIDATE_EMAIL) : false;
        if (!$email || mb_strlen($email) > 254) {
            $failed[] = "email is invalid";
        }
        if (is_string($email) && !$this->hasStringBraks($email)) {
            $failed[] = "email has invalid characters";
        }

        //dsgvo
        if (empty($_POST['datenschutz'])) {
            $failed[] = "Datenschutz was not checked.";
        }

        if (!empty($failed)) {
            return $this->reportError($response, 400, implode(" ", $failed));
        }

        return [
            "name" => $name,
            "plz" => $plz,
            "address" => $address,
            "birthday" => $birthday,
            "phone" => $phone,
            "email" => $email,
        ];
    }

    /**
     * Sends an email
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function sendMail(Request $request, Response $response, array $args): Response
    {
        ini_set('session.use_strict_mode', '1');
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => false,      // Production over HTTPS (set to true)
            'httponly' => true,
            'samesite' => 'strict',
        ]);
        session_start();

        $originResult = $this->originCheck($response);
        if ($originResult instanceof Response) {
            return $originResult;
        }

        $honeypotResult = $this->honeypotCheck($response);
        if ($honeypotResult instanceof Response) {
            return $honeypotResult;
        }

        $data = $this->inputValidationCheck($response);
        if ($data instanceof Response) {
            return $data;
        }

        $rateLimitResult = $this->rateLimit($response);
        if ($rateLimitResult instanceof Response) {
            return $rateLimitResult;
        }

        try {
            $localEnv = is_file(BASE_PATH . '/.env')
                ? parse_ini_file(BASE_PATH . '/.env', false, INI_SCANNER_RAW)
                : [];
            if (!is_array($localEnv)) {
                $localEnv = [];
            }

            $setting = static function (string $key, string $default = '') use ($localEnv): string {
                $value = getenv($key);
                return $value !== false ? $value : (string) ($localEnv[$key] ?? $default);
            };

            $smtpEmail = trim($setting('MAIL_EMAIL'));
            $smtpUsername = trim($setting('MAIL_USERNAME', $smtpEmail));
            $smtpPassword = $setting('MAIL_PASSWORD');
            $smtpPort = filter_var(trim($setting('MAIL_PORT', '587')), FILTER_VALIDATE_INT,
                ['options' => ['min_range' => 1, 'max_range' => 65535]]);
            $smtpHost = trim($setting('MAIL_HOST'));
            $encryption = strtolower(trim($setting('MAIL_ENCRYPTION', 'starttls')));
            $authMode = strtolower(trim($setting('MAIL_AUTH_MODE', $setting('MAIL_AUTH', 'password'))));

            $smtpEncryption = match ($encryption) {
                'starttls', 'tls' => PHPMailer::ENCRYPTION_STARTTLS,
                'smtps', 'ssl' => PHPMailer::ENCRYPTION_SMTPS,
                default => null,
            };
            $authMode = match ($authMode) {
                'password', 'basic', 'false', '0' => 'password',
                'oauth2', 'xoauth2', 'true', '1' => 'oauth2',
                default => '',
            };

            if (!filter_var($smtpEmail, FILTER_VALIDATE_EMAIL) || $smtpUsername === '' ||
                $smtpHost === '' || $smtpPort === false || $smtpEncryption === null || $authMode === '') {
                return $this->reportError($response, 500, 'Internal Server Error #001');
            }

            if ($authMode === 'password' && $smtpPassword === '') {
                return $this->reportError($response, 500, 'Internal Server Error #002');
            }

            $tenant = trim($setting('MAIL_OAUTH_TENANT'));
            $clientId = trim($setting('MAIL_OAUTH_CLIENT_ID'));
            $clientSecret = $setting('MAIL_OAUTH_CLIENT_SECRET');
            if ($authMode === 'oauth2' &&
                (!preg_match('/^[a-zA-Z0-9.-]+$/', $tenant) || $clientId === '' || $clientSecret === '')) {
                return $this->reportError($response, 500, 'Internal Server Error #003');
            }

            // send mail
            $this->mailer->isSMTP();
            $this->mailer->Host       = $smtpHost;
            $this->mailer->SMTPAuth   = true;
            $this->mailer->Username   = $smtpUsername;

            if ($authMode === 'oauth2') {
                $this->mailer->AuthType = 'XOAUTH2';
                $this->mailer->setOAuth(new MicrosoftOAuthTokenProvider(
                    $smtpUsername, $tenant, $clientId, $clientSecret
                ));
            } else {
                $this->mailer->AuthType = '';
                $this->mailer->Password = $smtpPassword;
            }

            $this->mailer->SMTPSecure = $smtpEncryption;
            $this->mailer->Port       = $smtpPort;

            $this->mailer->setFrom($smtpEmail, 'Cosplay-Atelier');
            $this->mailer->addReplyTo($data['email'], $data['name']);
            $this->mailer->addAddress(trim($setting('MAIL_RECIPIENT', $smtpEmail)), $this->recipientName);
            $this->mailer->CharSet = PHPMailer::CHARSET_UTF8;
            $this->mailer->Encoding = PHPMailer::ENCODING_QUOTED_PRINTABLE;

            $this->mailer->Subject = "Kontaktanfrage von " . $data['name'];
            $this->mailer->Body = "\n\n
                Möchte Mitglied werden: " . $data['name'] . "\n\n
                Daten:\n
                Adresse: " . $data['address'] . "\n
                PLZ: " . $data['plz'] . "\n
                Geburtsdatum: " . $data['birthday'] . "\n
                Telefon: " . $data['phone'] . "\n
                E-Mail: " . $data['email'] . "\n\n\n\n\n\n

                Gesendet am: " . date('d.m.Y H:i') . "\n\n";

            $this->mailer->send();

            $_SESSION["form_success"] = "success";
            return $response
                ->withHeader('Location', "/contacts")
                ->withStatus(303);

        } catch (Throwable $e) {
            return $this->reportError($response, 500, "Internal Server Error");
        }
    }
}
