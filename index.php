<?php
include('iniciarDB.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="bairros.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=SUA_KEY_AQUI&libraries=places&callback=initAutocomplete" async defer></script>
    <script>
        let autocomplete;

        function initAutocomplete() {
            // Inicializa o campo de endereço
            autocomplete = new google.maps.places.Autocomplete(document.getElementById('endereco'), {
                types: ['address'],
                componentRestrictions: { country: "BR" }
            });

            // Adiciona o listener para preencher os campos automaticamente
            autocomplete.addListener('place_changed', fillInAddress);
        }       

        function fillInAddress() {
            const place = autocomplete.getPlace();
            const addressComponents = place.address_components;

            let bairro = '';
            for (let component of addressComponents) {
                const types = component.types;
                if (types.includes("sublocality_level_1") || types.includes("neighborhood")) {
                    bairro = component.long_name;
                    document.getElementById('bairro').value = bairro;
                }
                if (types.includes("street_number")) {
                    document.getElementById('numero').value = component.long_name;
                }
                if (types.includes("route")) {
                    document.getElementById('rua').value = component.long_name;
                }
                if (types.includes("administrative_area_level_2")) {
                    document.getElementById('cidade').value = component.long_name;
                }
            }

            // Atualiza o campo 'setor' com base no bairro
            updateSetor(bairro);
        }

        function updateSetor(bairro) {
            // Normaliza o nome do bairro para facilitar a comparação
            bairro = normalizeBairro(bairro);

            // Verifica o setor com base no bairro
            const setor = bairrosESetores[bairro];

            if (setor) {
                document.getElementById('setor').value = setor;
            } else {
                document.getElementById('setor').value = 'Não encontrado'; // Ou qualquer valor padrão
            }
        }

        function normalizeBairro(bairro) {
            // Converte o nome para minúsculas
            bairro = bairro.toLowerCase();

            // Substitui abreviações e remove acentos
            bairro = bairro.replace(/\b(vl|vila)\b/g, 'vila');  // Substitui 'Vl' ou 'Vila' por 'Vila'
            bairro = bairro.replace(/\b(jd|jardim)\b/g, 'jardim');  // Substitui 'Jd' ou 'Jardim' por 'Jardim'
            bairro = bairro.normalize('NFD').replace(/[\u0300-\u036f]/g, ''); // Remove acentos

            return bairro;
        }
    </script>
</head>

<body style="padding: 20px;">
    <div class="form-container">
        <h2>Relatório de Inspeção</h2>
        <form action="formulario.php" method="POST">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required autocomplete="off">

            <label for="endereco">Endereço:</label>
            <input type="text" id="endereco" name="endereco" required autocomplete="off">

            <input type="hidden" id="rua" name="rua">
            <input type="hidden" id="numero" name="numero">
            <input type="hidden" id="bairro" name="bairro">
            <input type="hidden" id="cidade" name="cidade">

            <label for="setor">Setor:</label>
            <input type="text" id="setor" name="setor" readonly autocomplete="off">

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