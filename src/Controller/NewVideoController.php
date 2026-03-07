<?php

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Helper\FlashMessageTrait;
use Alura\Mvc\Repository\VideoRepository;

class NewVideoController implements Controller
{
    use FlashMessageTrait;

    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function processaRequisicao(): void
    {
        $url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
        $titulo = filter_input(INPUT_POST, 'titulo');

        if(!$url) {
            $this->addErrorMessage('Url inválida');
            header("Location: /novo-video");
            exit();
        }

        if(!$titulo) {
            $this->addErrorMessage('Título inválido');
            header("Location: /novo-video");
            exit();
        }

		$video = new Video($url, $titulo);

		if($_FILES['image']['error'] === UPLOAD_ERR_OK) {
			$fInfo = new \finfo(FILEINFO_MIME_TYPE);
			$mimeType = $fInfo->file($_FILES['image']['tmp_name']);

			if(str_starts_with($mimeType, "image/")) {
				$safeFileName = uniqid("upload_") . "_" . pathinfo($_FILES['image']['name'], PATHINFO_BASENAME);
				move_uploaded_file(
					$_FILES['image']['tmp_name'],
					__DIR__ . "/../../public/img/uploads/" . $safeFileName
				);
				$video->setFilePath($safeFileName);
			}
		}
        $success = $this->videoRepository->add($video);

        if($success) {
            header("Location: /");
            exit();
        } else {
            $this->addErrorMessage("Erro ao adicionar vídeo");
            header("Location: /novo-video");
            exit();
        }
    }
}