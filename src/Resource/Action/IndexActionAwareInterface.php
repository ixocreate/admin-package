<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Resource\Action;

use Ixocreate\Admin\UserInterface;

interface IndexActionAwareInterface
{
    public function indexAction(UserInterface $user): string;
}
