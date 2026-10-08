<?php

declare(strict_types=1);

namespace Pixelant\Demander\Service;

use Psr\Http\Message\ServerRequestInterface;

class RequestSingleton
{
    private static $uniqueInstance = null;

    /**
     * @var ServerRequestInterface
     */
    private ?ServerRequestInterface $request = null;

    public static function getInstance(): ?self
    {
        if (self::$uniqueInstance === null) {
            self::$uniqueInstance = new self();
        }

        return self::$uniqueInstance;
    }

    protected function __construct()
    {
    }

    private function __clone()
    {
    }

    public function getRequest(): ?ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * @param ServerRequestInterface $request
     * @return RequestSingleton
     */
    public function setRequest(?ServerRequestInterface $request): self
    {
        $this->request = $request;

        return $this;
    }
}
