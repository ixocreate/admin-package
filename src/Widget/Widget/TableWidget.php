<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Widget\Widget;

use Ixocreate\Contract\Admin\Widget\WidgetInterface;

final class TableWidget implements WidgetInterface
{
    /**
     * @var int
     */
    private $size = self::SIZE_LARGE;

    /**
     * @var int
     */
    private $priority = 100;

    /**
     * @var array
     */
    private $data = [
        'schema' => null,
        'uri' => null
    ];

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
        return 'table';
    }

    /**
     * @param int $size
     * @return TableWidget
     */
    public function withSize(int $size): TableWidget
    {
        $widget = clone $this;
        $widget->size = $size;

        return $widget;
    }

    /**
     * @param int $priority
     * @return TableWidget
     */
    public function withPriority(int $priority): TableWidget
    {
        $widget = clone $this;
        $widget->priority = $priority;

        return $widget;
    }

    /**
     * @param string $resource
     * @param string $url
     * @return TableWidget
     */
    public function withData(string $resource, string $url): TableWidget
    {
        $widget = clone $this;
        $widget->data = [
            'resource' => $resource,
            'url' => $url,
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
     * @return array|mixed
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
