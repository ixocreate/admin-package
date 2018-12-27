<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Command\User;

use Identicon\Generator\ImageMagickGenerator;
use Identicon\Identicon;
use Ixocreate\Admin\Entity\User;
use Ixocreate\Admin\Event\UserEvent;
use Ixocreate\Admin\Repository\UserRepository;
use Ixocreate\Admin\Role\RoleSubManager;
use Ixocreate\CommandBus\Command\AbstractCommand;
use Ixocreate\CommonTypes\Entity\EmailType;
use Ixocreate\Contract\CommandBus\CommandInterface;
use Ixocreate\Contract\Validation\ValidatableInterface;
use Ixocreate\Contract\Validation\ViolationCollectorInterface;
use Ixocreate\Entity\Type\Type;
use Ixocreate\Event\EventDispatcher;
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
