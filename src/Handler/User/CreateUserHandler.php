<?php
namespace KiwiSuite\Admin\Handler\User;

use Identicon\Generator\SvgGenerator;
use Identicon\Identicon;
use KiwiSuite\Admin\Entity\User;
use KiwiSuite\Admin\Message\CreateUserMessage;
use KiwiSuite\Admin\Repository\UserRepository;
use KiwiSuite\CommandBus\Handler\HandlerInterface;
use KiwiSuite\CommandBus\Message\MessageInterface;
use Ramsey\Uuid\Uuid;

final class CreateUserHandler implements HandlerInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param MessageInterface $message
     * @return MessageInterface
     */
    public function __invoke(MessageInterface $message): MessageInterface
    {
        /** @var CreateUserMessage $message */

        $password = password_hash($message->password(), PASSWORD_DEFAULT);

        $identicion = new Identicon(new SvgGenerator());
        $avatar = $identicion->getImageData((string) $message->email()->getValue());

        $hash = Uuid::uuid4()->toString();

        $user = new User([
            'id' => $message->uuid(),
            'email' => $message->email(),
            'password' => $password,
            'hash' => $hash,
            'role' => $message->role(),
            'avatar' => $avatar,
            'status' => 'active',
            'createdAt' => $message->createdAt(),
        ]);

        $user = $this->userRepository->save($user);
        $this->userRepository->flush($user);

        return $message;
    }
}
