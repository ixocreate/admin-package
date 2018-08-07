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

namespace KiwiSuite\Admin\Action;

use FilesystemIterator;
use KiwiSuite\Admin\Config\AdminConfig;
use KiwiSuite\Admin\Router\AdminRouter;
use KiwiSuite\ProjectUri\ProjectUri;
use KiwiSuite\Template\TemplateResponse;
use PackageVersions\Versions;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SplFileInfo;

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


        foreach (array_keys($this->adminConfig->adminBuildFiles()) as $name) {
            foreach ($scripts as $scriptName => $value) {
                if ($value !== null) {
                    continue;
                }
                if (substr($name, 0, strlen($scriptName)) === $scriptName) {
                    $scripts[$scriptName] = $this->adminRouter->generateUri('admin.static', ['file' => $name]). '?v=' .  Versions::getVersion(Versions::ROOT_PACKAGE_NAME);

                    continue 2;
                }
            }

            foreach ($styles as $stylesName => $value) {
                if ($value !== null) {
                    continue;
                }

                if (substr($name, 0, strlen($stylesName)) === $stylesName) {
                    $styles[$stylesName] = $this->adminRouter->generateUri('admin.static', ['file' => $name]) . '?v=' .  Versions::getVersion(Versions::ROOT_PACKAGE_NAME);
                    continue 2;
                }
            }
        }

        return ['scripts' => $scripts, 'styles' => $styles];
    }
}
