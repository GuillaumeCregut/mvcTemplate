<?php

namespace Editiel98\Kernel\WebInterface;

use Editiel98\Kernel\Exception\WebInterfaceException;

class RequestHandler
{
    public const HEADER_FORWARDED = 0b000001; // When using RFC 7239
    public const HEADER_X_FORWARDED_FOR = 0b000010;
    public const HEADER_X_FORWARDED_HOST = 0b000100;
    public const HEADER_X_FORWARDED_PROTO = 0b001000;
    public const HEADER_X_FORWARDED_PORT = 0b010000;
    public const HEADER_X_FORWARDED_PREFIX = 0b100000;

    public const HEADER_X_FORWARDED_AWS_ELB = 0b0011010; // AWS ELB doesn't send X-Forwarded-Host
    public const HEADER_X_FORWARDED_TRAEFIK = 0b0111110; // All "X-Forwarded-*" headers sent by Traefik reverse proxy

    public const METHOD_HEAD = 'HEAD';
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_PATCH = 'PATCH';
    public const METHOD_DELETE = 'DELETE';
    public const METHOD_PURGE = 'PURGE';
    public const METHOD_OPTIONS = 'OPTIONS';
    public const METHOD_TRACE = 'TRACE';
    public const METHOD_CONNECT = 'CONNECT';

    protected bool $overRideMethod = false;

    //For $_POST datas
    public ReadOnlyContainer $request;

    //For GET datas
    public ReadOnlyContainer $query;

    //For $_Server
    public ReadOnlyContainer $server;

    //For $_FILES
    public FilesContainer $files;

    public SessionContainer $session;

    //Voir pour les headers ?!

    public ReadWriteContainer $cookies;

    public ReadWriteContainer $infos;

    private static ?RequestHandler $instance = null;

    public static function getInstance(): RequestHandler
    {
        if (self::$instance === null) {
            self::$instance = new RequestHandler();
        }
        return self::$instance;
    }

    /**
     * @param mixed[] $get
     * @param mixed[] $post
     * @param mixed[] $server
     * @param mixed[] $cookies
     * @param mixed[] $session
     * @return void
     */
    public function init(array $get, array $post, array $server, array $cookies, array $session): void
    {
        $this->cookies = new ReadWriteContainer($cookies);
        $this->query = new ReadOnlyContainer($get);
        $this->request = new ReadOnlyContainer($post);
        $this->server = new ReadOnlyContainer($server);
        $this->session = new SessionContainer($session);
        $this->infos = new ReadWriteContainer([]);
        //Check if override
        $this->overRideMethod = $this->testOveride();
    }

    private function testOveride(): bool
    {
        try {
            if ($this->getServerInfo('REQUEST_METHOD') === 'POST') {
                if ($this->request->hasKey('override_method')) {
                    return true;
                }
            }
            return false;
        } catch (WebInterfaceException $e) {
            return false;
        }
    }

    public function getURI(): string
    {
        return $this->getServerInfo('REQUEST_URI');
    }

    public function getMethod(): string
    {
        if ($this->overRideMethod) {
            $newMethod = $this->request->getParam('override_method');
            if (in_array($newMethod, ['POST', 'PUT', 'DELETE', 'PATCH'])) {
                return $newMethod;
            }
            return $this->getServerInfo('REQUEST_METHOD');
        }
        return $this->getServerInfo('REQUEST_METHOD');
    }

    public function getServerKey(string $key): string
    {
        return $this->getServerInfo($key);
    }

    private function getServerInfo(string $key): string
    {
        return $this->getContainerValue($this->server, $key);
    }

    private function getContainerValue(ElementContainer $container, string $key): string
    {
        try {
            if ($container->hasKey($key)) {
                return $container->getParam($key);
            } else {
                throw new WebInterfaceException(sprintf(" the key %s doesn't exist", $key));
            }
        } catch (WebInterfaceException $e) {
            throw new WebInterfaceException(sprintf(" the key %s doesn't exist", $key));
        }
    }

    /**
     * @return string
     */
    function getContentType(): string | null
    {
        try {
            return $this->getServerKey('CONTENT_TYPE');
        } catch (WebInterfaceException $e) {
            return null;
        }
    }

    /**
     * @return string
     */
    public function getContentLength(): string | null
    {
        try {
            return $this->getServerKey('CONTENT_LENGTH');
        } catch (WebInterfaceException $e) {
            return null;
        }
    }

    /**
     * @return string
     */
    public function getContentOrigin(): string | null
    {
        try {
            return $this->getServerKey('HTTP_ORIGIN');
        } catch (WebInterfaceException $e) {
            return null;
        }
    }

    /**
     * @return string
     */
    public function getServerProtocol(): string | false
    {
        if ($this->server->hasKey('SERVER_PROTOCOL')) {
            return $this->server->getParam('SERVER_PROTOCOL');
        }
        return false;
    }
}
