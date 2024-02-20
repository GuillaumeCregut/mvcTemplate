<?php

namespace Editiel98;

use Editiel98\Interfaces\SessionInterface;
use Editiel98\Kernel\WebInterface\RequestHandler;

class Session implements SessionInterface
{
    public const SESSION_CONNECTED = 'isConnected';
    public const SESSION_FULLNAME = 'fullName';
    public const SESSION_RANK_USER = 'rankUser';
    public const SESSION_USER_ID = 'userId';
    private RequestHandler $handler;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->handler = RequestHandler::getInstance();
    }

    /**
     * Get a Key in Session
     *
     * @param string $key : key in session
     * @return mixed value of the key
     */
    public function getKey(string $key): mixed
    {
        if ($this->handler->session->hasKey($key)) {
            return $this->handler->session->getParam($key);
        } else {
            return null;
        }
    }

    /**
     * Store a key in session
     *
     * @param string $key : name of the key
     * @param mixed $value : value to store
     * @return void
     */
    public function setKey(string $key, mixed $value): void
    {
        $this->handler->session->setValue($key, $value);
    }

    /**
     * Store an array for the key in session
     *
     * @param string $key : key to store
     * @param mixed $value : values to store for the key
     * @return void
     */
    public function setMultipleKey(string $key, mixed $value): void
    {
        $this->handler->session->addValueToArray($key, $value);
    }

    /**
     * delete a key from session
     *
     * @param string $key : key to remove
     * @return void
     */
    public function deleteKey(string $key): void
    {
        $this->handler->session->remove($key);
    }

    /**
     * Destroy the session
     *
     * @return void
     */
    public function destroy()
    {
        $this->handler->session->clear();
        session_destroy();
    }
}
