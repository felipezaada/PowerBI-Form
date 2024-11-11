<?php
include('iniciarDB.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
    public string $numero;
    public string $bairro;
    public string $setor;
    public string $cidade;
    public bool $caixa_dagua;
    public bool $ralo;
    public bool $vaso;
    public bool $lixo;
    public float $latitude;
    public float $longitude;

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
            
            // Obter latitude e longitude com a API do Google
            $this->obterCoordenadas();
        }

        $sql = "INSERT INTO formulario (
            usuario_username, nome, rua, numero, bairro, setor, cidade, caixa_dagua, ralo, vaso, lixo, latitude, longitude
        ) VALUES (
            :usuario_username, :nome, :rua, :numero, :bairro, :setor, :cidade, :caixa_dagua, :ralo, :vaso, :lixo, :latitude, :longitude
        )";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':usuario_username', $this->username, PDO::PARAM_STR);
        $stmt->bindValue(':nome', $this->nome, PDO::PARAM_STR);
        $stmt->bindValue(':rua', $this->rua, PDO::PARAM_STR);
        $stmt->bindValue(':numero', $this->numero, PDO::PARAM_STR); // Alterado para string para aceitar número com caracteres
        $stmt->bindValue(':bairro', $this->bairro, PDO::PARAM_STR);
        $stmt->bindValue(':setor', $this->setor, PDO::PARAM_STR);
        $stmt->bindValue(':cidade', $this->cidade, PDO::PARAM_STR);
        $stmt->bindValue(':caixa_dagua', (int) $this->caixa_dagua, PDO::PARAM_INT);
        $stmt->bindValue(':ralo', (int) $this->ralo, PDO::PARAM_INT);
        $stmt->bindValue(':vaso', (int) $this->vaso, PDO::PARAM_INT);
        $stmt->bindValue(':lixo', (int) $this->lixo, PDO::PARAM_INT);
        $stmt->bindValue(':latitude', $this->latitude, PDO::PARAM_STR);
        $stmt->bindValue(':longitude', $this->longitude, PDO::PARAM_STR);

        $stmt->execute();
        header("Location: valeu.php");
    }

    private function obterCoordenadas()
    {
        // Combina os campos rua, bairro e cidade em um único endereço
        $enderecoCompleto = $this->rua . ', ' . $this->numero . ', ' . $this->bairro . ', ' . $this->cidade;

        // Substitua pela sua chave da API do Google
        $apiKey = 'chave da api';
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($enderecoCompleto) . "&key=" . $apiKey;

        // Requisição à API
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if ($data['status'] == 'OK') {
            $this->latitude = $data['results'][0]['geometry']['location']['lat'];
            $this->longitude = $data['results'][0]['geometry']['location']['lng'];
        } else {
            // Em caso de erro, pode-se definir valores padrão ou tratar de outra forma
            $this->latitude = 0.0;
            $this->longitude = 0.0;
        }
    }
}
