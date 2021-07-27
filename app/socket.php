<?php

namespace MyWebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Socket implements MessageComponentInterface {

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {

        // Store the new connection in $this->clients
        $this->clients->attach($conn);

        echo "Nova conexao! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {

        foreach ( $this->clients as $client ) {

            if ( $from->resourceId == $client->resourceId ) {
                continue;
            }

			$array = json_decode($msg, true);
			$client->send( "<b>".$array["nickname"]."</b>: ".$array["message"]);
        }
    }

    public function onClose(ConnectionInterface $conn) {
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    }
}