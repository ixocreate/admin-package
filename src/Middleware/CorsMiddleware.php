<?php
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package kiwi-suite/admin
 * @see https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace KiwiSuite\Admin\Middleware;

use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use KiwiSuite\Config\Config;
use KiwiSuite\ProjectUri\ProjectUri;
use Neomerx\Cors\Analyzer;
use Neomerx\Cors\Contracts\AnalysisResultInterface;
use Neomerx\Cors\Strategies\Settings;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

final class CorsMiddleware implements MiddlewareInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var ProjectUri
     */
    private $projectUri;

    /**
     * CorsMiddleware constructor.
     * @param ProjectUri $projectUri
     * @param Config $config
     */
    public function __construct(Config $config, ProjectUri $projectUri)
    {
        $this->config = $config;
        $this->projectUri = $projectUri;
    }


    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $corsSettings = new Settings([
            Settings::KEY_SERVER_ORIGIN => [
                Settings::KEY_SERVER_ORIGIN_SCHEME => $this->projectUri->getMainUrl()->getScheme(),
                Settings::KEY_SERVER_ORIGIN_HOST => $this->projectUri->getMainUrl()->getHost(),
            ],
            Settings::KEY_ALLOWED_ORIGINS => $this->config->get('admin.security.allow'),
            Settings::KEY_ALLOWED_METHODS => [
                'GET' => true,
                'POST' => true,
                'DELETE' => true,
                'PUT' => true,
            ],
            Settings::KEY_ALLOWED_HEADERS => [
                'origin' => true,
                'x-xsrf-token' => true,
                'access-control-request-headers' => true,
                'content-type' => true,
                'access-control-request-method' => true,
                'accept' => true,
            ],
            Settings::KEY_EXPOSED_HEADERS => [
            ],
            Settings::KEY_IS_USING_CREDENTIALS => true,
            Settings::KEY_FLIGHT_CACHE_MAX_AGE => 1800,
            Settings::KEY_IS_FORCE_ADD_METHODS => true,
            Settings::KEY_IS_FORCE_ADD_HEADERS => true,
            Settings::KEY_IS_CHECK_HOST => false,
        ]);

        $cors = Analyzer::instance($corsSettings)->analyze($request);

        switch ($cors->getRequestType()) {
            case AnalysisResultInterface::ERR_NO_HOST_HEADER:
            case AnalysisResultInterface::ERR_ORIGIN_NOT_ALLOWED:
            case AnalysisResultInterface::ERR_METHOD_NOT_SUPPORTED:
            case AnalysisResultInterface::ERR_HEADERS_NOT_SUPPORTED:
                $headers = ['content-type' => 'application/json'];
                return new Response\EmptyResponse(401, $headers);

            case AnalysisResultInterface::TYPE_PRE_FLIGHT_REQUEST:
                $corsHeaders = $cors->getResponseHeaders();
                $corsHeaders['content-type'] = 'application/json';
                return new Response\EmptyResponse(200, $corsHeaders);

            case AnalysisResultInterface::TYPE_REQUEST_OUT_OF_CORS_SCOPE:
                return $handler->handle($request);

            default:
                $response = $handler->handle($request);
                $corsHeaders = $cors->getResponseHeaders();
                foreach ($corsHeaders as $headerName => $heaverValue) {
                    $response = $response->withAddedHeader($headerName, $heaverValue);
                }
                return $response;
        }
    }
}
