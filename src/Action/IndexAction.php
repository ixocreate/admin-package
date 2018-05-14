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
use KiwiSuite\ProjectUri\ProjectUri;
use KiwiSuite\Template\TemplateResponse;
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
     * IndexAction constructor.
     * @param AdminConfig $adminConfig
     * @param ProjectUri $projectUri
     */
    public function __construct(AdminConfig $adminConfig, ProjectUri $projectUri)
    {
        $this->adminConfig = $adminConfig;
        $this->projectUri = $projectUri;
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
     * automatically read contents of admin-frontend assets folder (scripts & css file names)
     *
     * TODO: cache
     *
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

        /**
         * prefer embedded path
         */
        $path = \getcwd() . '/resources/admin/build';

        /**
         * look up assets by name
         */
        $fileSystemIterator = new FilesystemIterator($path);
        /** @var SplFileInfo $fileInfo */
        foreach ($fileSystemIterator as $fileInfo) {
            if ($fileInfo->getExtension() === 'js') {
                $assetName = \explode('.', $fileInfo->getFilename())[0] ?? null;
                if (\in_array($assetName, \array_keys($scripts))) {
                    $scripts[$assetName] = '/admin/' . $fileInfo->getFilename();
                }
            } elseif ($fileInfo->getExtension() === 'css') {
                $assetName = \explode('.', $fileInfo->getFilename())[0] ?? null;
                if (\in_array($assetName, \array_keys($styles))) {
                    $styles[$assetName] = '/admin/' . $fileInfo->getFilename();
                }
            }
        }

        return ['scripts' => $scripts, 'styles' => $styles];
    }
}
