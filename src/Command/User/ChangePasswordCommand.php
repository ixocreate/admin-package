<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package\Command\User;

use Ixocreate\Admin\Package\Config\AdminConfig;
use Ixocreate\Admin\Package\Entity\User;
use Ixocreate\Admin\Package\Event\UserEvent;
use Ixocreate\Admin\Package\Repository\UserRepository;
use Ixocreate\CommandBus\Package\Command\AbstractCommand;
use Ixocreate\Admin\UserInterface;
use Ixocreate\Filter\Package\FilterableInterface;
use Ixocreate\Validation\ValidatableInterface;
use Ixocreate\Validation\ViolationCollectorInterface;
use Ixocreate\Event\Package\EventDispatcher;

class ChangePasswordCommand extends AbstractCommand implements FilterableInterface, ValidatableInterface
{
    /**
     * @var AdminConfig
     */
    private $adminConfig;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * UpdateUserCommand constructor.
     * @param AdminConfig $adminConfig
     * @param UserRepository $userRepository
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(
        AdminConfig $adminConfig,
        UserRepository $userRepository,
        EventDispatcher $eventDispatcher
    ) {
        $this->adminConfig = $adminConfig;
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return string
     */
    public static function serviceName(): string
    {
        return 'admin.user-change-password';
    }

    /**
     * @throws \Exception
     * @return bool
     */
    public function execute(): bool
    {
        $data = $this->data();

        /* @var $user User */
        if (!empty($data['user'])) {
            $user = $data['user'];
        } else {
            $user = $this->userRepository->find($data['userId']);
        }

        $passwordHash = $password = \password_hash($data['password'], PASSWORD_DEFAULT);
        $user = $user->with('password', $passwordHash);

        $this->userRepository->save($user);

        $this->eventDispatcher->dispatch(UserEvent::EVENT_CHANGE_PASSWORD, new UserEvent($user));

        $this->sendNotificationEmail();

        return true;
    }

    public function filter(): FilterableInterface
    {
        $newData = [];
        $newData['userId'] = (string) $this->dataValue('userId');
        $newData['password'] = (string) $this->dataValue('password');
        $newData['passwordRepeat'] = (string) $this->dataValue('passwordRepeat');
        $newData['passwordOld'] = (string) $this->dataValue('passwordOld');

        return $this->withData($newData);
    }

    /**
     * @param ViolationCollectorInterface $violationCollector
     */
    public function validate(ViolationCollectorInterface $violationCollector): void
    {
        if (empty($this->dataValue('user')) || !$this->dataValue('user') instanceof UserInterface) {
            $user = $this->userRepository->find($this->dataValue('userId'));
            if (empty($user)) {
                $violationCollector->add("user", "invalid_user");
            }
        }

        if ($this->dataValue("skipPasswordOld") === true) {
            if (empty($this->dataValue("passwordOld"))) {
                $violationCollector->add("passwordOld", "invalid_password_old");
            } elseif (!\password_verify($this->dataValue("passwordOld"), $user->password())) {
                $violationCollector->add("passwordOld", "invalid_password_old");
            }
        }

        if (empty($this->dataValue("password"))) {
            $violationCollector->add("password", "invalid_password");
        } elseif ($this->dataValue("password") !== $this->dataValue("passwordRepeat")) {
            $violationCollector->add("password", "invalid_password");
        }
    }

    private function sendNotificationEmail()
    {
        // Create the Transport
//        $transport = (new \Swift_Transport_SendmailTransport())
//            ->setUsername('your username')
//            ->setPassword('your password')
//        ;
//
//// Create the Mailer using your created Transport
//        $mailer = new Swift_Mailer($transport);
//
//// Create a message
//        $message = (new Swift_Message('Wonderful Subject'))
//            ->setFrom(['john@doe.com' => 'John Doe'])
//            ->setTo(['receiver@domain.org', 'other@domain.org' => 'A name'])
//            ->setBody('Here is the message itself')
//        ;
//
//// Send the message
//        $result = $mailer->send($message);
    }
}
