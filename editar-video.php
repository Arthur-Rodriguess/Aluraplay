<?php

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Repository\VideoRepository;

$dbPath = __DIR__ . "/banco.sqlite";
$pdo = new PDO("sqlite:$dbPath");

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
$titulo = filter_input(INPUT_POST, 'titulo');

if (!$id || $id === null || !$url || !$titulo) {
    header("Location: /?sucesso=0");
    exit();
}

$repository = new VideoRepository($pdo);
$video = new Video($url, $titulo);
$video->setId($id);

if ($repository->update($video)) {
    header("Location: /?sucesso=1");
} else {
    header("Location: /?sucesso=0");
}