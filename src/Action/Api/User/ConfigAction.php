<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Action\Api\User;

use Ixocreate\Admin\Config\AdminConfig;
use Ixocreate\Admin\Repository\UserRepository;
use Ixocreate\Admin\Response\ApiSuccessResponse;
use Ixocreate\Admin\Type\RoleType;
use Ixocreate\Admin\Type\StatusType;
use Ixocreate\Contract\Schema\AdditionalSchemaInterface;
use Ixocreate\Contract\Schema\SchemaInterface;
use Ixocreate\Entity\Type\TypeSubManager;
use Ixocreate\Schema\AdditionalSchema\AdditionalSchemaSubManager;
use Ixocreate\Schema\Builder;
use Ixocreate\Schema\Elements\GroupElement;
use Ixocreate\Schema\Elements\TabbedGroupElement;
use Ixocreate\Schema\Elements\TextElement;
use Ixocreate\Schema\ElementSubManager;
use Ixocreate\Schema\Schema;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ConfigAction implements MiddlewareInterface
{
    /**
     * @var Builder
     */
    private $builder;

    /**
     * @var TypeSubManager
     */
    private $typeSubManager;

    /**
     * @var ElementSubManager
     */
    private $elementSubManager;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var AdditionalSchemaSubManager
     */
    private $additionalSchemaSubManager;

    /**
     * @var AdminConfig
     */
    private $adminConfig;

    /**
     * ConfigAction constructor.
     * @param Builder $builder
     * @param TypeSubManager $typeSubManager
     * @param ElementSubManager $elementSubManager
     * @param UserRepository $userRepository
     * @param AdditionalSchemaSubManager $additionalSchemaSubManager
     * @param AdminConfig $adminConfig
     */
    public function __construct(
        Builder $builder,
        TypeSubManager $typeSubManager,
        ElementSubManager $elementSubManager,
        UserRepository $userRepository,
        AdditionalSchemaSubManager $additionalSchemaSubManager,
        AdminConfig $adminConfig
    ) {
        $this->builder = $builder;
        $this->typeSubManager = $typeSubManager;
        $this->elementSubManager = $elementSubManager;
        $this->userRepository = $userRepository;
        $this->additionalSchemaSubManager = $additionalSchemaSubManager;
        $this->adminConfig = $adminConfig;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return new ApiSuccessResponse([
            'create' => $this->createSchema(),
            'update' => $this->updateSchema(),
            'additionalSchema' => $this->receiveAdditionalSchema(),
        ]);
    }

    /**
     * @return SchemaInterface
     */
    private function createSchema(): SchemaInterface
    {
        $createSchema = (new Schema())
            ->withAddedElement(
                $this->builder->create(TabbedGroupElement::class, 'tabs')
                    ->withAddedElement(
                        $this->builder->create(GroupElement::class, 'basicData')
                            ->withLabel('Grunddaten')
                            ->withAddedElement(
                                $this->builder->create(TextElement::class, 'email')
                                    ->withRequired(true)
                                    ->withLabel("Email")
                            )
                            ->withAddedElement(
                                $this->builder->create(TextElement::class, 'password')
                                    ->withRequired(true)
                                    ->withLabel("Password")
                            )
                            ->withAddedElement(
                                $this->builder->create(TextElement::class, 'passwordRepeat')
                                    ->withRequired(true)
                                    ->withLabel("Password Repeat")
                            )
                            ->withAddedElement($this->typeSubManager->get(RoleType::class)->schemaElement($this->elementSubManager)->withRequired(true)->withName('role')->withLabel('Role'))
                            ->withAddedElement($this->typeSubManager->get(StatusType::class)->schemaElement($this->elementSubManager)->withRequired(true)->withName('status')->withLabel('Status'))
                    )
    );
        return $createSchema;
    }

    /**
     * @return SchemaInterface
     */
    private function updateSchema(): SchemaInterface
    {
        $updateSchema = (new Schema())
            ->withAddedElement(
                $this->builder->create(TextElement::class, 'email')
                    ->withRequired(true)
                    ->withLabel("Email")
            )
            ->withAddedElement($this->typeSubManager->get(RoleType::class)->schemaElement($this->elementSubManager)->withRequired(true)->withName('role')->withLabel('Role'))
            ->withAddedElement($this->typeSubManager->get(StatusType::class)->schemaElement($this->elementSubManager)->withRequired(true)->withName('status')->withLabel('Status'));

        return $updateSchema;
    }

    /**
     * @return AdditionalSchemaInterface|null
     */
    private function receiveAdditionalSchema(): ?AdditionalSchemaInterface
    {
        $schema = null;

        if (!empty($this->adminConfig->userAttributesSchema())) {
            $schema = $this->additionalSchemaSubManager->get($this->adminConfig->userAttributesSchema());
        }

        return $schema;
    }
}
