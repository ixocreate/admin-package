<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\CustomButton;

interface CustomButtonInterface extends \JsonSerializable
{
    /**
     * @var int
     */
    const SIZE_SMALL = 1;

    /**
     * @var int
     */
    const SIZE_MEDIUM = 2;

    /**
     * @var int
     */
    const SIZE_LARGE = 3;

    /**
     * @return int
     */
    public function size(): int;

    /**
     * @return int
     */
    public function priority(): int;

    /**
     * @return string
     */
    public function type(): string;

    /**
     * @return string
     */
    public function color(): string;

    /**
     * @return string
     */
    public function title(): string;

    /**
     * @return string
     */
    public function icon(): string;

}
