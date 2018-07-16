<?php
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package kiwi-suite/admin
 * @see https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */
declare(strict_types=1);

namespace KiwiSuite\Admin\ImageDefinition;

use KiwiSuite\Media\ImageDefinition\ImageDefinitionInterface;

final class AdminThumb implements ImageDefinitionInterface
{
    /**
     * @var int
     */
    private $width = 500;

    /**
     * @var int
     */
    private $height = 500;

    /**
     * @var string
     */
    private $directory = 'admin-thumb';

    /**
     * @return string
     */
    public static function serviceName(): string
    {
        return 'AdminThumb';
    }

    /**
     * @return int|null
     */
    public function getWidth(): ?int
    {
        return $this->width;
    }

    /**
     * @return int|null
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function getCrop(): bool
    {
        return true;
    }

    public function getUpscale(): bool
    {
        return false;
    }

    public function getCanvas(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getDirectory(): string
    {
        return $this->directory;
    }

}