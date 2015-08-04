<?php

namespace Virgil\SDK\Common\Clients;

use Virgil\SDK\Common\Http\ConnectionInterface,
    Virgil\SDK\Common\Http\Request;

class ApiClient {

    protected $_connection = null;

    public function __construct(ConnectionInterface $connection) {

        $this->_connection = $connection;
    }

    /**
     * @return ConnectionInterface
     */
    public function getConnection() {

        return $this->_connection;
    }

    public function get($endpoint) {

        return $this->_connection->send(
            Request::get(
                $endpoint
            )
        );
    }

    public function post($endpoint, $data) {

        return $this->_connection->send(
            Request::post(
                $endpoint,
                $data
            )
        );
    }

    public function put($endpoint, $data) {

        return $this->_connection->send(
            Request::put(
                $endpoint,
                $data
            )
        );
    }

    public function delete($endpoint, $data = array()) {

        return $this->_connection->send(
            Request::delete(
                $endpoint,
                $data
            )
        );
    }
}