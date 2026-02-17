<?php

namespace Alura\Mvc\Repository;

use Alura\Mvc\Entity\User;
use PDO;

class UserRepository
{
	public function __construct(private PDO $pdo)
	{
	}

	private function hydrateUser(array $userData): User
	{
		$id = $userData['id'];
		$email = $userData['email'];
		$password = $userData['password'];

		$user = new User($email, $password);
		$user->setId($id);

		return $user;
	}
	public function add(User $user): bool
	{
		$sql = "INSERT INTO users (email, password) VALUES (?, ?)";
		$statement = $this->pdo->prepare($sql);
		$statement->bindValue(1, $user->email);
		$statement->bindValue(2, $user->hash);
		$result = $statement->execute();

		$id = $this->pdo->lastInsertId();
		$user->setId(intval($id));

		return $result;
	}

	public function update(User $user): bool
	{
		$sql = "UPDATE users SET email = :email, password = :password WHERE id = :id";
		$statement = $this->pdo->prepare($sql);
		$statement->bindValue(":email", $user->email);
		$statement->bindValue(":password", $user->hash);
		$statement->bindValue(":id", $user->id);
		return $statement->execute();
	}

	public function remove(int $id): bool
	{
		$sql = "DELETE FROM users WHERE id = ?";
		$statement = $this->pdo->prepare($sql);
		$statement->bindValue(1, $id, PDO::PARAM_INT);
		return $statement->execute();
	}

	public function find(int $id): User
	{
		$sql = "SELECT * FROM users WHERE id = ?";
		$statement = $this->pdo->prepare($sql);
		$statement->bindValue(1, $id, PDO::PARAM_INT);
		$statement->execute();

		$userData = $statement->fetch(PDO::FETCH_ASSOC);
		return $this->hydrateUser($userData);
	}

	public function findByEmail(string $email): ?User
	{
		$sql = "SELECT * FROM users WHERE email = ?";
		$statement = $this->pdo->prepare($sql);
		$statement->bindValue(1, $email);
		$statement->execute();

		$user = $statement->fetch(PDO::FETCH_ASSOC);
		if ($user) {
			return $this->hydrateUser($user);
		}

		return null;
	}

	/**
	 * @return User[]
	 */
	public function all(): array
	{
		$sql = "SELECT * FROM users";
		$statement = $this->pdo->query($sql);
		$userList = $statement->fetchAll(PDO::FETCH_ASSOC);

		return array_map(
			fn($userData) => $this->hydrateUser($userData),
			$userList
		);
	}

	public function upgradePasswordHash(int $id, string $newHash): void
	{
		$sql = "UPDATE users SET password = ? WHERE id = ?";
		$statement = $this->pdo->prepare($sql);
		$statement->bindValue(1, $newHash);
		$statement->bindValue(2, $id);
		$statement->execute();
	}
}