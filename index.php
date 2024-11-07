<?php
include('iniciarDB.php');
include('formulario.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
}

if (verificarFormularioRespondido($pdo, $_SESSION["username"])) {
    header("Location: valeu.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body style="padding: 20px;">
    <div class="form-container">
        <h2>Relatório de Inspeção</h2>
        <form action="formulario.php" method="POST">

            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required oninput="toLowerCase(this)" autocomplete="off">

            <label for="rua">Rua:</label>
            <input type="text" id="rua" name="rua" required oninput="toLowerCase(this)" autocomplete="off">

            <label for="numero">Número:</label>
            <input type="text" id="numero" name="numero" required autocomplete="off">

            <label for="bairro">Bairro:</label>
            <input type="text" id="bairro" name="bairro" required oninput="toLowerCase(this)" autocomplete="off">

            <label for="setor">Setor:</label>
            <input type="text" id="setor" name="setor" required oninput="toLowerCase(this)" autocomplete="off">

            <label for="cidade">Cidade:</label>
            <input type="text" id="cidade" name="cidade" required oninput="toLowerCase(this)" autocomplete="off">

            <label for="caixa_dagua">Caixa d'água destampada:</label>
            <select name="caixa_dagua" id="caixa_dagua" required>
                <option value="1">Sim</option>
                <option value="0">Não</option>
            </select>
            <label for="ralo">Ralo sujo:</label>
            <select name="ralo" id="ralo" required>
                <option value="1">Sim</option>
                <option value="0">Não</option>
            </select>

            <label for="vaso">Vaso de planta:</label>
            <select name="vaso" id="vaso" required>
                <option value="1">Sim</option>
                <option value="0">Não</option>
            </select>

            <label for="lixo">Lixo:</label>
            <select name="lixo" id="lixo" required>
                <option value="1">Sim</option>
                <option value="0">Não</option>
            </select>

            <button type="submit" name="submitForm">Enviar</button>
        </form>
    </div>
</body>

</html>