<?php

namespace Editiel98;

use Editiel98\Kernel\Emitter;
use Editiel98\Kernel\GetEnv;
use Editiel98\Kernel\Routing\RegisterController;
use Editiel98\Kernel\WebInterface\ResponseHandler;
use Editiel98\Templates\DebugController;
use Editiel98\Templates\SmartyEditiel;
use Exception;

abstract class AbstractController
{
    protected SmartyEditiel $smarty;
    protected Session $session;
    protected Flash $flash;
    protected Emitter $emitter;
    protected bool $hasFlash = false;
    protected int $userId;
    protected int $userRank;
    protected bool $isConnected = false;
    protected string $fullName = '';

    public function __construct()
    {
        $this->smarty = new SmartyEditiel();

        $this->session = Factory::getSession();
        $this->flash = new Flash();
        $this->hasFlash = $this->flash->hasFlash();
        if ($this->hasFlash) {
            $flashes = $this->flash->getFlash();
            $this->smarty->assignVar('flash', $flashes);
        }
        $this->emitter = Emitter::getInstance();
        $this->getCredentials();
    }

    /**
     * @return void
     */
    protected function getCredentials(): void
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


    /**
     * @param string $filename
     * @param mixed[] | null $values
     * @return ResponseHandler
     */
    protected function render(string $filename, ?array $values = []): ResponseHandler
    {
        if (GetEnv::getEnvValue('envMode') === 'DEBUG') {
            $debugController = new DebugController();
            $this->smarty->assignVar('debug', $debugController->getDisplay());
        }
        foreach ($values as $key => $value) {
            $this->smarty->assignVar($key, $value);
        }
        $template = $this->smarty->fetchTemplate($filename);
        $rhandler = new ResponseHandler();
        $rhandler->setContent($template);
        return $rhandler;
    }

    /**
     * @param string $routeName
     *
     * @return ResponseHandler
     */
    protected function redirectTo(string $routeName): ResponseHandler
    {
        $routes = RegisterController::getRoutes();
        if (empty($routes[$routeName])) {
            throw new Exception('Route does not exists');
        }
        $rhandler = new ResponseHandler();
        $rhandler->setHeaderResponse(302, 'Found');
        $rhandler->addHeader('Location', $routes[$routeName]);
        return $rhandler;
    }
}
