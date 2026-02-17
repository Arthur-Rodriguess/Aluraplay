<?php

$dbPath = __DIR__ . "/banco.sqlite";
$pdo = new PDO("sqlite:$dbPath");

$email = $argv[1];
$password = $argv[2];
$hash = password_hash($password, PASSWORD_ARGON2ID);

$statement = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
$statement->bindValue(1, $email);
$statement->bindValue(2, $hash);
$statement->execute();