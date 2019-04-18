<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package\Widget\Widget;

use Ixocreate\Admin\Widget\WidgetInterface;

final class LineGraphWidget implements WidgetInterface
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
        'xAxisLabel' => null,
        'yAxisLabel' => null,
        'datasets' => [],
        'customColors' => [],
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
        return 'graph';
    }

    /**
     * @param int $size
     * @return LineGraphWidget
     */
    public function withSize(int $size): LineGraphWidget
    {
        $widget = clone $this;
        $widget->size = $size;

        return $widget;
    }

    /**
     * @param int $priority
     * @return LineGraphWidget
     */
    public function withPriority(int $priority): LineGraphWidget
    {
        $widget = clone $this;
        $widget->priority = $priority;

        return $widget;
    }

    /**
     * @param array $data
     * @param string $label
     * @param string $color
     * @return LineGraphWidget
     */
    public function withDataset(array $data, string $label, string $color): LineGraphWidget
    {
        $widget = clone $this;
        $widget->data['datasets'][] = [
            'name' => $label,
            'series' => $data,
        ];
        $widget->data['customColors'][] = [
            'name' => $label,
            'value' => $color,
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
