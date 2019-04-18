<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package\Config\Navigation;

use Zend\Stdlib\SplPriorityQueue;

final class Item extends SubItem
{
    /**
     * @var Item[]
     */
    private $items = [];

    /**
     * @param string $name
     * @param array $permissions
     * @param string $icon
     * @param string $url
     * @param int $priority
     */
    public function add(string $name, array $permissions, string $icon, string $url, int $priority = 0): void
    {
        $item = new SubItem($name, $permissions, $icon, $url, $priority);
        $this->items[$item->getName()] = $item;
    }

    /**
     * @param SubItem $item
     */
    public function remove(SubItem $item): void
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
    public function get(string $name): SubItem
    {
        return $this->items[$name];
    }

    public function toArray(): array
    {
        $array = parent::toArray();

        $array['children'] = [];

        if (!empty($this->items)) {
            $queue = new SplPriorityQueue();
            foreach ($this->items as $item) {
                $queue->insert($item, $item->getPriority());
            }

            $queue->top();
            /** @var Item $item */
            foreach ($queue as $item) {
                $array['children'][] = $item->toArray();
            }
        }

        return $array;
    }
}
