<?php
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package kiwi-suite/admin
 * @link https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace KiwiSuite\Admin\ImageDefinition;

use KiwiSuite\Media\ImageDefinition\ImageDefinitionInterface;

final class AdminThumb implements ImageDefinitionInterface
{
    /**
     * @return string
     */
    public static function serviceName(): string
    {
        return 'admin-thumb';
    }

    /**
     * @return int|null
     */
    public function width(): ?int
    {
        return 500;
    }

    /**
     * @return int|null
     */
    public function height(): ?int
    {
        return 500;
    }


    public function upscale(): bool
    {
        return false;
    }

    public function mode(): string
    {
        return ImageDefinitionInterface::MODE_CANVAS;
    }

    /**
     * @return string
     */
    public function directory(): string
    {
        return 'admin-thumb';
    }
}
