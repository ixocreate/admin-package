<?php
declare(strict_types=1);

namespace KiwiSuite\Admin\Action;

use FilesystemIterator;
use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SplFileInfo;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Plates\PlatesRenderer;

class IndexAction implements MiddlewareInterface
{
    protected $templateRenderer;

    public function __construct()
    {
        // TODO: inject a TemplateRendererInterface
        $this->templateRenderer = new PlatesRenderer();
        $this->templateRenderer->addPath(__DIR__ . '/../../templates/admin', 'admin');
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // TODO: read, cache and share contents of admin-frontend assets folder (scripts & css file names)
        // TODO: read project config for white labeling / deep merge with default config

        $data = \array_merge($this->assetsPaths(), $this->config());

        return new HtmlResponse($this->templateRenderer->render('admin::index', $data));
    }

    /**
     * @return array
     */
    private function config()
    {
        return [
            'title'       => 'Kiwi CMF',
            'description' => 'Kiwi CMF Admin Area',
            'author'      => 'kiwi suite GmbH',
            'baseUrl'     => '/admin/',
            'assetsUrl'   => '/assets/admin/',
            'config'      => [
                'apiUrl'     => 'https://kiwi.test/api/',
                'configPath' => 'config',
                'authPath'   => 'auth',
                'project'    => [
                    'name'      => 'Kiwi CMF',
                    'copyright' => '2018 kiwi suite GmbH',
                    'poweredBy' => true,
                ],
            ],
        ];
    }

    /**
     * automatically grab angular asset names from build directory
     *
     * @return array
     */
    private function assetsPaths()
    {
        $scripts = [
            'inline'    => null,
            'polyfills' => null,
            'scripts'   => null,
            'main'      => null,
        ];

        $styles = [
            'styles' => null,
        ];

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
