<?php

namespace Editiel98;

use Editiel98\Chore\Emitter;
use Editiel98\Chore\Logger\ErrorLogger;
use Editiel98\Chore\Logger\WarnLogger;
use App\Router\Routing;
use Error;
use Exception;
use ReflectionClass;

class App
{
    private Emitter $emitter;

    public function run()
    {
        $this->setEmitter();
        //$test=RegisterController::registerContoller('\App\Controller\TestController');
        RegisterController::getControllers();
        $controllerInfos = $this->decodeURI();
        if (empty($controllerInfos)) {
            header("HTTP/1.0 404 Not Found");
            echo '404 - Not Found';
            die();
        }
        try {
            $controllerName = '\\App\\Controller\\' . $controllerInfos[0];
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
     * Get controller and method from routing
     * @return array : array with
     * 0=>controller
     * 1=>method
     * 2=>params [key=>alue]
     */
    private function decodeURI(): array
    {
        $url = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '', '/');
        $route = Routing::getRoute($url);
        if (!$route) {
            return [];
        }
        $parameters = [];
        foreach ($route[3] ?? [] as $parameter) {
            if (isset($_GET[$parameter])) {
                $parameters[$parameter] =  $_GET[$parameter];
            }
        }
        return [$route[0], $route[1], '',$parameters];
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
