<?php

namespace Alura\Mvc\Controller;

use Alura\Mvc\Repository\VideoRepository;

class DeleteVideoController implements Controller
{
    public function __construct(private VideoRepository $videoRepository)
    {
    }


    public function processaRequisicao(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        $success = $this->videoRepository->remove($id);

        if($success) {
            header("Location: /?sucesso=1");
        } else {
            header("Location: /?sucesso=0");
        }
    }
}