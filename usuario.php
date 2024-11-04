<?php

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "forms";
$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

$sql = "CREATE TABLE IF NOT EXISTS usuario (
    id VARCHAR(32) PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
)";

$pdo->exec($sql);

if (isset($_POST['submitRegistro'])) {
    $usuario = new Usuario();
    $usuario->cadastrar($pdo);
}

if (isset($_POST['submitLogin'])) {
    $usuario = new Usuario();
    $usuario->autenticar($pdo);
}

class Usuario
{
    public string $id;
    public string $nome;
    public string $email;
    public string $senha;

    function cadastrar(PDO $pdo)
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $this->nome = $_POST["username"];
            $this->email = $_POST["email"];
            $this->senha = $_POST["password"];
        }

        $this->id = bin2hex(random_bytes(16));

        try {
            $sqlCheck = "SELECT * FROM usuario WHERE email = :email";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->execute(['email' => $this->email]);
            if ($stmtCheck->rowCount() > 0) {
                throw new Exception("Este email já está cadastrado.");
            }

            $sqlCheck = "SELECT * FROM usuario WHERE nome = :nome";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->execute(['nome' => $this->nome]);
            if ($stmtCheck->rowCount() > 0) {
                throw new Exception("Este username já está cadastrado.");
            }

            $sql = "INSERT INTO usuario (id, nome, email, senha) VALUES (:id, :nome, :email, :senha)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'id' => $this->id,
                'nome' => $this->nome, 
                'email' => $this->email, 
                'senha' => password_hash($this->senha, PASSWORD_DEFAULT)
            ]);

            $_SESSION['id'] = $this->id;
            $_SESSION['nome'] = $this->nome;
            $_SESSION['email'] = $this->email;

            header("Location: index.php");
            exit();
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header("Location: registro.php");
            exit();
        }
    }

    function autenticar(PDO $pdo)
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $this->nome = $_POST["username"];
            $this->senha = $_POST["password"];
        }
    
        $sql = "SELECT * FROM usuario WHERE nome = :nome";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['nome' => $this->nome]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($usuario && password_verify($this->senha, $usuario['senha'])) {
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['email'] = $usuario['email'];
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "Usuário ou senha incorretos.";
            header("Location: login.php");
            exit();
        }
    }    
}
?>