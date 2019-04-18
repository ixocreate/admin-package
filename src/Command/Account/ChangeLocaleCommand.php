<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package\Command\Account;

use Ixocreate\Admin\Package\Repository\UserRepository;
use Ixocreate\CommandBus\Package\Command\AbstractCommand;
use Ixocreate\Filter\Package\FilterableInterface;
use Ixocreate\Validation\Package\ValidatableInterface;
use Ixocreate\Validation\Package\ViolationCollectorInterface;

final class ChangeLocaleCommand extends AbstractCommand implements FilterableInterface, ValidatableInterface
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

        $user = $user->with("locale", $this->dataValue("locale"));
        $user = $user->with("numberLocale", $this->dataValue("numberLocale"));
        $user = $user->with("dateLocale", $this->dataValue("dateLocale"));
        $user = $user->with("timeLocale", $this->dataValue("timeLocale"));
        $user = $user->with("timezone", $this->dataValue("timezone"));
        $this->userRepository->save($user);

        return true;
    }

    public function filter(): FilterableInterface
    {
        $newData = [];
        $newData['userId'] = (string)$this->dataValue('userId');
        $newData['locale'] = $this->dataValue('locale');
        $newData['numberLocale'] = (string)$this->dataValue('numberLocale');
        $newData['dateLocale'] = (string)$this->dataValue('dateLocale');
        $newData['timeLocale'] = (string)$this->dataValue('timeLocale');
        $newData['timezone'] = (string)$this->dataValue('timezone');

        return $this->withData($newData);
    }

    /**
     * @return string
     */
    public static function serviceName(): string
    {
        return "admin.account-change-locale";
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

        //if (!empty($this->dataValue("locale")) && !in_array($this->dataValue("locale"), \Locale::)) {
        //    $violationCollector->add("locale", "invalid_locale");
        //}
    }
}
