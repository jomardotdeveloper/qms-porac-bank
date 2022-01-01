<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\Models\Server;

class WebsocketController extends Controller implements MessageComponentInterface
{
    private $connections = [];
    private $branches = [];
    /**
     * When a new connection is opened it will be passed to this method
     * @param  ConnectionInterface $conn The socket/connection that just connected to your application
     * @throws \Exception
     */
    function onOpen(ConnectionInterface $conn)
    {
        $this->connections[$conn->resourceId] = compact('conn') + ['user_id' => null];
        $this->connections[$conn->resourceId]['conn']->send(json_encode(["message" => json_encode(["message" => "isBranch"])]));
    }

    /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     * @param  ConnectionInterface $conn The socket/connection that is closing/closed
     * @throws \Exception
     */
    function onClose(ConnectionInterface $conn)
    {
        if (array_key_exists($conn->resourceId, $this->branches)) {
            $server = Server::where("branch_id", "=", $this->branches[$conn->resourceId])->first();
            $server->is_connected = false;
            $server->save();
            unset($this->branches[$conn->resourceId]);
        }
    }

    /**
     * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
     * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
     * @param  ConnectionInterface $conn
     * @param  \Exception $e
     * @throws \Exception
     */
    function onError(ConnectionInterface $conn, \Exception $e)
    {
        // if (array_key_exists($conn->resourceId, $this->branches)) {
        //     $server = Server::where("branch_id", "=", $this->branches[$conn->resourceId])->first();
        //     $server->is_connected = false;
        //     $server->save();
        //     unset($this->branches[$conn->resourceId]);
        // }
    }

    /**
     * Triggered when a client sends data through the socket
     * @param  \Ratchet\ConnectionInterface $conn The socket/connection that sent the message to your application
     * @param  string $msg The message received
     * @throws \Exception
     */
    function onMessage(ConnectionInterface $conn, $msg)
    {
        $message_json = json_decode($msg, true);
        if ($message_json["message"] == "iambranch") {
            $this->branches[$conn->resourceId] = $message_json["branch_id"];
            $server = Server::where("branch_id", "=", $this->branches[$conn->resourceId])->first();
            $server->is_connected = true;
            $server->save();
        } else {
            $this->connections[$conn->resourceId]['user_id'] = $msg;
            $onlineUsers = [];

            foreach ($this->connections as $resourceId => &$connection) {
                $connection['conn']->send(json_encode(["message" => $msg]));
            }
        }
    }
}
