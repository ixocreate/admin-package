<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Resource\WidgetPosition;

use Ixocreate\Admin\CustomButton\CustomButtonCollectorInterface;
use Ixocreate\Admin\UserInterface;

interface EditCustomButtonInterface
{
    public function receiveEditCustomButtons(UserInterface $user, CustomButtonCollectorInterface $customButtonCollector, string $id): void;
}
