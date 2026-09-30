<?php
declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \PHPMailer\PHPMailer\PHPMailer;

class Mail
{
    private string $recipientEmail = "kontakt@cosplay-atelier.ch";
    private string $recipientName = "Cosplay-Atelier Vorstand";
    private string $senderDomain = "cosplay-atelier.ch";
    private int $rateLimit = 3;
    private string $allowedOrigin = "https://cosplay-atelier.ch";

    public function __construct( private readonly PHPMailer $mailer = new PHPMailer(true))
    {
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
        $_SESSION['form_old'] = $_POST;

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
     * Only 3 Mails are allowed
     * @param Response $response
     * @return bool|Response true if successfull
     */
    private function rateLimitCheck(Response $response): bool|Response
    {
        $now = time();
        if (!isset($_SESSION['rate_limit'])) {
            $_SESSION['rate_limit'] = [];
        }

        $_SESSION['rate_limit'] = array_filter(
            $_SESSION['rate_limit'],
            fn($time) => ($now - $time) < 3600
        );

        if (count($_SESSION['rate_limit']) >= $this->rateLimit) {
            return $this->reportError($response, 429, "Rate limit exceeded");
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
        if (empty($plz) || mb_strlen($plz) > 4) {
            $failed[] = "plz/city is invalid";
        }

        //address
        $address = $this->cleanStringInput($_POST['adresse'] ?? null);
        if (empty($address) || mb_strlen($plz) > 200) {
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
        session_start();

        $originResult = $this->originCheck($response);
        if ($originResult instanceof Response) {
            return $originResult;
        }

        $honeypotResult = $this->honeypotCheck($response);
        if ($honeypotResult instanceof Response) {
            return $honeypotResult;
        }

        $rateLimitResult = $this->rateLimitCheck($response);
        if ($rateLimitResult instanceof Response) {
            return $rateLimitResult;
        }

        $data = $this->inputValidationCheck($response);
        if ($data instanceof Response) {
            return $data;
        }

        try {
            // send mail
            $this->mailer->isSMTP();
            $this->mailer->Host = $this->allowedOrigin;
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username   = 'deine-email@example.com';
            $this->mailer->Password   = 'dein-sicheres-passwort';
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port       = 587;

            $this->mailer->setFrom($data['email'], $data['name']);
            $this->mailer->addAddress($this->recipientEmail, $this->recipientName);

            $this->mailer->Subject = "Kontaktanfrage von " . $data['name'];
            $this->mailer->Body = "
                Möchte Mitglied werden: " . $data['name'] . "\n\n
                Daten:\n
                Adresse: " . $data['address'] . "\n
                PLZ: " . $data['plz'] . "\n
                Geburtsdatum: " . $data['birthday'] . "\n
                Telefon: " . $data['phone'] . "\n
                E-Mail: " . $data['email'] . "\n\n\n\n\n\n

                Gesendet am: " . date('d.m.Y H:i') . "\n\n";

            $this->mailer->send();

            $_SESSION['rate_limit'][] = time();
            $_SESSION["form_success"] = "success";
            return $response
                ->withHeader('Location', "/contacts")
                ->withStatus(303);

        } catch (Error|\PHPMailer\PHPMailer\Exception $e) {
            return $this->reportError($response, 500, "Internal Server Error");
        }
    }
}
