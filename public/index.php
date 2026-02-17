<?php

use Alura\Mvc\Controller\{
    Controller,
    DeleteVideoController,
    EditVideoController,
    Error404Controller,
    NewVideoController,
    VideoFormController,
    VideoListController
};
use Alura\Mvc\Repository\{
	VideoRepository,
	UserRepository
};

require_once __DIR__ . "/../vendor/autoload.php";

$dbPath = __DIR__ . "/../banco.sqlite";
$pdo = new PDO("sqlite:$dbPath");
$videoRepository = new VideoRepository($pdo);
$userRepository = new UserRepository($pdo);

$routes = require_once __DIR__ . "/../config/routes.php";
$pathInfo = $_SERVER['PATH_INFO'] ?? "/";
$httpMethod = $_SERVER['REQUEST_METHOD'];
$key = "$httpMethod|$pathInfo";

$isLoginRoute = $pathInfo === "/login";
session_start();
session_regenerate_id();

if(!array_key_exists('logado', $_SESSION) && !$isLoginRoute && array_key_exists($key, $routes)) {
	header("Location: /login");
	return;
}

if(array_key_exists($key, $routes) && $pathInfo !== "/login") {
    $controllerClass = $routes[$key];
    $controller = new $controllerClass($videoRepository);
} elseif ($pathInfo === "/login") {
	$controllerClass = $routes[$key];
	$controller = new $controllerClass($userRepository);
} else {
    $controller = new Error404Controller();
}

/** @var Controller $controller */
$controller->processaRequisicao();