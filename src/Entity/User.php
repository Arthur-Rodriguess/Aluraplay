<?php

namespace Alura\Mvc\Entity;

class User
{
	public readonly int $id;
	public readonly string $email;
	public readonly string $hash;

	public function __construct(string $email, string $hash)
	{
		$this->email = $email;
		$this->hash = $hash;
	}

	public function setId(int $id): void
	{
		$this->id = $id;
	}
}