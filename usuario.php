<?php
include('iniciarDB.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
    public string $username;
    public string $email;
    public string $senha;

    function cadastrar(PDO $pdo)
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $this->username = $_POST["username"];
            $this->email = $_POST["email"];
            $this->senha = $_POST["password"];
        }

        $this->id = bin2hex(random_bytes(16));

        try {
            $sqlCheck = "SELECT * FROM usuario WHERE email = :email";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmtCheck->execute();

            if ($stmtCheck->rowCount() > 0) {
                throw new Exception("Este email já está cadastrado.");
            }

            $sqlCheck = "SELECT * FROM usuario WHERE username = :username";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->bindValue(':username', $this->username, PDO::PARAM_STR);
            $stmtCheck->execute();

            if ($stmtCheck->rowCount() > 0) {
                throw new Exception("Este username já está cadastrado.");
            }

            $sql = "INSERT INTO usuario (id, username, email, senha) VALUES (:id, :username, :email, :senha)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_STR);
            $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':senha', password_hash($this->senha, PASSWORD_DEFAULT), PDO::PARAM_STR);
            $stmt->execute();

            $_SESSION['id'] = $this->id;
            $_SESSION['username'] = $this->username;
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
            $this->username = $_POST["username"];
            $this->senha = $_POST["password"];
        }

        $sql = "SELECT * FROM usuario WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($this->senha, $usuario['senha'])) {
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['username'] = $usuario['username'];
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
