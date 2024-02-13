<?php

namespace Editiel98;

use Editiel98\Chore\Database;
use Editiel98\Chore\Emitter;


abstract class AbstractController
{
    protected SmartyMKD $smarty;
    protected Session $session;
    protected Flash $flash;
    protected Emitter $emitter;
    protected Database $dbConnection;

    protected bool $hasFlash = false;
    protected int $userId;
    protected int $userRank;
    protected bool $isConnected = false;
    protected string $fullName = '';

    public function __construct()
    {
        $this->smarty = new SmartyMKD();

        $this->session = Factory::getSession();
        $this->flash = new Flash();
        $this->hasFlash = $this->flash->hasFlash();
        if ($this->hasFlash) {
            $flashes = $this->flash->getFlash();
            $this->smarty->assign('flash', $flashes);
        }
        $this->emitter = Emitter::getInstance();
        $this->dbConnection = Database::getInstance();
        $this->getCredentials();
    }

    protected function getCredentials()
    {
        $connected = $this->session->getKey(Session::SESSION_CONNECTED);
        if (!is_null($connected)) {
            if ($connected) {
                $this->isConnected = true;
                $this->userRank = $this->session->getKey(Session::SESSION_RANK_USER);
                $this->userId = $this->session->getKey(Session::SESSION_USER_ID);
                $this->fullName = $this->session->getKey(Session::SESSION_FULLNAME);
            }
        } else {
            $this->userRank = 0;
        }
    }

    protected function render(string $filename, array $values)
    {
        foreach ($values as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->display($filename);
    }
}
