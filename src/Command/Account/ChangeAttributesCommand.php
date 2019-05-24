<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Command\Account;

use Ixocreate\Admin\Config\AdminConfig;
use Ixocreate\Admin\Entity\User;
use Ixocreate\Admin\Repository\UserRepository;
use Ixocreate\CommandBus\Command\AbstractCommand;
use Ixocreate\Schema\AdditionalSchemaInterface;
use Ixocreate\Schema\SchemaSubManager;
use Ixocreate\Schema\Type\SchemaType;
use Ixocreate\Schema\Type\Type;

class ChangeAttributesCommand extends AbstractCommand
{
    /**
     * @var AdminConfig
     */
    private $adminConfig;

    /**
     * @var SchemaSubManager
     */
    private $additionalSchemaSubManager;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * ChangeAttributesCommand constructor.
     * @param AdminConfig $adminConfig
     * @param SchemaSubManager $additionalSchemaSubManager
     * @param UserRepository $userRepository
     */
    public function __construct(AdminConfig $adminConfig, SchemaSubManager $additionalSchemaSubManager, UserRepository $userRepository)
    {
        $this->adminConfig = $adminConfig;
        $this->additionalSchemaSubManager = $additionalSchemaSubManager;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws \Exception
     * @return bool
     */
    public function execute(): bool
    {
        $data = $this->data();

        /** @var User $user */
        $user = $this->userRepository->find($data['userId']);

        $additionalSchema = $this->receiveAdditionalSchema();

        if ($additionalSchema !== null) {
            $type = Type::create($data['data'], SchemaType::class, [
                'provider' => ['class' => SchemaSubManager::class, 'name' => $additionalSchema::serviceName()],
            ]);

            $user = $user->with('accountAttributes', $type);
            $user = $user->with('updatedAt', new \DateTimeImmutable());
        }

        $this->userRepository->save($user);

        return true;
    }

    public static function serviceName(): string
    {
        return "admin.account-change-attributes";
    }

    /**
     * @return AdditionalSchemaInterface|null
     */
    private function receiveAdditionalSchema(): ?AdditionalSchemaInterface
    {
        $schema = null;

        if (!empty($this->adminConfig->accountAttributesSchema())) {
            $schema = $this->additionalSchemaSubManager->get($this->adminConfig->accountAttributesSchema());
        }

        return $schema;
    }
}
