<?php

namespace Editiel98;

use Editiel98\Kernel\Emitter;
use Editiel98\Kernel\GetEnv;
use Editiel98\Kernel\Logger\ErrorLogger;
use Editiel98\Kernel\Logger\WarnLogger;
use Editiel98\Kernel\Routing\Routing;
use Editiel98\Kernel\WebInterface\RequestHandler;
use Error;
use Exception;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class App
{
    private Emitter $emitter;
    public static float $timeStart;
    /**
     * @return void
     */
    public function run(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (GetEnv::getEnvValue('envMode') === 'DEBUG') {
            $whoops = new Run();
            $whoops->prependHandler(new PrettyPageHandler());
            $whoops->register();
            self::$timeStart = microtime(true);
        }
        $requestHandler = RequestHandler::getInstance();
        $requestHandler->init($_GET, $_POST, $_SERVER, $_COOKIE, $_SESSION);
        $this->setEmitter();
        $controllerInfos = Routing::decodeURI($requestHandler->getURI());
        if (empty($controllerInfos)) {
            header("HTTP/1.0 404 Not Found");
            echo '404 - Not Found';
            die();
        }
        try {
            $controllerName =  $controllerInfos['controller'];
            $requestHandler->infos->setValue('Controller', $controllerName);
            $controller = new  $controllerName();
            $method = $controllerInfos['method'];
            $requestHandler->infos->setValue('Method', $method);
            $response = $controller->$method(...$controllerInfos['params']);
            $response->send();
        } catch (Error $e) {
            if (isset($whoops)) {
                echo $whoops->handleException($e);
            } else {
                header("HTTP/1.0 500 Internal Server Error");
                echo '500 - Internal Server Error';
                exit();
            }
        } catch (Exception $e) {
            if (isset($whoops)) {
                echo $whoops->handleException($e);
            } else {
                header("HTTP/1.0 500 Internal Server Error");
                echo '500 - Internal Server Error';
                exit();
            }
        }
    }

    /**
     * Loads emitters
     *
     * @return void
     */
    private function setEmitter(): void
    {
        $this->emitter = Emitter::getInstance();
        $this->emitter->on(
            Emitter::DATABASE_ERROR,
            function ($message) {
                $logger = new ErrorLogger();
                if ($logger->storeToFile($message)) {
                    $logger = null;
                }
            }
        );
        $this->emitter->on(
            Emitter::MAIL_ERROR,
            function ($to) {
                $logger = new WarnLogger();
                $message = "L'envoi du mail à " . $to . ' a échoué';
                if ($logger->storeToFile($message)) {
                    $logger = null;
                }
            }
        );
    }
}
