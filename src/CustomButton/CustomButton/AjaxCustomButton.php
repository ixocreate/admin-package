<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\CustomButton\CustomButton;

use Ixocreate\Admin\CustomButton\CustomButtonInterface;

final class AjaxCustomButton implements CustomButtonInterface
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
    private $text = '';

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
        return 'ajax';
    }

    /**
     * @param int $size
     * @return AjaxCustomButton
     */
    public function withSize(int $size): AjaxCustomButton
    {
        $customButton = clone $this;
        $customButton->size = $size;

        return $customButton;
    }

    /**
     * @param int $priority
     * @return AjaxCustomButton
     */
    public function withPriority(int $priority): AjaxCustomButton
    {
        $customButton = clone $this;
        $customButton->priority = $priority;

        return $customButton;
    }

    /**
     * @param string $color
     * @return AjaxCustomButton
     */
    public function withColor(string $color): AjaxCustomButton
    {
        $customButton = clone $this;
        $customButton->color = $color;

        return $customButton;
    }

    /**
     * @param string $title
     * @return AjaxCustomButton
     */
    public function withTitle(string $title): AjaxCustomButton
    {
        $customButton = clone $this;
        $customButton->title = $title;

        return $customButton;
    }

    /**
     * @param string $text
     * @return AjaxCustomButton
     */
    public function withText(string $text): AjaxCustomButton
    {
        $customButton = clone $this;
        $customButton->text = $text;

        return $customButton;
    }

    /**
     * @param string $icon
     * @return AjaxCustomButton
     */
    public function withIcon(string $icon): AjaxCustomButton
    {
        $customButton = clone $this;
        $customButton->icon = $icon;

        return $customButton;
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
    public function text(): string
    {
        return $this->text;
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
            'text' => $this->text(),
            'icon' => $this->icon(),
        ];
    }
}
