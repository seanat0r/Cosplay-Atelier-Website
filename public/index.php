<?php
declare(strict_types=1);

use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpException;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config.php';
require BASE_PATH . '/src/PageLoader.php';
require BASE_PATH . '/src/MarkdownParser.php';
require BASE_PATH . '/src/Mail.php';
require BASE_PATH . '/src/DownloadHandler.php';

// initializing
$app = AppFactory::create();

$parser = new MarkdownParser();
$pageLoader = new PageLoader($parser);
$mail = new Mail();
$downloadHandler = new DownloadHandler();

// middleware
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(false, true, true);
$errorMiddleware->setErrorHandler(
    HttpNotFoundException::class,
    function (Request $request, Throwable $e) use ($app, $pageLoader) {
        $response = $app->getResponseFactory()->createResponse();
        return $pageLoader->notFound($request, $response, ['message' => $e->getMessage()]);
    }
);

$standardHandler = $errorMiddleware->getDefaultErrorHandler();

$errorMiddleware->setDefaultErrorHandler(
    function (Request $request, Throwable $e, ...$options) use ($app, $pageLoader, $standardHandler) {
        if ($e instanceof HttpException && $e->getCode() < 500) {
            return $standardHandler($request, $e, ...$options);
        }

        error_log((string) $e);
        $response = $app->getResponseFactory()->createResponse();
        return $pageLoader->serverError($request, $response, ['message' => $e->getMessage()]);
    }
);

// homepage and redirect to homepage ("/")
$app->get("/", [$pageLoader, "homepage"]);
$app->redirect("/home", "/");

// download and mail router
$app->get("/download", [$downloadHandler, "download"]);
$app->post("/send_mail", [$mail, "sendMail"]);

// all subpage
$app->group("/", function (RouteCollectorProxy $group) use ($pageLoader) {

    $group->get("about", [$pageLoader, 'about']);
    $group->get("bylaws", [$pageLoader, 'bylaws']);
    $group->get("contacts", [$pageLoader, 'contacts']);
    $group->get("dsgvo", [$pageLoader, 'dsgvo']);
    $group->get("news/{article}", [$pageLoader, 'newsArticle']);
    $group->get("news", [$pageLoader, 'news']);
    $group->get("photogalerie", [$pageLoader, 'photogalerie']);
    $group->get("kibacon", [$pageLoader, 'kibacon']);
    $group->get("404", [$pageLoader, 'notFound']);
    $group->get("500", [$pageLoader, 'serverError']);
});

$app->run();
