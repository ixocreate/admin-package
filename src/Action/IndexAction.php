<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Action;

use Ixocreate\Admin\Middleware\TemplateVariablesMiddleware;
use Ixocreate\Template\TemplateResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class IndexAction implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return new TemplateResponse(
            'admin::index',
            [],
            \array_merge(
                $request->getAttribute(TemplateVariablesMiddleware::GLOBAL_DATA, []),
                [
                    // 'title' => '...',
                ]
            )
        );
    }
}
