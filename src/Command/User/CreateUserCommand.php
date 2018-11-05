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

namespace KiwiSuite\Admin\Command\User;

use Identicon\Generator\ImageMagickGenerator;
use Identicon\Identicon;
use KiwiSuite\Admin\Entity\User;
use KiwiSuite\Admin\Event\UserEvent;
use KiwiSuite\Admin\Repository\UserRepository;
use KiwiSuite\Admin\Role\RoleSubManager;
use KiwiSuite\CommandBus\Command\AbstractCommand;
use KiwiSuite\CommonTypes\Entity\EmailType;
use KiwiSuite\Contract\CommandBus\CommandInterface;
use KiwiSuite\Contract\Validation\ValidatableInterface;
use KiwiSuite\Contract\Validation\ViolationCollectorInterface;
use KiwiSuite\Entity\Type\Type;
use KiwiSuite\Event\EventDispatcher;
use Ramsey\Uuid\Uuid;

final class CreateUserCommand extends AbstractCommand implements CommandInterface, ValidatableInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var RoleSubManager
     */
    private $roleSubManager;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * CreateUserCommand constructor.
     * @param UserRepository $userRepository
     * @param RoleSubManager $roleSubManager
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(UserRepository $userRepository, RoleSubManager $roleSubManager, EventDispatcher $eventDispatcher)
    {
        $this->userRepository = $userRepository;
        $this->roleSubManager = $roleSubManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @throws \Exception
     * @return bool
     */
    public function execute(): bool
    {
        $identicion = new Identicon(new ImageMagickGenerator());
        $avatar = $identicion->getImageDataUri($this->data()['email']);

        $user = new User([
            'id' => $this->uuid(),
            'email' => $this->data()['email'],
            'password' =>  \password_hash($this->data()['password'], PASSWORD_DEFAULT),
            'hash' => Uuid::uuid4()->toString(),
            'role' => $this->data()['role'],
            'avatar' => $avatar,
            'createdAt' => $this->createdAt(),
            'updatedAt' => $this->createdAt(),
            'status' => $this->data()['status'],
        ]);

        $this->userRepository->save($user);

        $this->eventDispatcher->dispatch(UserEvent::EVENT_CREATE, new UserEvent($user));

        return true;
    }

    public static function serviceName(): string
    {
        return 'admin-user-create';
    }

    public function validate(ViolationCollectorInterface $violationCollector): void
    {
        try {
            Type::create($this->data()['email'], EmailType::class);

            $count = $this->userRepository->count([
                'email' => $this->data()['email'],
            ]);

            if ($count > 0) {
                $violationCollector->add("email", "email.already-in-use", "Email is already in use");
            }
        } catch (\Exception $e) {
            $violationCollector->add('email', 'email.invalid', 'Email is invalid');
        }

        if (empty($this->data()['password']) || empty($this->data()['passwordRepeat'])) {
            $violationCollector->add("password", "password.invalid", "Password is invalid");
        }

        if ($this->data()['password'] !== $this->data()['passwordRepeat']) {
            $violationCollector->add("password", "password.doesnt-match", "Password and repeated password doesn't match");
        }

        if (!$this->roleSubManager->has($this->data()['role'])) {
            $violationCollector->add("role", "role.invalid", "Role is invalid");
        }
    }
}
