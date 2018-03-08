<?php
namespace KiwiSuite\Admin\Message;

use KiwiSuite\Admin\Handler\User\CreateUserHandler;
use KiwiSuite\Admin\Repository\UserRepository;
use KiwiSuite\Admin\Role\RoleServiceManagerConfig;
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


    public function __construct(UserRepository $userRepository, RoleSubManager $roleSubManager)
    {

        $this->userRepository = $userRepository;
        $this->roleSubManager = $roleSubManager;
    }

    /**
     * @return string
     */
    public static function getHandler(): string
    {
        return CreateUserHandler::class;
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
            'email' => $this->email
        ]);

        if ($count > 0) {
            $result->addError("email_already_in_use");
        }

        /** @var RoleServiceManagerConfig $roleServiceManagerConfig */
        $roleServiceManagerConfig = $this->roleSubManager->getServiceManagerConfig();

        if (empty($roleServiceManagerConfig->getRoleMapping()[$this->data['role']])) {
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