<?php

namespace Editiel98;

use Editiel98\Kernel\Emitter;
use Editiel98\Kernel\Logger\ErrorLogger;
use Editiel98\Kernel\Logger\WarnLogger;
use Editiel98\Kernel\Routing\Routing;
use Error;
use Exception;


class App
{
    private Emitter $emitter;

    public function run()
    {
        $this->setEmitter();
        $url=trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '');
        $controllerInfos=Routing::decodeURI($url);
        if (empty($controllerInfos)) {
            header("HTTP/1.0 404 Not Found");
            echo '404 - Not Found';
            die();
        }
        try {
            $controllerName =  $controllerInfos['controller'];
            $controller = new  $controllerName();
            $method = $controllerInfos['method'];
            $controller->$method(...$controllerInfos['params']);
        } catch (Exception $e) {
            header("HTTP/1.0 500 Internal Server Error");
            echo '500 - Internal Server Error';
            exit();
        } catch(Error $e) {
            header("HTTP/1.0 500 Internal Server Error");
            echo '500 - Internal Server Error';
            exit();
        }
    }

    /**
     * Loads emitters
     * @return void
     */
    private function setEmitter(): void
    {
        $this->emitter = Emitter::getInstance();
        $this->emitter->on(Emitter::DATABASE_ERROR, function ($message) {
            $logger = new ErrorLogger();
            if ($logger->storeToFile($message)) {
                $logger = null;
            }
        });
        $this->emitter->on(Emitter::MAIL_ERROR, function ($to) {
            $logger = new WarnLogger();
            $message = "L'envoi du mail à " . $to . ' a échoué';
            if ($logger->storeToFile($message)) {
                $logger = null;
            }
        });
    }
}
