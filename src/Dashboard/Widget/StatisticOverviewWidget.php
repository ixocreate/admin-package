<?php
namespace KiwiSuite\Admin\Dashboard\Widget;

use KiwiSuite\Contract\Admin\DashboardWidgetInterface;

final class StatisticOverviewWidget implements DashboardWidgetInterface
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
        'progress' => [],
        'counter' => [],
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
        return 'statistic-overview';
    }

    /**
     * @param int $size
     * @return StatisticOverviewWidget
     */
    public function withSize(int $size): StatisticOverviewWidget
    {
        $widget = clone $this;
        $widget->size = $size;

        return $widget;
    }

    /**
     * @param int $priority
     * @return StatisticOverviewWidget
     */
    public function withPriority(int $priority): StatisticOverviewWidget
    {
        $widget = clone $this;
        $widget->priority = $priority;

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
     * @param int $current
     * @param int $max
     * @param string $title
     * @return StatisticOverviewWidget
     */
    public function withAddedProgress(int $current, int $max, string $title): StatisticOverviewWidget
    {
        $widget = clone $this;
        $widget->data['progress'][] = [
            'current' => $current,
            'max' => $max,
            'title' => $title,
        ];

        return $widget;
    }

    /**
     * @param string $icon
     * @param string $title
     * @param string $counter
     * @param string $color
     * @return StatisticOverviewWidget
     */
    public function withAddedCounter(string $title, string $counter, string $color): StatisticOverviewWidget
    {
        $widget = clone $this;
        $widget->data['counter'][] = [
            'title' => $title,
            'counter' => $counter,
            'color' => $color,
        ];

        return $widget;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
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