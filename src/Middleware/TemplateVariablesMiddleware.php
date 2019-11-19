<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Middleware;

use Ixocreate\Admin\Config\AdminConfig;
use Ixocreate\Admin\Config\Client\ClientConfigGenerator;
use Ixocreate\Admin\Entity\SessionData;
use Ixocreate\Admin\Entity\User;
use Ixocreate\Admin\Router\AdminRouter;
use Ixocreate\Admin\Template\AdminExtension;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TemplateVariablesMiddleware implements MiddlewareInterface
{
    const GLOBAL_DATA = 'admin_template_global_data';

    /**
     * @var AdminConfig
     */
    protected $adminConfig;

    /**
     * @var AdminRouter
     */
    protected $adminRouter;

    /**
     * @var AdminExtension
     */
    private $adminExtension;

    /**
     * @var ClientConfigGenerator
     */
    private $clientConfigGenerator;

    public function __construct(
        AdminConfig $adminConfig,
        AdminRouter $adminRouter,
        ClientConfigGenerator $clientConfigGenerator,
        AdminExtension $adminExtension
    ) {
        $this->adminConfig = $adminConfig;
        $this->adminRouter = $adminRouter;
        $this->adminExtension = $adminExtension;
        $this->clientConfigGenerator = $clientConfigGenerator;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $scripts = [
            '/admin/js/manifest.js',
            '/admin/js/vendor.js',
            '/admin/js/main.js',
        ];
        $styles = [
            '/admin/css/styles.css',
        ];

        /**
         * TODO: validate login etc actions csrf implementation
         */
        $csrf = $request->getCookieParams()['XSRF-TOKEN'] ?? null;

        /** @var SessionData $sessionData */
        $sessionData = $request->getAttribute(SessionData::class, null);
        if ($sessionData) {
            $csrf = $sessionData->xsrfToken()->value();
        }

        return $handler->handle(
            $request->withAttribute(self::GLOBAL_DATA, [
                'csrf' => $csrf,
                'project' => $this->adminConfig,
                'config' => $this->clientConfigGenerator->generate($request->getAttribute(User::class)),
                'router' => $this->adminRouter,
                'request' => $request,
                'styles' => $styles,
                'scripts' => $scripts,
            ])
        );
    }
}
