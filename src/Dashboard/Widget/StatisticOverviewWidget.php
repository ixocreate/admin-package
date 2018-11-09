<?php

declare(strict_types=1);

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
     * @param string $name
     * @param array $progress
     * @param array $counter
     * @return StatisticOverviewWidget
     */
    public function withAddedSection(string $name, array $progress, array $counter): StatisticOverviewWidget
    {
        $widget = clone $this;
        $widget->data[] = [
            'name' => $name,
            'progress' => $progress,
            'counter' => $counter,
        ];

        return $widget;
    }

    /**
     * @param int $current
     * @param int $max
     * @param string $title
     * @param string $color
     * @param string $textColor
     * @return array
     */
    public function createProgress(int $current, int $max, string $title, string $color, string $textColor): array
    {
        return [
            'current' => $current,
            'max' => $max,
            'title' => $title,
            'color' => $color,
            'textColor' => $textColor,
        ];
    }

    /**
     * @param string $icon
     * @param string $title
     * @param string $counter
     * @param string $color
     * @return array
     */
    public function createCounter(string $title, string $counter, string $color): array
    {
        return [
            'title' => $title,
            'counter' => $counter,
            'color' => $color,
        ];
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