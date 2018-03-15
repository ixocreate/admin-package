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

namespace KiwiSuite\Admin\Message;

use KiwiSuite\Admin\Handler\User\CreateUserHandler;
use KiwiSuite\Admin\Repository\UserRepository;
use KiwiSuite\Admin\Role\RoleMapping;
use KiwiSuite\Admin\Role\RoleSubManager;
use KiwiSuite\CommandBus\Message\MessageInterface;
use KiwiSuite\CommandBus\Message\MessageTrait;
use KiwiSuite\CommandBus\Message\Validation\Result;
use KiwiSuite\CommonTypes\Entity\EmailType;
use KiwiSuite\Entity\Type\Type;

final class CreateUserMessage implements MessageInterface
{
    use MessageTrait;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EmailType
     */
    private $email;

    /**
     * @var RoleSubManager
     */
    private $roleSubManager;

    /**
     * @var RoleMapping
     */
    private $roleMapping;


    public function __construct(UserRepository $userRepository, RoleSubManager $roleSubManager, RoleMapping $roleMapping)
    {
        $this->userRepository = $userRepository;
        $this->roleSubManager = $roleSubManager;
        $this->roleMapping = $roleMapping;
    }

    /**
     * @return array
     */
    public function handlers(): array
    {
        return [
            CreateUserHandler::class,
        ];
    }

    /**
     * @return EmailType
     */
    public function email(): EmailType
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function role(): string
    {
        return $this->data['role'];
    }

    public function password(): string
    {
        return $this->data['password'];
    }

    /**
     * @param Result $result
     * @throws \Assert\AssertionFailedException
     */
    protected function doValidate(Result $result): void
    {
        try {
            $this->email = Type::create($this->data['email'], EmailType::class);
        } catch (\Exception $e) {
            $result->addError("invalid_email");
        }

        $count = $this->userRepository->count([
            'email' => $this->email,
        ]);

        if ($count > 0) {
            $result->addError("email_already_in_use");
        }


        if (empty($this->roleMapping->getMapping()[$this->data['role']])) {
            $result->addError("invalid_role");
        }

        if (empty($this->data['password']) || empty($this->data['passwordRepeat'])) {
            $result->addError("invalid_password");

            return;
        }

        if ($this->data['password'] !== $this->data['passwordRepeat']) {
            $result->addError("password_dont_match");
        }
    }
}
