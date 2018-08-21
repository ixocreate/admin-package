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

namespace KiwiSuite\Admin\Message;

use KiwiSuite\CommandBus\Message\MessageInterface;
use KiwiSuite\Entity\Entity\EntityInterface;

interface CrudMessageInterface extends MessageInterface
{
    public function fetchEntity(): EntityInterface;

    public function entity(): EntityInterface;

    public function withEntity(EntityInterface $entity): CrudMessageInterface;
}
