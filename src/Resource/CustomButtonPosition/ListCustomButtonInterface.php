<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Resource\CustomButtonPosition;

use Ixocreate\Admin\CustomButton\CustomButtonCollectorInterface;
use Ixocreate\Admin\UserInterface;

interface ListCustomButtonInterface
{
    public function receiveListCustomButtons(UserInterface $user, CustomButtonCollectorInterface $customButtonCollector): void;
}
