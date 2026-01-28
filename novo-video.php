<?php

use Alura\Mvc\Repository\VideoRepository;
use Alura\Mvc\Entity\Video;

$dbPath = __DIR__ . "/banco.sqlite";
$pdo = new PDO("sqlite:$dbPath");

$url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
$titulo = filter_input(INPUT_POST, 'titulo');

if($url === false || $titulo === false) {
    header("Location: /?sucesso=0");
    exit();
}

$repository = new VideoRepository($pdo);

if($repository->add(new Video($url, $titulo))) {
    header("Location: /?sucesso=1");
} else {
    header("Location: /?sucesso=0");
}