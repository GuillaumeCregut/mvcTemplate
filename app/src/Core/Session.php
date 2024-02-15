<?php

namespace Editiel98;

use Editiel98\Interfaces\SessionInterface;

class Session implements SessionInterface
{
    public const SESSION_CONNECTED = 'isConnected';
    public const SESSION_FULLNAME = 'fullName';
    public const SESSION_RANK_USER = 'rankUser';
    public const SESSION_USER_ID = 'userId';


    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Get a Key in Session
     *
     * @param string $key : key in session
     * @return mixed value of the key
     */
    public function getKey(string $key): mixed
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
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
        $_SESSION[$key] = $value;
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
        $_SESSION[$key][] = $value;
    }

    /**
     * delete a key from session
     *
     * @param string $key : key to remove
     * @return void
     */
    public function deleteKey(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Destroy the session
     *
     * @return void
     */
    public function destroy()
    {
        session_destroy();
    }
}
