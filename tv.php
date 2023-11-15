<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TV</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: 'Arial', sans-serif;
        }

        #header {
            background-color: #3498db;
            color: #fff;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }

        #main-content {
            display: flex;
            height: calc(100vh - 120px); /* 100% viewport height - header and footer height */
            overflow: hidden;
        }

        #video-column {
            flex: 70%;
            background-color: #fff;
            overflow: hidden;
        }

        #video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #names-column {
            flex: 30%;
            background-color: #ecf0f1;
            overflow-y: auto;
            padding: 20px;
        }

        #footer {
            background-color: #3498db;
            color: #fff;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <div id="header">
        <span id="nomeCliente"></span>
    </div>

    <div id="main-content">
        <div id="video-column">
<!-- Substitua o elemento abaixo pelo código de incorporação do vídeo desejado -->
<video id="video" autoplay loop muted playsinline>
    <source src="dentista.mp4" type="video/mp4">
</video>

        </div>

        <div id="names-column">
            <h4>Ultimos Chamados:</h4>
            <ul id="ultimosNomes"></ul>
        </div>
    </div>

    <div id="footer">
        OdontoLider
    </div>
    <audio id="alertSound" src="notificacao.mp3"></audio>

<script>
    var ws = new WebSocket('ws://localhost:8080');
    var alertSound = document.getElementById('alertSound');
    var ultimoNomeChamado = null;

    // Evento de abertura da conexão WebSocket
    ws.onopen = function(event) {
        console.log('Conexão WebSocket aberta:', event);
    };

    // Evento de recebimento de mensagem WebSocket
    ws.onmessage = function(event) {
        var data = JSON.parse(event.data);

        // Atualizar o nome na parte superior da tela
        document.getElementById('nomeCliente').innerText = data.nome;

        // Adicionar o nome à lista de últimos nomes apenas se houver um último nome chamado
        if (ultimoNomeChamado !== null) {
            var ul = document.getElementById('ultimosNomes');
            var li = document.createElement('li');
            li.appendChild(document.createTextNode(ultimoNomeChamado));
            ul.insertBefore(li, ul.firstChild);
        }

        // Armazenar o nome atual para comparação na próxima mensagem
        ultimoNomeChamado = data.nome;

        // Reproduzir som de alerta
        alertSound.play();

        // Mude o áudio do vídeo (substitua pelo seu código de mudança de áudio)
        document.getElementById('video').volume = 0.2;

        // Defina um temporizador para restaurar o áudio após 3 segundos (ajuste conforme necessário)
        setTimeout(function() {
            document.getElementById('video').volume = 1;
        }, 3000);
    };

    // Evento de fechamento da conexão WebSocket
    ws.onclose = function(event) {
        console.log('Conexão WebSocket fechada:', event);
    };
</script>
</body>
</html>