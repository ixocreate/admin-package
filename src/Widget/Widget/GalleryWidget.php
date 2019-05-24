<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Widget\Widget;

use Ixocreate\Admin\Widget\WidgetInterface;
use Ixocreate\Media\Schema\Type\ImageType;

final class GalleryWidget implements WidgetInterface
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
        return 'gallery';
    }

    /**
     * @param int $size
     * @return GalleryWidget
     */
    public function withSize(int $size): GalleryWidget
    {
        $widget = clone $this;
        $widget->size = $size;

        return $widget;
    }

    /**
     * @param int $priority
     * @return GalleryWidget
     */
    public function withPriority(int $priority): GalleryWidget
    {
        $widget = clone $this;
        $widget->priority = $priority;

        return $widget;
    }

    public function withAddedItem(ImageType $image, ?string $title = null, ?string $icon = null): GalleryWidget
    {
        $widget = clone $this;
        $widget->data[] = [
            'image' => $image,
            'title' => $title,
            'icon' => $icon,
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
