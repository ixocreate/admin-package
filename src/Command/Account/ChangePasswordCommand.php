<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Command\Account;

use Ixocreate\Admin\Repository\UserRepository;
use Ixocreate\CommandBus\Command\AbstractCommand;
use Ixocreate\Filter\FilterableInterface;
use Ixocreate\Validation\ValidatableInterface;
use Ixocreate\Validation\Violation\ViolationCollectorInterface;

final class ChangePasswordCommand extends AbstractCommand implements FilterableInterface, ValidatableInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * ChangeEmailCommand constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return bool
     */
    public function execute(): bool
    {
        $user = $this->userRepository->find($this->dataValue('userId'));

        $user = $user->with("password", \password_hash($this->dataValue("password"), PASSWORD_DEFAULT));
        $this->userRepository->save($user);

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
     * @return string
     */
    public static function serviceName(): string
    {
        return "admin.account-change-email";
    }

    /**
     * @param ViolationCollectorInterface $violationCollector
     */
    public function validate(ViolationCollectorInterface $violationCollector): void
    {
        $user = $this->userRepository->find($this->dataValue('userId'));
        if (empty($user)) {
            $violationCollector->add("user", "invalid_user");
        }

        if (empty($this->dataValue("passwordOld"))) {
            $violationCollector->add("passwordOld", "invalid_password_old");
        }

        if (empty($this->dataValue("password"))) {
            $violationCollector->add("password", "invalid_password");
        }

        if ($this->dataValue("password") !== $this->dataValue("passwordRepeat")) {
            $violationCollector->add("password", "invalid_password");
        }

        if (!\password_verify($this->dataValue("passwordOld"), $user->password())) {
            $violationCollector->add("passwordOld", "invalid_password_old");
        }
    }
}
