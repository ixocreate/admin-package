<?php
declare(strict_types=1);

namespace KiwiSuite\Admin\Command\Account;


use KiwiSuite\Admin\Config\AdminConfig;
use KiwiSuite\Admin\Repository\UserRepository;
use KiwiSuite\CommandBus\Command\AbstractCommand;
use KiwiSuite\CommonTypes\Entity\SchemaType;
use KiwiSuite\Contract\Schema\AdditionalSchemaInterface;
use KiwiSuite\Entity\Type\Type;
use KiwiSuite\Schema\AdditionalSchema\AdditionalSchemaSubManager;

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
     * @return bool
     * @throws \Exception
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
                        'additionalSchema' => $additionalSchema::serviceName()
                    ]
                ],
                '__value__' => $data,
            ];

            $type = (Type::create($content, SchemaType::class))->convertToDatabaseValue();

            $entity = $entity->with('additionalAccountSchema', $type);
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

        if (!empty($this->adminConfig->additionalAccountSchema())) {
            $schema = $this->additionalSchemaSubManager->get($this->adminConfig->additionalAccountSchema());
        }

        return $schema;
    }
}