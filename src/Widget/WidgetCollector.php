<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package\Widget;

use Ixocreate\Admin\DashboardWidgetInterface;
use Ixocreate\Admin\Widget\WidgetCollectorInterface;
use Ixocreate\Admin\Widget\WidgetInterface;
use Zend\Stdlib\SplPriorityQueue;

final class WidgetCollector implements WidgetCollectorInterface
{
    /**
     * @var SplPriorityQueue
     */
    private $queue;

    public function __construct()
    {
        $this->queue = new SplPriorityQueue();
    }

    /**
     * @param WidgetInterface $widget
     */
    public function add(WidgetInterface $widget): void
    {
        $this->queue->insert($widget, $widget->priority());
    }

    /**
     * @return DashboardWidgetInterface[]
     */
    public function widgets(): array
    {
        if ($this->queue->isEmpty()) {
            return [];
        }

        $this->queue->top();
        return $this->queue->toArray();
    }
}
