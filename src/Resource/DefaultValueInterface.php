<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Resource;

use Ixocreate\Admin\Package\UserInterface;

interface DefaultValueInterface
{
    /**
     * @param UserInterface $user
     * @return array
     */
    public function defaultValues(UserInterface $user): array;
}
