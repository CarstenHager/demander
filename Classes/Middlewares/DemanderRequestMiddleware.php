<?php

declare(strict_types=1);

namespace Pixelant\Demander\Middlewares;

use Pixelant\Demander\Service\RequestSingleton;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DemanderRequestMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $storage = RequestSingleton::getInstance();
        $previousRequest = $storage->getRequest();
        $storage->setRequest($request);
        try {
            return $handler->handle($request);
        } finally {
            $storage->setRequest($previousRequest);
        }
    }
}
