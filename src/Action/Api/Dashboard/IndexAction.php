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

namespace KiwiSuite\Admin\Action\Api\Dashboard;

use KiwiSuite\Admin\Dashboard\DashboardWidgetCollector;
use KiwiSuite\Admin\Dashboard\DashboardWidgetProviderSubManager;
use KiwiSuite\Admin\Entity\User;
use KiwiSuite\Admin\Response\ApiSuccessResponse;
use KiwiSuite\Contract\Admin\DashboardWidgetProviderInterface;
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
        $collector = new DashboardWidgetCollector();

        $services = $this->dashboardWidgetSubManager->getServices();
        if (!empty($services)) {
            foreach ($services as $serviceName) {
                /** @var DashboardWidgetProviderInterface $provider */
                $provider = $this->dashboardWidgetSubManager->get($serviceName);
                $provider->provide($collector, $request->getAttribute(User::class));
            }
        }
        return new ApiSuccessResponse(['items' => $collector->widgets()]);
    }
}
