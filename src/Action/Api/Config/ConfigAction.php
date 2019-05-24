<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Action\Api\Config;

use Ixocreate\Admin\Config\Client\ClientConfigGenerator;
use Ixocreate\Admin\Entity\User;
use Ixocreate\Admin\Response\ApiSuccessResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ConfigAction implements MiddlewareInterface
{
    /**
     * @var ClientConfigGenerator
     */
    private $clientConfigGenerator;

    public function __construct(ClientConfigGenerator $clientConfigGenerator)
    {
        $this->clientConfigGenerator = $clientConfigGenerator;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return new ApiSuccessResponse($this->clientConfigGenerator->generate($request->getAttribute(User::class, null)));
    }
}
