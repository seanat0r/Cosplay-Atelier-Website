<?php
declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PageLoader
{

    public function __construct(private MarkdownParser $parser)
    {
    }

    public function homepage(Request $request, Response $response, array $args): Response {
        ob_start();
        include BASE_PATH . "/public/pages/homepage.php";
        $html = ob_get_clean();

        $response->getBody()->write($html);
        return $response
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }

    public function about(Request $request, Response $response, array $args): Response {
        ob_start();
        include BASE_PATH . "/public/pages/about_us.php";
        $html = ob_get_clean();

        $response->getBody()->write($html);
        return $response
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }

    public function bylaws(Request $request, Response $response, array $args): Response {
        ob_start();
        include BASE_PATH . "/public/pages/bylaws.php";
        $html = ob_get_clean();

        $response->getBody()->write($html);
        return $response
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }

    public function contacts(Request $request, Response $response, array $args): Response {
        ob_start();
        include BASE_PATH . "/public/pages/contacts.php";
        $html = ob_get_clean();

        $response->getBody()->write($html);
        return $response
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }

    public function dsgvo(Request $request, Response $response, array $args): Response {
        ob_start();
        include BASE_PATH . "/public/pages/dsgvo.php";
        $html = ob_get_clean();

        $response->getBody()->write($html);
        return $response
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }

    public function news(Request $request, Response $response, array $args): Response {
        ob_start();
        include BASE_PATH . "/public/pages/news.php";
        $html = ob_get_clean();

        $response->getBody()->write($html);
        return $response
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }

    public function photogalerie(Request $request, Response $response, array $args): Response {
        ob_start();
        include BASE_PATH . "/public/pages/photogalerie.php";
        $html = ob_get_clean();

        $response->getBody()->write($html);
        return $response
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }
}