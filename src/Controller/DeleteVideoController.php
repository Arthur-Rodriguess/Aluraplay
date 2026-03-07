<?php

namespace Alura\Mvc\Controller;

use Alura\Mvc\Helper\FlashMessageTrait;
use Alura\Mvc\Repository\VideoRepository;

class DeleteVideoController implements Controller
{
    use FlashMessageTrait;

    public function __construct(private VideoRepository $videoRepository)
    {
    }


    public function processaRequisicao(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        $success = $this->videoRepository->remove($id);

        if($success) {
            header("Location: /");
            exit();
        } else {
            $this->addErrorMessage("Erro ao remover vídeo");
            header("Location: /");
            exit();
        }
    }
}