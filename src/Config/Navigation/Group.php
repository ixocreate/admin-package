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

namespace KiwiSuite\Admin\Config\Navigation;

use Zend\Stdlib\SplPriorityQueue;

class Group
{
    /**
     * @var
     */
    private $name;

    /**
     * @var Item[]
     */
    private $items = [];

    /**
     * @var int
     */
    private $priority;

    /**
     * Group constructor.
     * @param string $name
     * @param int $priority
     */
    public function __construct(string $name, int $priority)
    {
        $this->setName($name);
        $this->setPriority($priority);
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param int $priority
     */
    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @param string $name
     * @param array $permissions
     * @param string $icon
     * @param string $url
     * @param int $priority
     */
    public function add(string $name, array $permissions, string $icon, string $url, int $priority = 0): void
    {
        $item = new Item($name, $permissions, $icon, $url, $priority);
        $this->items[$item->getName()] = $item;
    }

    /**
     * @param Item $item
     */
    public function remove(Item $item): void
    {
        if (!\array_key_exists($item->getName(), $this->items)) {
            return;
        }

        unset($this->items[$item->getName()]);
    }

    /**
     * @param string $name
     * @return Item
     */
    public function get(string $name): Item
    {
        return $this->items[$name];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $result = [
            'name' => $this->name,
            'children' => [],
        ];

        if (!empty($this->items)) {
            $queue = new SplPriorityQueue();
            foreach ($this->items as $item) {
                $queue->insert($item, $item->getPriority());
            }

            $queue->top();
            /** @var Item $item */
            foreach ($queue as $item) {
                $result['children'][] = $item->toArray();
            }
        }

        return $result;
    }
}
