<?php

namespace Alura\Mvc\Controller;

use Alura\Mvc\Controller\Controller;
use Alura\Mvc\Entity\User;
use Alura\Mvc\Repository\UserRepository;

class LoginController implements Controller
{
	public function __construct(private UserRepository $userRepository)
	{
	}
	public function processaRequisicao(): void
	{
		$email = trim(filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL));
		$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

		$user = $this->userRepository->findByEmail($email);

		if(!$user) {
			header("Location: /login?sucesso=0");
			exit();
		}

		if (password_verify($password, $user->hash ?? '')) {

			if(password_needs_rehash($user->hash, PASSWORD_ARGON2ID)) {
				$newHash = password_hash($password, PASSWORD_ARGON2ID);
				$this->userRepository->upgradePasswordHash($user->id, $newHash);
			}

			$_SESSION['logado'] = true;
			header("Location: /?sucesso=1");
		} else {
			header("Location: /login?sucesso=0");
		}
	}
}