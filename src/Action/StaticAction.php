<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Action;

use Ixocreate\Admin\Config\AdminConfig;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;

final class StaticAction implements MiddlewareInterface
{
    /**
     * @var AdminConfig
     */
    private $adminConfig;

    public function __construct(AdminConfig $adminConfig)
    {
        $this->adminConfig = $adminConfig;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $file = $this->adminConfig->adminBuildPath() . $request->getAttribute('file', '');

        if (!(\file_exists($file) && !\is_dir($file))) {
            return new Response\EmptyResponse(404);
        }

        if (empty($this->adminConfig->adminBuildFiles()[$request->getAttribute('file', '')])) {
            return new Response\EmptyResponse(404);
        }

        $fileInfo = $this->adminConfig->adminBuildFiles()[$request->getAttribute('file', '')];

        $response = new Response(new Stream($file));
        $response = $response->withAddedHeader('Content-Type', $fileInfo['contentType']);
        $response = $response->withAddedHeader('Content-Length', $fileInfo['filesize']);

        return $response;
    }
}
