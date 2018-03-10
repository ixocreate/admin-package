<?php
namespace KiwiSuite\Admin\Message;

use KiwiSuite\Admin\Entity\User;
use KiwiSuite\Admin\Handler\Crud\UpdateHandler;
use KiwiSuite\Admin\Repository\UserRepository;
use KiwiSuite\CommandBus\Message\Validation\Result;
use KiwiSuite\CommonTypes\Entity\EmailType;
use KiwiSuite\Entity\Entity\EntityInterface;
use KiwiSuite\Entity\Type\Type;

final class ChangeEmailMessage implements CrudMessageInterface
{
    use CrudMessageTrait;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EmailType
     */
    private $email;


    public function __construct(UserRepository $userRepository)
    {

        $this->userRepository = $userRepository;
    }

    /**
     * @return array
     */
    public function handlers(): array
    {
        return [
            UpdateHandler::class
        ];
    }

    /**
     * @return EmailType
     */
    public function email(): EmailType
    {
        return $this->email;
    }

    public function fetchEntity(): EntityInterface
    {
        $id = $this->metadata[User::class];
        if (!empty($this->metadata['id'])) {
            $id = $this->metadata['id'];
        }
        /** @var User $user */
        $user = $this->userRepository->findOneBy(['id' => $id]);
        return $user->with('email', $this->email());
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

        try {
            $emailRepeat = Type::create($this->data['emailRepeat'], EmailType::class);
        } catch (\Exception $e) {
            $result->addError("invalid_email");
        }

        if ($this->email->getValue() !== $emailRepeat->getValue()) {
            $result->addError("email_doesnt_match");
        }

        $count = $this->userRepository->count([
            'email' => $this->email
        ]);

        if ($count > 0) {
            $result->addError("email_already_in_use");
        }
    }
}
