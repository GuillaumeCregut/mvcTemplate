<?php

namespace Editiel98\Kernel\WebInterface;

use InvalidArgumentException;

class Cookie
{
    public const SAME_SITE_STRICT = 'strict';
    public const SAME_SITE_LAX = 'lax';
    public const SAME_SITE_NONE = 'none';

    private string $name;
    private ?string $value;
    private ?string $domain;
    private ?int $expire;
    private string $path;
    private ?bool $secure;
    private bool $httpOnly;
    private ?string $sameSite;

    public function __construct(
        string $name,
        ?string $value = null,
        ?string $expire = null,
        ?string $domain = null,
        ?string $path = null,
        ?bool $secure = false,
        ?bool $httpOnly = false,
        ?string $sameSite = self::SAME_SITE_STRICT
    ) {
        $this->name = $name;
        $this->value = $value;
        $handler = RequestHandler::getInstance();
        $requestHost = $handler->server->getParam('HTTP_HOST');
        $this->domain = $domain ?? $requestHost;
        $this->expire = $this->getExpire($expire);
        $this->path = $path ??  $handler->getURI();
        $this->secure = $secure;
        $this->httpOnly = $httpOnly;
        $this->sameSite = $sameSite;
    }

    private function getExpire(?string $expire = ''): int
    {
        if (null === $expire) {
            $delay = 0;
        } else {
            $delay = strtotime($expire);
            if (false === $delay) {
                throw new InvalidArgumentException('The expiration is not a valid time');
            }
        }
        return $delay;
    }

    public function toString(): string
    {
        $value = $this->value;
        if ($value === '') {
            $value = null;
        }
        $toReturn = $this->name . '=';
        $toReturn .= null !== $value ? $value . ';' : 'deleted' . ';';
        if ($this->expire === 0) {
            $expireDate = gmdate('d M Y H:m:s', 0);
            $maxAge = 0;
        } else {
            $expireDate = gmdate('d M Y H:m:s', $this->expire);
            $maxAge = $this->expire - strtotime('now');
        }
        $toReturn .= 'expires=' . $expireDate . ' GMT;';
        $toReturn .= 'Max-Age=' . $maxAge . ';';

        $toReturn .= 'domain=' . $this->domain . ';';
        $toReturn .= 'path=' . $this->path . ';';
        $toReturn .= 'SameSite=' . $this->sameSite . ';';
        if ($this->secure) {
            $toReturn .= 'secure;';
        }
        if ($this->httpOnly) {
            $toReturn .= 'httpOnly';
        }
        return $toReturn;
    }

    /**
     * Get the value of name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the value of value
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * Set the value of value
     */
    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
