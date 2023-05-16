<?php

use App\Core\Renderer;
use App\Core\Router;

require_once __DIR__ . "/../vendor/autoload.php";

$response = Router::route();
$renderer = new Renderer();

echo $renderer->render($response);
