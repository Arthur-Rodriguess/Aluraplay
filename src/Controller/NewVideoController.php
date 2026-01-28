<?php

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Repository\VideoRepository;

class NewVideoController implements Controller
{
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function processaRequisicao(): void
    {
        $url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
        $titulo = filter_input(INPUT_POST, 'titulo');

        if($url === false || $titulo === false) {
            header("Location: /?sucesso=0");
            exit();
        }

        $success = $this->videoRepository->add(new Video($url, $titulo));

        if($success) {
            header("Location: /?sucesso=1");
        } else {
            header("Location: /?sucesso=0");
        }
    }
}