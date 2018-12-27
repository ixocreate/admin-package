<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Command\Account;

use Ixocreate\Admin\Config\AdminConfig;
use Ixocreate\Admin\Repository\UserRepository;
use Ixocreate\CommandBus\Command\AbstractCommand;
use Ixocreate\CommonTypes\Entity\SchemaType;
use Ixocreate\Contract\Schema\AdditionalSchemaInterface;
use Ixocreate\Entity\Type\Type;
use Ixocreate\Schema\AdditionalSchema\AdditionalSchemaSubManager;

class UpdateCommand extends AbstractCommand
{
    /**
     * @var AdminConfig
     */
    private $adminConfig;

    /**
     * @var AdditionalSchemaSubManager
     */
    private $additionalSchemaSubManager;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UpdateCommand constructor.
     * @param AdminConfig $adminConfig
     * @param AdditionalSchemaSubManager $additionalSchemaSubManager
     * @param UserRepository $userRepository
     */
    public function __construct(AdminConfig $adminConfig, AdditionalSchemaSubManager $additionalSchemaSubManager, UserRepository $userRepository)
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

        $entity = $this->userRepository->find($data['userId']);

        $additionalSchema = $this->receiveAdditionalSchema();

        if ($additionalSchema !== null) {
            $content = [
                '__receiver__' => [
                    'receiver' => AdditionalSchemaSubManager::class,
                    'options' => [
                        'additionalSchema' => $additionalSchema::serviceName(),
                    ],
                ],
                '__value__' => $data,
            ];

            $type = (Type::create($content, SchemaType::class))->convertToDatabaseValue();

            $entity = $entity->with('accountAttributes', $type);
        }

        $entity = $entity->with('updatedAt', new \DateTimeImmutable());

        $this->userRepository->save($entity);

        return true;
    }

    public static function serviceName(): string
    {
        return "admin.account-update";
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
