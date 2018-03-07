<?php
namespace KiwiSuite\Admin\Resource;

use KiwiSuite\Admin\Message\CreateUserMessage;
use KiwiSuite\Admin\Repository\UserRepository;

final class UserResource implements ResourceInterface
{

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

    public function createMessage(): ?string
    {
        return CreateUserMessage::class;
    }

    public function updateMessage(): ?string
    {
        return null;
    }

    public function deleteMessage(): ?string
    {
        return null;
    }

    public function indexAction(): ?string
    {
        return null;
    }
}
