<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Action\Api\Dashboard;

use Ixocreate\Admin\Entity\User;
use Ixocreate\Admin\Response\ApiSuccessResponse;
use Ixocreate\Admin\Widget\DashboardWidgetProviderSubManager;
use Ixocreate\Admin\Widget\WidgetCollector;
use Ixocreate\Admin\Widget\WidgetProviderInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class IndexAction implements MiddlewareInterface
{
    /**
     * @var DashboardWidgetProviderSubManager
     */
    private $dashboardWidgetSubManager;

    /**
     * IndexAction constructor.
     *
     * @param DashboardWidgetProviderSubManager $dashboardWidgetSubManager
     */
    public function __construct(DashboardWidgetProviderSubManager $dashboardWidgetSubManager)
    {
        $this->dashboardWidgetSubManager = $dashboardWidgetSubManager;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $collector = new WidgetCollector();

        $services = $this->dashboardWidgetSubManager->services();
        if (!empty($services)) {
            foreach ($services as $serviceName) {
                /** @var WidgetProviderInterface $provider */
                $provider = $this->dashboardWidgetSubManager->get($serviceName);
                $provider->provide($collector, $request->getAttribute(User::class));
            }
        }
        return new ApiSuccessResponse(['items' => $collector->widgets()]);
    }
}
