<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "controlink_equipe1";

try {
    global $pdo;
    $pdo = new PDO("mysql:host=$servername", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    $pdo->exec($sql);
    $pdo->exec("USE $dbname");

    $sql = "CREATE TABLE IF NOT EXISTS usuario (
        id VARCHAR(32) PRIMARY KEY,
        username VARCHAR(100) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        senha VARCHAR(255) NOT NULL,
        data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS formulario (
        usuario_username VARCHAR(100) PRIMARY KEY,
        nome VARCHAR(32),
        rua VARCHAR(255) NOT NULL,
        numero INT NOT NULL,
        bairro VARCHAR(255) NOT NULL,
        setor VARCHAR(255) NOT NULL,
        cidade VARCHAR(255) NOT NULL,
        caixa_dagua BOOLEAN NOT NULL,
        ralo BOOLEAN NOT NULL,
        vaso BOOLEAN NOT NULL,
        lixo BOOLEAN NOT NULL,
        FOREIGN KEY (usuario_username) REFERENCES usuario(username) ON DELETE CASCADE
    );";

    $pdo->exec($sql);

} catch (PDOException $e) {
    echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
}
