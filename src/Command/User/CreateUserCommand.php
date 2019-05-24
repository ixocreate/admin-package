<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Command\User;

use Identicon\Generator\ImageMagickGenerator;
use Identicon\Identicon;
use Ixocreate\Admin\Config\AdminConfig;
use Ixocreate\Admin\Entity\User;
use Ixocreate\Admin\Event\UserEvent;
use Ixocreate\Admin\Repository\UserRepository;
use Ixocreate\Admin\Role\RoleSubManager;
use Ixocreate\CommandBus\Command\AbstractCommand;
use Ixocreate\Event\EventDispatcher;
use Ixocreate\Schema\AdditionalSchemaInterface;
use Ixocreate\Schema\SchemaSubManager;
use Ixocreate\Schema\Type\EmailType;
use Ixocreate\Schema\Type\SchemaType;
use Ixocreate\Schema\Type\Type;
use Ixocreate\Validation\ValidatableInterface;
use Ixocreate\Validation\Violation\ViolationCollectorInterface;

final class CreateUserCommand extends AbstractCommand implements ValidatableInterface
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
     * @var AdminConfig
     */
    private $adminConfig;

    /**
     * @var SchemaSubManager
     */
    private $additionalSchemaSubManager;

    /**
     * CreateUserCommand constructor.
     *
     * @param UserRepository $userRepository
     * @param RoleSubManager $roleSubManager
     * @param EventDispatcher $eventDispatcher
     * @param AdminConfig $adminConfig
     * @param SchemaSubManager $additionalSchemaSubManager
     */
    public function __construct(
        UserRepository $userRepository,
        RoleSubManager $roleSubManager,
        EventDispatcher $eventDispatcher,
        AdminConfig $adminConfig,
        SchemaSubManager $additionalSchemaSubManager
    ) {
        $this->userRepository = $userRepository;
        $this->roleSubManager = $roleSubManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->adminConfig = $adminConfig;
        $this->additionalSchemaSubManager = $additionalSchemaSubManager;
    }

    /**
     * @throws \Exception
     * @return bool
     */
    public function execute(): bool
    {
        $identicon = new Identicon(new ImageMagickGenerator());
        $avatar = $identicon->getImageDataUri($this->data()['email']);

        if (!empty($this->data()['passwordHash'])) {
            $password = $this->data()['passwordHash'];
        } else {
            $password = \password_hash($this->data()['password'], PASSWORD_DEFAULT);
        }

        $type = null;

        $additionalSchema = $this->receiveUserAttributesSchema();

        if ($additionalSchema !== null) {
            $content = [
                '__receiver__' => [
                    'receiver' => SchemaSubManager::class,
                    'options' => [
                        'additionalSchema' => $additionalSchema::serviceName(),
                    ],
                ],
                '__value__' => $this->data(),
            ];

            $type = (Type::create($content, SchemaType::class))->convertToDatabaseValue();
        }

        $user = new User([
            'id' => $this->uuid(),
            'email' => $this->data()['email'],
            'password' => $password,
            'role' => $this->data()['role'],
            'avatar' => $avatar,
            'createdAt' => $this->createdAt(),
            'updatedAt' => $this->createdAt(),
            'userAttributes' => $type,
            'status' => $this->data()['status'],
        ]);

        $this->userRepository->save($user);

        $this->eventDispatcher->dispatch(UserEvent::EVENT_CREATE, new UserEvent($user));

        return true;
    }

    /**
     * @return string
     */
    public static function serviceName(): string
    {
        return 'admin-user-create';
    }

    /**
     * @param ViolationCollectorInterface $violationCollector
     */
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

        if (empty($this->data()['passwordHash'])) {
            if (empty($this->data()['password']) || empty($this->data()['passwordRepeat'])) {
                $violationCollector->add("password", "password.invalid", "Password is invalid");
            } elseif ($this->data()['password'] !== $this->data()['passwordRepeat']) {
                $violationCollector->add(
                    "password",
                    "password.doesnt-match",
                    "Password and repeated password doesn't match"
                );
            }
        }

        if (!$this->roleSubManager->has($this->data()['role'])) {
            $violationCollector->add("role", "role.invalid", "Role is invalid");
        }
    }

    /**
     * @return AdditionalSchemaInterface|null
     */
    private function receiveUserAttributesSchema(): ?AdditionalSchemaInterface
    {
        $schema = null;
        if (!empty($this->adminConfig->userAttributesSchema())) {
            $schema = $this->additionalSchemaSubManager->get($this->adminConfig->userAttributesSchema());
        }
        return $schema;
    }
}
