<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package\Widget\Widget;

use Ixocreate\Admin\Widget\WidgetInterface;

final class CounterWidget implements WidgetInterface
{
    /**
     * @var int
     */
    private $size = self::SIZE_SMALL;

    /**
     * @var int
     */
    private $priority = 100;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @return int
     */
    public function size(): int
    {
        return $this->size;
    }

    /**
     * @return int
     */
    public function priority(): int
    {
        return $this->priority;
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return 'counter';
    }

    /**
     * @param int $size
     * @return CounterWidget
     */
    public function withSize(int $size): CounterWidget
    {
        $widget = clone $this;
        $widget->size = $size;

        return $widget;
    }

    /**
     * @param int $priority
     * @return CounterWidget
     */
    public function withPriority(int $priority): CounterWidget
    {
        $widget = clone $this;
        $widget->priority = $priority;

        return $widget;
    }

    /**
     * @param string $icon
     * @param string $title
     * @param string $counter
     * @param string $color
     * @return CounterWidget
     */
    public function withData(string $icon, string $title, string $counter, string $color): CounterWidget
    {
        $widget = clone $this;
        $widget->data = [
            'icon' => $icon,
            'title' => $title,
            'counter' => $counter,
            'color' => $color,
        ];

        return $widget;
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * Specify data which should be serialized to JSON
     * @see https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'size' => $this->size(),
            'type' => $this->type(),
            'data' => $this->data(),
        ];
    }
}
