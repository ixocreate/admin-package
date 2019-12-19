<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\CustomButton;

interface CustomButtonCollectorInterface
{
    /**
     * @param CustomButtonInterface $customButton
     */
    public function add(CustomButtonInterface $customButton): void;

    /**
     * @return CustomButtonInterface[]
     */
    public function customButtons(): array;
}
