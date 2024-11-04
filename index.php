<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
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
<body>
    <div class="container">
        <h1 style="color: #ffffff">Bom dia, <?php echo htmlspecialchars($_SESSION['nome']); ?>!</h1>
        <form action="logout.php" method="POST">
            <button type="submit">Sair</button>
        </form>
    </div>
    <a href="https://discord.gg/7QejUNe9" target="_blank">
        <img src="images/discord.png" alt="Discord" class="discord-icon">
    </a>
</body>
</html>