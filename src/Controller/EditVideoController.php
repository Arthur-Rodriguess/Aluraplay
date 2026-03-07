<?php

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Helper\FlashMessageTrait;
use Alura\Mvc\Repository\VideoRepository;

class EditVideoController implements Controller
{
    use FlashMessageTrait;

    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function processaRequisicao(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
        $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS);

        if (!$id || !$url || !$titulo) {
            $this->addErrorMessage("Dados inválidos ou tarefa não existe");
            header("Location: /novo-video");
            exit();
        }

        $video = new Video($url, $titulo);
        $video->setId($id);

		if($_FILES['image']['error'] === UPLOAD_ERR_OK) {
			move_uploaded_file(
				$_FILES['image']['tmp_name'],
				__DIR__ . "/../../public/img/uploads/" . $_FILES['image']['name']
			);
			$video->setFilePath($_FILES['image']['name']);
		}

        $success = $this->videoRepository->update($video);
        if ($success) {
            header("Location: /");
            exit();
        } else {
            $this->addErrorMessage("Erro ao editar vídeo");
            header("Location: /novo-video");
            exit();
        }
    }
}