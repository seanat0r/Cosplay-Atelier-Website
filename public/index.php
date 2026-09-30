<?php
declare(strict_types=1);

use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config.php';
require BASE_PATH . '/src/PageLoader.php';
require BASE_PATH . '/src/MarkdownParser.php';
require BASE_PATH . '/src/Mail.php';

$app = AppFactory::create();

$parser = new MarkdownParser();
$pageLoader = new PageLoader($parser);
$mail = new Mail();

// homepage and redirect to homepage ("/")
$app->get("/", [$pageLoader, "homepage"]);
$app->redirect("/home", "/");

$app->post("/send_mail", [$mail, "sendMail"]);

// all subpage
$app->group("/", function (RouteCollectorProxy $group) use ($pageLoader) {

    $group->get("about", [$pageLoader, 'about']);
    $group->get("bylaws", [$pageLoader, 'bylaws']);
    $group->get("contacts", [$pageLoader, 'contacts']);
    $group->get("dsgvo", [$pageLoader, 'dsgvo']);
    $group->get("news", [$pageLoader, 'news']);
    $group->get("photogalerie", [$pageLoader, 'photogalerie']);
});

$app->run();
