<?php

namespace Editiel98;

use Editiel98\Chore\Emitter;
use Editiel98\Chore\Logger\ErrorLogger;
use Editiel98\Chore\Logger\WarnLogger;
use Editiel98\Chore\Routing\Routing;
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
            $controllerName =  $controllerInfos[0];
            $controller = new  $controllerName();
            $method = $controllerInfos[1];
            $controller->$method(...$controllerInfos[3]);
        } catch (Exception $e) {
            var_dump($e->getMessage());
            header("HTTP/1.0 500 Internal Server Error");
            echo '500 - Internal Server Error';
            exit();
        } catch(Error $e) {
            var_dump($e->getMessage());
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
