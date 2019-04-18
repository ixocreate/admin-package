<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Widget;

interface WidgetCollectorInterface
{
    /**
     * @param WidgetInterface $widget
     */
    public function add(WidgetInterface $widget): void;

    /**
     * @return WidgetInterface[]
     */
    public function widgets(): array;
}
