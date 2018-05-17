<?php
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package   kiwi-suite/admin
 * @see       https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license   MIT License
 */

declare(strict_types=1);

namespace KiwiSuite\Admin\Resource;

use KiwiSuite\Admin\Message\CreateUserMessage;
use KiwiSuite\Admin\Repository\UserRepository;

final class UserResource implements ResourceInterface
{
    use ResourceTrait;

    public static function name(): string
    {
        return "user";
    }

    public function repository(): string
    {
        return UserRepository::class;
    }

    public function icon(): string
    {
        return "fa";
    }

    public function createMessage(): string
    {
        return CreateUserMessage::class;
    }

    public function schema(): array
    {
        return [
            'name'       => 'User',
            'namePlural' => 'Users',
        ];
    }
}
