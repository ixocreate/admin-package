<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\CustomButton;

use Zend\Stdlib\SplPriorityQueue;

final class CustomButtonCollector implements CustomButtonCollectorInterface
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
     * @param CustomButtonInterface $customButton
     */
    public function add(CustomButtonInterface $customButton): void
    {
        $this->queue->insert($customButton, $customButton->priority());
    }

    public function customButtons(): array
    {
        if ($this->queue->isEmpty()) {
            return [];
        }

        $this->queue->top();
        return $this->queue->toArray();
    }
}
