<?php
namespace KiwiSuite\Admin\Dashboard\Widget;

use KiwiSuite\Contract\Admin\DashboardWidgetInterface;

final class SlideshowWidget implements DashboardWidgetInterface
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
        return 'slideshow';
    }

    /**
     * @param int $size
     * @return SlideshowWidget
     */
    public function withSize(int $size): SlideshowWidget
    {
        $widget = clone $this;
        $widget->size = $size;

        return $widget;
    }

    /**
     * @param int $priority
     * @return SlideshowWidget
     */
    public function withPriority(int $priority): SlideshowWidget
    {
        $widget = clone $this;
        $widget->priority = $priority;

        return $widget;
    }

    /**
     * @param string $src
     * @param string $title
     * @param string $link
     * @return SlideshowWidget
     */
    public function withAddedItem(string $src, string $title, string $link): SlideshowWidget
    {
        $widget = clone $this;
        $widget->data[] = [
            'src' => $src,
            'title' => $title,
            'link' => $link,
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