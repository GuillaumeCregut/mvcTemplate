<?php

namespace Editiel98\Templates;

use Editiel98\App;
use Editiel98\Kernel\Attribute\RouteAttribute;
use Editiel98\Kernel\MainAbstractController;
use Editiel98\Kernel\Routing\RoutesDisplay;
use Editiel98\Kernel\WebInterface\RequestHandler;
use Editiel98\Kernel\WebInterface\ResponseHandler;

#[RouteAttribute('/debug', name: 'debug_')]
class DebugController extends MainAbstractController
{
    private RequestHandler $handler;

    public function __construct()
    {
        parent::__construct();
        $this->handler = RequestHandler::getInstance();
    }

    public function getDisplay(): string
    {
        $statusCode = $this->handler->server->getParam('REDIRECT_STATUS');
        $method = $this->handler->getMethod();
        $timeSpent = round(1000 * (microtime(true) - App::$timeStart));
        $menus = [
            array(
                'display' => 'Code ' . $statusCode,
                'link' => ''
            ),
            array(
                'display' => $method,
                'link' => ''
            ),
            array(
                'display' => 'Page générée en ' . $timeSpent . 'ms',
                'link' => '',
            ),
            array(
                'display' => 'Routes',
                'link' => '/debug/routes'
            ),
            array(
                'display' => 'Session',
                'link' => '/debug/session'
            ),
            array(
                'display' => 'User',
                'link' => ''
            ),
            array(
                'display' => $this->handler->infos->getParam('Controller') . ' - '
                    . $this->handler->infos->getParam('Method'),
                'link' => ''
            )

        ];
        $this->smarty->assign('menus', $menus);
        $this->smarty->assignVar('time', $timeSpent);
        return $this->smarty->fetchTemplate('debug.tpl');
    }

    #[RouteAttribute('/routes', name: 'routes')]
    public function displayRoutes(): ResponseHandler
    {
        $displayRoutes = new RoutesDisplay();
        return  $this->render('debug/routes.tpl', [
            'routes' => $displayRoutes->getRoutes(),
            'debug' => $this->getDisplay()
        ]);
    }

    #[RouteAttribute('/session', name: 'session')]
    public function displaySession(): ResponseHandler
    {
        $sessionVars = $this->handler->session->getAll();
        return $this->render('debug/session.tpl', [
            'session' => $sessionVars,
            "debug" => $this->getDisplay()
        ]);
    }
}
