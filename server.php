<?php
require __DIR__ . '/vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class MyWebSocket implements MessageComponentInterface {
    protected $tvConnections = [];

    public function onOpen(ConnectionInterface $conn) {
        $this->tvConnections[] = $conn;
        echo "Nova conexão aberta! (ID: {$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        echo "Mensagem recebida de {$from->resourceId}: $msg\n";

        // Enviar a mensagem para todas as conexões da TV
        foreach ($this->tvConnections as $tvConnection) {
            $tvConnection->send($msg);
            echo "Enviando mensagem para TV (ID: {$tvConnection->resourceId})\n";
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $index = array_search($conn, $this->tvConnections);
        if ($index !== false) {
            unset($this->tvConnections[$index]);
        }
        echo "Conexão fechada! (ID: {$conn->resourceId})\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Erro: {$e->getMessage()}\n";
        $conn->close();
    }
}

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new MyWebSocket()
        )
    ),
    8080
);

echo "Servidor WebSocket iniciado. Aguardando conexões...\n";

$server->run();