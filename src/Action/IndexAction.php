<?php
declare(strict_types=1);

namespace KiwiSuite\Admin\Action;

use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Plates\PlatesRenderer;

class IndexAction implements MiddlewareInterface
{
    protected $templateRenderer;

    public function __construct()
    {
        $this->templateRenderer = new PlatesRenderer();
        $this->templateRenderer->addPath(__DIR__ . '/../../templates/admin', 'admin');
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // TODO: read, cache and share contents of admin-frontend assets folder (scripts & css file names)
        // TODO: read project config for white labeling / deep merge with default config

        $data = [
            'title'       => 'Kiwi CMF',
            'description' => 'Kiwi CMF Admin Area',
            'author'      => 'kiwi suite GmbH',
            'baseUrl'     => '/admin/',
            'assetsUrl'   => '/assets/admin/',
            'styles'      => [
                'styles.5c4b74b2975be54d4f58.bundle.css',
            ],
            'scripts'     => [
                'inline.59af9aa1ba51e73d4f61.bundle.js',
                'polyfills.83c2cf10225eb4ddb836.bundle.js',
                'scripts.e6fa46938b14bf2a795f.bundle.js',
                'main.747c6df7e803fbdae74c.bundle.js',
            ],
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

        return new HtmlResponse($this->templateRenderer->render('admin::index', $data));
    }
}
