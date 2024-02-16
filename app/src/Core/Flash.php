<?php

namespace Editiel98;

class Flash
{
    private Session $session;
    public const KEY = 'editielFlash';
    public const FLASH_ERROR = 'error';
    public const FLASH_INFO = 'info';
    public const FLASH_WARNING = 'warning';
    public const FLASH_SUCCESS = 'success';

    public function __construct()
    {
        $this->session = Factory::getSession();
    }
    /**
     * Set a flash message in session
     *
     * @param string $message
     * @param string $type
     * @return void
     */
    public function setFlash(string $message, string $type)
    {
        $this->session->setMultipleKey(self::KEY, [
            'message' => $message,
            'flashType' => $type,
        ]);
    }

    /**
     * get the flash message stored in session
     *
     * @return mixed
     */
    public function getFlash(): mixed
    {

        $flash = $this->session->getKey(self::KEY);
        if (!is_null($flash)) {
            $this->session->deleteKey(self::KEY);
        }
        return $flash;
    }

    /**
     * check if there is flash in session
     * @return bool
     */
    public function hasFlash(): bool
    {
        return !is_null($this->session->getKey(self::KEY));
    }
}
