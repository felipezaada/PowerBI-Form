<?php
include('iniciarDB.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="css/styles.css">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <script>
        function toLowerCase(input) {
            input.value = input.value.toLowerCase();
        }

        function showError(message) {
            const modal = document.getElementById('errorModal');
            const modalContent = document.getElementById('modalContent');
            modalContent.innerText = message;
            modal.style.display = 'flex';
            setTimeout(() => {
                modal.style.display = 'none'; // Esconder automaticamente após 3 segundos
            }, 3000);
        }

        window.onload = function () {
            const errorMessage = "<?php echo isset($_SESSION['error']) ? addslashes($_SESSION['error']) : ''; ?>";
            if (errorMessage) {
                showError(errorMessage);
                <?php unset($_SESSION['error']); ?>
            }
        };
    </script>
</head>

<body>
    <div class="form-container">
        <h2>Registro</h2>

        <form action="usuario.php" method="POST">
            <label for="username">Usuário:</label>
            <input type="text" id="username" name="username" required oninput="toLowerCase(this)" autocomplete="off">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required oninput="toLowerCase(this)" autocomplete="off">

            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required oninput="toLowerCase(this)"
                autocomplete="off">

            <button type="submit" name="submitRegistro">Registrar</button>
        </form>
        <div class="extra-container">
            <a href="login.php" class="extra-button">Faça Login</a>
        </div>
    </div>

    <div id="errorModal">
        <p id="modalContent"></p>
    </div>
</body>

</html>