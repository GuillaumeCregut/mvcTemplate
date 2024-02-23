<?php

namespace Editiel98\Kernel\WebInterface;

class ResponseHandler
{
    private string $content;
    private string $statusText;
    private int $statusCode;
    private ?string $charset = null;
    private string $version;
    private Cookies $cookies;
    private Headers $headers;
    private RequestHandler $handler;
    private bool $init = false;
    private bool $headersSent = false;

    public function __construct()
    {
        $this->headers = new Headers();
        $this->cookies = Cookies::getInstance();
        $this->handler = RequestHandler::getInstance();
    }


    public function prepare(): void
    {
        if (!is_null($this->handler->getContentType())) {
            //Ajoute aux headers
        }
        $this->charset = $this->charset ?? 'UTF-8';
        if (!isset($this->version)) {
            $this->version = $this->handler->getServerProtocol() ? $this->handler->getServerProtocol() : 'HTTP/1.1';
        }
        $date = new \DateTimeImmutable();
        $date = \DateTimeImmutable::createFromInterface($date);
        $this->headers->set('Date', $date->format('D, d M Y H:i:s') . ' GMT');

        $this->init = true;
    }

    /**
     * @param int $code
     * @param string $status
     *
     * @return void
     */
    public function setHeaderResponse(int $code, string $status): void
    {
        $this->statusCode = $code;
        $this->statusText = $status;
    }

    /**
     * @return void
     */
    public function sendHeaders(?int $code = 200, ?string $status = 'OK'): void
    {
        if (!$this->headersSent  && !headers_sent()) {
            if (!isset($this->statusCode)) {
                $this->statusCode = $code;
            }
            if (!isset($this->statusText)) {
                $this->statusText = $status;
            }
            if (!$this->init) {
                $this->prepare();
            }
            //voir pour envoyer d'autres headers si besoin
            $headers = $this->headers->getHeaders();
            foreach ($headers as $key => $value) {
                header($key . ': ' . $value);
            }
            //Send Cookie
            $cookies = $this->cookies->getCookies();
            foreach ($cookies as $cookie) {
                header('Set-Cookie: ' . $cookie, false, $this->statusCode);
            }
            header(sprintf("%s %s %s", $this->version, $this->statusCode, $this->statusText), true, $this->statusCode);
        }
        $this->headersSent = true;
    }

    public function sendContent(): self
    {
        echo $this->content;
        return $this;
    }

    public function send(?int $code = 200, ?string $status = 'OK', ?string $content = ''): self
    {
        $this->sendHeaders($code, $status);
        if (!isset($this->content)) {
            $this->content = $content;
        }
        $this->sendContent();
        return $this;
    }

    /**
     * Get the value of content
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return void
     */
    public function addHeader(string $name, mixed $value): void
    {
        $this->headers->set($name, $value);
    }

    /**
     * Set the value of content
     */
    public function setContent(?string $content = ''): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of statusText
     */
    public function getStatusText(): string
    {
        return $this->statusText;
    }

    /**
     * Set the value of statusText
     */
    public function setStatusText(string $statusText): self
    {
        $this->statusText = $statusText;

        return $this;
    }

    /**
     * Get the value of statusCode
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Set the value of statusCode
     */
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Get the value of charset
     */
    public function getCharset(): ?string
    {
        return $this->charset;
    }

    /**
     * Set the value of charset
     */
    public function setCharset(?string $charset): self
    {
        $this->charset = $charset;

        return $this;
    }

    /**
     * Get the value of version
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Set the value of version
     */
    public function setVersion(string $version): self
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get the value of cookies
     */
    public function getCookies(): Cookies
    {
        return $this->cookies;
    }

    /**
     * Get the value of headers
     */
    public function getHeaders(): Headers
    {
        return $this->headers;
    }
}
