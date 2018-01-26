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
use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use KiwiSuite\Admin\Config\AdminConfig;
use KiwiSuite\Config\Config;
use KiwiSuite\ProjectUri\ProjectUri;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SplFileInfo;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Plates\PlatesRenderer;

class IndexAction implements MiddlewareInterface
{
    /**
     * @var PlatesRenderer
     */
    protected $renderer;

    /**
     * @var AdminConfig
     */
    protected $adminConfig;

    /**
     * @var ProjectUri
     */
    protected $projectUri;

    /**
     * IndexAction constructor.
     * @param AdminConfig $adminConfig
     * @param ProjectUri $projectUri
     * @param PlatesRenderer $renderer
     */
    public function __construct(AdminConfig $adminConfig, ProjectUri $projectUri, PlatesRenderer $renderer)
    {
        $this->adminConfig = $adminConfig;
        $this->projectUri = $projectUri;
        $this->renderer = $renderer;

        // TODO: inject a TemplateRendererInterface
        $this->renderer->addPath(__DIR__ . '/../../templates/admin', 'admin');
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return new HtmlResponse($this->renderer->render('admin::index', [
            'assets' => $this->assetsPaths(),
            'assetsUrl' => $this->projectUri->getMainUrl() . '/assets/admin/',
            'adminConfig' => $this->adminConfig,
        ]));
    }

    /**
     * automatically read contents of admin-frontend assets folder (scripts & css file names)
     *
     * TODO: cache
     *
     * @return array
     */
    private function assetsPaths()
    {
        $scripts = [
            'inline' => null,
            'polyfills' => null,
            'scripts' => null,
            'main' => null,
        ];

        $styles = [
            'styles' => null,
        ];

        /**
         * TODO: get build/dist of frontend by config
         */

        /**
         * admin and admin-frontend are siblings in a vendor folder
         */
        $embeddedPath = \realpath(__DIR__ . '/../../../admin-frontend/build');

        /**
         * admin-frontend is a local dependency of admin for development
         */
        $standalonePath = \realpath(__DIR__ . '/../../vendor/kiwi-suite/admin-frontend/build');

        /**
         * prefer embedded path
         */
        $path = \file_exists($embeddedPath) ? $embeddedPath : $standalonePath;

        /**
         * look up assets by name
         */
        $fileSystemIterator = new FilesystemIterator($path);
        /** @var SplFileInfo $fileInfo */
        foreach ($fileSystemIterator as $fileInfo) {
            if ($fileInfo->getExtension() === 'js') {
                $assetName = \explode('.', $fileInfo->getFilename())[0] ?? null;
                if (\in_array($assetName, \array_keys($scripts))) {
                    $scripts[$assetName] = $fileInfo->getFilename();
                }
            } elseif ($fileInfo->getExtension() === 'css') {
                $assetName = \explode('.', $fileInfo->getFilename())[0] ?? null;
                if (\in_array($assetName, \array_keys($styles))) {
                    $styles[$assetName] = $fileInfo->getFilename();
                }
            }
        }

        return ['scripts' => $scripts, 'styles' => $styles];
    }
}
