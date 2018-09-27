<?php
namespace KiwiSuite\Admin\Command\Account;

use Doctrine\Common\Collections\Criteria;
use KiwiSuite\Admin\Repository\UserRepository;
use KiwiSuite\CommandBus\Command\AbstractCommand;
use KiwiSuite\CommonTypes\Entity\EmailType;
use KiwiSuite\Contract\Filter\FilterableInterface;
use KiwiSuite\Contract\Validation\ValidatableInterface;
use KiwiSuite\Contract\Validation\ViolationCollectorInterface;
use KiwiSuite\Entity\Type\Type;

final class ChangeEmailCommand extends AbstractCommand implements FilterableInterface, ValidatableInterface
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

        $user = $user->with("email", $this->dataValue("email"));
        $this->userRepository->save($user);

        return true;
    }

    public function filter(): FilterableInterface
    {
        $newData = [];
        $newData['userId'] = (string) $this->dataValue('userId');
        $newData['email'] = $this->dataValue('email');
        $newData['emailRepeat'] = (string) $this->dataValue('emailRepeat');

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

        try {
            Type::create($this->dataValue("email"), EmailType::class);
        } catch (\Exception $exception) {
            $violationCollector->add("email", "invalid_email");
        }

        if ($this->dataValue("email") !== $this->dataValue("emailRepeat")) {
            $violationCollector->add("emailRepeat", "invalid_email");
        }

        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('email', $this->dataValue("email")));
        $criteria->andWhere(Criteria::expr()->neq("id", $user->id()));
        $criteria->setMaxResults(1);

        $result = $this->userRepository->matching($criteria);
        if ($result->count() > 0) {
            $violationCollector->add("email", "email_already_taken");
        }
    }
}