<?php

namespace Editiel98;

use Error;
use Exception;
use Whoops\Run;
use Editiel98\Kernel\GetEnv;
use Editiel98\Kernel\Routing\Routing;
use Whoops\Handler\PrettyPageHandler;
use Editiel98\Kernel\Logger\WarnLogger;
use Editiel98\Kernel\Logger\ErrorLogger;
use Editiel98\Kernel\Events\EventSubcriber;
use Editiel98\Kernel\Events\SystemEvents;
use Editiel98\Kernel\WebInterface\RequestHandler;

class App
{
    public static float $timeStart;
    /**
     * @return void
     */
    public function run(): void
    {
        if (!empty($_SERVER['HTTPS'])) {
            session_set_cookie_params([
                'httponly' => true,
                'secure' => true,
                'samesite' => 'lax'
            ]);
        }
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
        $requestHandler->init($_GET, $_POST, $_SERVER, $_COOKIE, $_SESSION, $_FILES);
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
            echo $response->send();
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
        EventSubcriber::subscribe(
            SystemEvents::DATABASE_ERROR,
            function ($message) {
                $logger = new ErrorLogger();
                if ($logger->storeToFile($message)) {
                    $logger = null;
                }
            }
        );
        EventSubcriber::subscribe(
            SystemEvents::MAIL_ERROR,
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
