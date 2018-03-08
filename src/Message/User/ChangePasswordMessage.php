<?php
namespace KiwiSuite\Admin\Message;

use KiwiSuite\Admin\Entity\User;
use KiwiSuite\Admin\Handler\Crud\UpdateHandler;
use KiwiSuite\Admin\Repository\UserRepository;
use KiwiSuite\CommandBus\Message\MessageInterface;
use KiwiSuite\CommandBus\Message\MessageTrait;
use KiwiSuite\CommandBus\Message\Validation\Result;
use KiwiSuite\CommonTypes\Entity\EmailType;
use KiwiSuite\Entity\Entity\EntityInterface;
use KiwiSuite\Entity\Type\Type;

final class ChangePasswordMessage implements MessageInterface, CrudMessageInterface
{
    use MessageTrait;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var string
     */
    private $password;

    public function __construct(UserRepository $userRepository)
    {

        $this->userRepository = $userRepository;
    }

    /**
     * @return string
     */
    public static function getHandler(): string
    {
        return UpdateHandler::class;
    }

    /**
     * @return string
     */
    public function password(): string
    {
        return $this->password;
    }

    public function fetchEntity(): EntityInterface
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy(['id' => $this->metadata[User::class]]);
        return $user->with('password', password_hash($this->password(), PASSWORD_DEFAULT));
    }

    /**
     * @param Result $result
     * @throws \Assert\AssertionFailedException
     */
    protected function doValidate(Result $result): void
    {
        if (empty($this->data['password'])) {
            $result->addError("invalid_password");
        }
        if (empty($this->data['passwordOld'])) {
            $result->addError("invalid_old_password");
        }
        if (empty($this->data['passwordRepeat'])) {
            $result->addError("invalid_password_password");
        }

        if (!$result->isSuccessful()) {
            return;
        }

        $this->password = $this->data['password'];

        if ($this->password !== $this->data['passwordRepeat']) {
            $result->addError("password_do_not_match");
        }

        $user = $this->userRepository->findOneBy(['id' => $this->metadata[User::class]]);
        if (!password_verify($this->data['passwordOld'], $user->password())){
            $result->addError("invalid_old_password");
        }
    }
}
