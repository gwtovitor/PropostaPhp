<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planos e Preços</title>
    <link rel="stylesheet" href="../Style/style.css">
    <link rel="shortcut icon" href="../Assets/logo.png" type="image/x-icon">
</head>

<body>
    <header>
        <a href="http://localhost:8001/">
            <img class="imgLogo" src="../Assets/logo.png" alt="" />
        </a>
        <div class="divTitle">
            <h1 class="">Planos Disponíveis</h1>
        </div>
    </header>

    <div id='wrapper-home'>
        <div id="plans-container"></div>

        <div id='wrapper-gerar'>
            <select id="plans-select"></select>
            <label for="num-users">Número de Usuários:</label>
            <input type="number" id="num-users" min="1">
            <button id='btn-gerar' onclick="gerarUsuarios()">Confirmar</button>
            <div id="users-fields-container"></div>
            <button type="button" id="enviar-formulario" onclick="submitForm()">Enviar Formulário</button>
            <div id="error-message"></div>
            <div id="dados-container"></div>
        </div>




        <script src="../Scripts/script.js"></script>
</body>

</html>