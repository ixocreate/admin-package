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

namespace KiwiSuite\Admin\Resource;

use KiwiSuite\Admin\Handler\Crud\CreateHandler;
use KiwiSuite\Admin\Handler\Crud\DeleteHandler;
use KiwiSuite\Admin\Handler\Crud\UpdateHandler;
use KiwiSuite\Admin\Message\Crud\CreateMessage;
use KiwiSuite\Admin\Message\Crud\DeleteMessage;
use KiwiSuite\Admin\Message\Crud\UpdateMessage;

trait ResourceTrait
{
    public function createMessage(): string
    {
        return CreateMessage::class;
    }

    public function updateMessage(): string
    {
        return UpdateMessage::class;
    }

    public function deleteMessage(): string
    {
        return DeleteMessage::class;
    }

    public function indexAction(): ?string
    {
        return null;
    }

    public function createHandler(): array
    {
        return [
            CreateHandler::class,
        ];
    }

    public function updateHandler(): array
    {
        return [
            UpdateHandler::class,
        ];
    }

    public function deleteHandler(): array
    {
        return [
            DeleteHandler::class,
        ];
    }

    public function schema(): array
    {
        return [];
    }
}
