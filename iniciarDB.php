<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "controlink_equipe1";

try {
    global $pdo;
    $pdo = new PDO("mysql:host=$servername", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Criação do banco de dados, se não existir
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    $pdo->exec($sql);
    $pdo->exec("USE $dbname");

    // Criação das tabelas se não existirem
    $sql = "
    CREATE TABLE IF NOT EXISTS usuario (
        id VARCHAR(32) PRIMARY KEY,
        username VARCHAR(100) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        senha VARCHAR(255) NOT NULL,
        data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS formulario (
        formulario_id VARCHAR (32) PRIMARY KEY,
        agente_username VARCHAR(100),
        nome VARCHAR(32) NOT NULL,
        rua VARCHAR(255) NOT NULL,
        numero INT NOT NULL,
        bairro VARCHAR(255) NOT NULL,
        setor VARCHAR(255) NOT NULL,
        cidade VARCHAR(255) NOT NULL,
        caixa_dagua BOOLEAN NOT NULL,
        ralo BOOLEAN NOT NULL,
        vaso BOOLEAN NOT NULL,
        lixo BOOLEAN NOT NULL,
        latitude VARCHAR(255),
        longitude VARCHAR(255),
        FOREIGN KEY (agente_username) REFERENCES usuario(username) ON DELETE CASCADE
    );";
    
    // Executa o SQL para criar as tabelas
    $pdo->exec($sql);

} catch (PDOException $e) {
    echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
}
?>
