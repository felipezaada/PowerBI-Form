<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "forms";
$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

$sql = "CREATE TABLE IF NOT EXISTS formulario (
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
)";

$pdo->exec($sql);

if (verificarFormularioRespondido($pdo, $_SESSION["username"])) {
    header("Location: valeu.php");
    exit();
}

if (isset($_POST['submitForm'])) {
    $formulario = new Formulario();
    $formulario->salvar($pdo);
}

function verificarFormularioRespondido(PDO $pdo, $username)
{
    $sql_check = "SELECT COUNT(*) FROM formulario WHERE usuario_username = :username";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt_check->execute();
    $userEnviou = $stmt_check->fetchColumn();
    return $userEnviou > 0;
}

class Formulario
{
    public string $username;
    public string $nome;
    public string $rua;
    public int $numero;
    public string $bairro;
    public string $setor;
    public string $cidade;
    public bool $caixa_dagua;
    public bool $ralo;
    public bool $vaso;
    public bool $lixo;

    public function salvar(PDO $pdo)
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $this->username = $_SESSION["username"];
            $this->nome = $_POST["nome"];
            $this->rua = $_POST["rua"];
            $this->numero = $_POST["numero"];
            $this->bairro = $_POST["bairro"];
            $this->setor = $_POST["setor"];
            $this->cidade = $_POST["cidade"];
            $this->caixa_dagua = (bool) $_POST["caixa_dagua"];
            $this->ralo = (bool) $_POST["ralo"];
            $this->vaso = (bool) $_POST["vaso"];
            $this->lixo = (bool) $_POST["lixo"];
        }

        $sql = "INSERT INTO formulario (
            usuario_username, nome, rua, numero, bairro, setor, cidade, caixa_dagua, ralo, vaso, lixo
        ) VALUES (
            :usuario_username, :nome, :rua, :numero, :bairro, :setor, :cidade, :caixa_dagua, :ralo, :vaso, :lixo
        )";

        $stmt = $pdo->prepare($sql);
        
        // evitar SQL Injection
        $stmt->bindValue(':usuario_username', $this->username, PDO::PARAM_STR);
        $stmt->bindValue(':nome', $this->nome, PDO::PARAM_STR);
        $stmt->bindValue(':rua', $this->rua, PDO::PARAM_STR);
        $stmt->bindValue(':numero', $this->numero, PDO::PARAM_INT);
        $stmt->bindValue(':bairro', $this->bairro, PDO::PARAM_STR);
        $stmt->bindValue(':setor', $this->setor, PDO::PARAM_STR);
        $stmt->bindValue(':cidade', $this->cidade, PDO::PARAM_STR);
        $stmt->bindValue(':caixa_dagua', (int) $this->caixa_dagua, PDO::PARAM_INT);
        $stmt->bindValue(':ralo', (int) $this->ralo, PDO::PARAM_INT);
        $stmt->bindValue(':vaso', (int) $this->vaso, PDO::PARAM_INT);
        $stmt->bindValue(':lixo', (int) $this->lixo, PDO::PARAM_INT);
        
        $stmt->execute();
        header("Location: valeu.php");
    }
}