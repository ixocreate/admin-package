<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\CustomButton\CustomButton;

use Ixocreate\Admin\CustomButton\CustomButtonInterface;

final class UrlCustomButton implements CustomButtonInterface
{
    /**
     * @var int
     */
    private $size = self::SIZE_SMALL;

    /**
     * @var int
     */
    private $priority = 100;

    private $color = '';
    private $title = '';
    private $icon = '';
    private $link = '';

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
        return 'url';
    }

    /**
     * @inheritDoc
     */
    public function color(): string
    {
        return $this->color;
    }

    /**
     * @inheritDoc
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * @inheritDoc
     */
    public function icon(): string
    {
        return $this->icon;
    }

    /**
     * @inheritDoc
     */
    public function link(): string
    {
        return $this->link;
    }

    /**
     * @param int $size
     * @return UrlCustomButton
     */
    public function withSize(int $size): UrlCustomButton
    {
        $customButton = clone $this;
        $customButton->size = $size;

        return $customButton;
    }

    /**
     * @param int $priority
     * @return UrlCustomButton
     */
    public function withPriority(int $priority): UrlCustomButton
    {
        $customButton = clone $this;
        $customButton->priority = $priority;

        return $customButton;
    }

    /**
     * @param string $color
     * @return UrlCustomButton
     */
    public function withColor(string $color): UrlCustomButton
    {
        $customButton = clone $this;
        $customButton->color = $color;

        return $customButton;
    }

    /**
     * @param string $title
     * @return UrlCustomButton
     */
    public function withTitle(string $title): UrlCustomButton
    {
        $customButton = clone $this;
        $customButton->title = $title;

        return $customButton;
    }

    /**
     * @param string $link
     * @return UrlCustomButton
     */
    public function withLink(string $link): UrlCustomButton
    {
        $customButton = clone $this;
        $customButton->link = $link;

        return $customButton;
    }

    /**
     * @param string $icon
     * @return UrlCustomButton
     */
    public function withIcon(string $icon): UrlCustomButton
    {
        $customButton = clone $this;
        $customButton->icon = $icon;

        return $customButton;
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
            'color' => $this->color(),
            'title' => $this->title(),
            'link' => $this->link(),
            'icon' => $this->icon(),
        ];
    }
}
