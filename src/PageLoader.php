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
        include BASE_PATH . "/templates/homepage.php";
        $html = ob_get_clean();

        $response->getBody()->write($html);
        return $response
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }

    public function about(Request $request, Response $response, array $args): Response {
        ob_start();
        include BASE_PATH . "/templates/about_us.php";
        $html = ob_get_clean();

        $response->getBody()->write($html);
        return $response
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }

    public function bylaws(Request $request, Response $response, array $args): Response {
        ob_start();
        include BASE_PATH . "/templates/bylaws.php";
        $html = ob_get_clean();

        $response->getBody()->write($html);
        return $response
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }

    public function contacts(Request $request, Response $response, array $args): Response {
        ob_start();
        include BASE_PATH . "/templates/contacts.php";
        $html = ob_get_clean();

        $response->getBody()->write($html);
        return $response
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }

    public function dsgvo(Request $request, Response $response, array $args): Response {
        ob_start();
        include BASE_PATH . "/templates/dsgvo.php";
        $html = ob_get_clean();

        $response->getBody()->write($html);
        return $response
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }

    public function newsArticle(Request $request, Response $response, array $args): Response
    {
        $article = $args['article']
            ? trim($args['article'])
            : null;

        if (empty($article)) {
            return $this->notFound($request, $response, $args);
        }

        if (
            preg_match('/^[A-Za-z0-9_-]+$/', $article) !== 1 ||
            !is_file(BASE_PATH . '/content/news/' . $article . '.md')
        ) {
            return $this->notFound($request, $response, $args);
        }


        return $response
            ->withHeader('Location', '/news#' . rawurlencode($article))
            ->withStatus(302);
    }

    public function news(Request $request, Response $response, array $args): Response {
        ob_start();
        include BASE_PATH . "/templates/news.php";
        $html = ob_get_clean();

        $response->getBody()->write($html);
        return $response
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }

    public function photogalerie(Request $request, Response $response, array $args): Response {
        ob_start();
        include BASE_PATH . "/templates/photogalerie.php";
        $html = ob_get_clean();

        $response->getBody()->write($html);
        return $response
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }
    public function kibacon(Request $request, Response $response, array $args): Response {
        ob_start();
        include BASE_PATH . "/templates/kibacon.php";
        $html = ob_get_clean();

        $response->getBody()->write($html);
        return $response
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }

    public function notFound(Request $request, Response $response, array $args): Response {
        $errorMessage = $args['message'] ?? 'Unknown Reason';
        ob_start();
        include BASE_PATH . "/templates/404.php";
        $html = ob_get_clean();
        $response->getBody()->write($html);
        return $response
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(404);
    }

    public function serverError(Request $request, Response $response, array $args): Response
    {
        $errorMessage = $args['message'] ?? 'Unknown Reason';
        ob_start();
        include BASE_PATH . "/templates/500.php";
        $html = ob_get_clean();
        $response->getBody()->write($html);
        return $response
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(500);
    }
}