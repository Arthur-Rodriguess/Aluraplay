<?php

namespace Alura\Mvc\Controller;

class LogoutController implements Controller
{

	public function processaRequisicao(): void
	{
		session_destroy();
		header("Location: /login");

		/**
		 * Outras duas formas de fazer logout:
		 * $_SESSION['logado'] = false;
		 * unset($_SESSION['logado']);
		 */
	}
}