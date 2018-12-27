<?php
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package kiwi-suite/admin
 * @link https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Action;

use Ixocreate\Admin\Config\AdminConfig;
use Ixocreate\Admin\Router\AdminRouter;
use Ixocreate\ProjectUri\ProjectUri;
use Ixocreate\Template\TemplateResponse;
use PackageVersions\Versions;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class IndexAction implements MiddlewareInterface
{
    /**
     * @var AdminConfig
     */
    protected $adminConfig;

    /**
     * @var ProjectUri
     */
    protected $projectUri;
    /**
     * @var AdminRouter
     */
    private $adminRouter;

    /**
     * IndexAction constructor.
     * @param AdminConfig $adminConfig
     * @param AdminRouter $adminRouter
     */
    public function __construct(AdminConfig $adminConfig, AdminRouter $adminRouter)
    {
        $this->adminConfig = $adminConfig;
        $this->adminRouter = $adminRouter;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return new TemplateResponse('admin::index', [
            'assets' => $this->assetsPaths(),
            'adminConfig' => $this->adminConfig,
        ]);
    }

    /**
     * @return array
     */
    private function assetsPaths()
    {
        $scripts = [
            'runtime' => null,
            'polyfills' => null,
            'scripts' => null,
            'main' => null,
        ];

        $styles = [
            'styles' => null,
        ];


        foreach (\array_keys($this->adminConfig->adminBuildFiles()) as $name) {
            foreach ($scripts as $scriptName => $value) {
                if ($value !== null) {
                    continue;
                }
                if (\mb_substr($name, 0, \mb_strlen($scriptName)) === $scriptName) {
                    $scripts[$scriptName] = $this->adminRouter->generateUri('admin.static', ['file' => $name]) . '?v=' . Versions::getVersion(Versions::ROOT_PACKAGE_NAME);

                    continue 2;
                }
            }

            foreach ($styles as $stylesName => $value) {
                if ($value !== null) {
                    continue;
                }

                if (\mb_substr($name, 0, \mb_strlen($stylesName)) === $stylesName) {
                    $styles[$stylesName] = $this->adminRouter->generateUri('admin.static', ['file' => $name]) . '?v=' . Versions::getVersion(Versions::ROOT_PACKAGE_NAME);
                    continue 2;
                }
            }
        }

        return ['scripts' => $scripts, 'styles' => $styles];
    }
}
