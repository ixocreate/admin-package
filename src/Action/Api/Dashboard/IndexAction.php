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

use KiwiSuite\Admin\Dashboard\DashboardWidgetSubManager;
use KiwiSuite\Admin\Response\ApiSuccessResponse;
use KiwiSuite\Contract\Admin\DashboardWidgetInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Stdlib\SplPriorityQueue;

final class IndexAction implements MiddlewareInterface
{
    /**
     * @var DashboardWidgetSubManager
     */
    private $dashboardWidgetSubManager;

    /**
     * IndexAction constructor.
     *
     * @param DashboardWidgetSubManager $dashboardWidgetSubManager
     */
    public function __construct(DashboardWidgetSubManager $dashboardWidgetSubManager)
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
        $services = $this->dashboardWidgetSubManager->getServices();
        $queue = new SplPriorityQueue();
        if (!empty($services)) {
            foreach ($services as $serviceName) {
                /** @var DashboardWidgetInterface $widget */
                $widget = $this->dashboardWidgetSubManager->get($serviceName);
                $queue->insert($widget, $widget->priority());
            }
            $queue->top();
        }
        return new ApiSuccessResponse(['items' => $queue->toArray(), 'meta' => ['count' => $queue->count()]]);
    }
}
