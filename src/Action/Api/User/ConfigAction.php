<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package\Action\Api\User;

use Ixocreate\Admin\Package\Config\AdminConfig;
use Ixocreate\Admin\Package\Repository\UserRepository;
use Ixocreate\Admin\Package\Response\ApiSuccessResponse;
use Ixocreate\Admin\Package\Type\RoleType;
use Ixocreate\Admin\Package\Type\StatusType;
use Ixocreate\Schema\Package\AdditionalSchemaInterface;
use Ixocreate\Schema\Package\SchemaInterface;
use Ixocreate\Entity\Package\Type\TypeSubManager;
use Ixocreate\Schema\Package\AdditionalSchema\AdditionalSchemaSubManager;
use Ixocreate\Schema\Package\Builder;
use Ixocreate\Schema\Package\Elements\TextElement;
use Ixocreate\Schema\Package\ElementSubManager;
use Ixocreate\Schema\Package\Schema;
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
        $this->userRepository = $userRepository;
        $this->additionalSchemaSubManager = $additionalSchemaSubManager;
        $this->adminConfig = $adminConfig;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return new ApiSuccessResponse([
            'create' => $this->createSchema(),
            'update' => $this->updateSchema(),
        ]);
    }

    /**
     * @return SchemaInterface
     */
    private function createSchema(): SchemaInterface
    {
        $createSchema = (new Schema())
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
            ->withAddedElement(
                $this->typeSubManager->get(RoleType::class)->provideElement($this->builder)->withRequired(true)->withName('role')->withLabel('Role')
            )
            ->withAddedElement(
                $this->typeSubManager->get(StatusType::class)->provideElement($this->builder)->withRequired(true)->withName('status')->withLabel('Status')
        );

        if ($this->receiveUserAttributesSchema() !== null) {
            foreach (($this->receiveUserAttributesSchema()->additionalSchema($this->builder))->elements() as $element) {
                $createSchema = $createSchema->withAddedElement($element);
            }
        }

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
            ->withAddedElement($this->typeSubManager->get(RoleType::class)->provideElement($this->builder)->withRequired(true)->withName('role')->withLabel('Role'))
            ->withAddedElement($this->typeSubManager->get(StatusType::class)->provideElement($this->builder)->withRequired(true)->withName('status')->withLabel('Status'));

        if ($this->receiveUserAttributesSchema() !== null) {
            foreach (($this->receiveUserAttributesSchema()->additionalSchema($this->builder))->elements() as $element) {
                $updateSchema = $updateSchema->withAddedElement($element);
            }
        }

        return $updateSchema;
    }

    /**
     * @return AdditionalSchemaInterface|null
     */
    private function receiveUserAttributesSchema(): ?AdditionalSchemaInterface
    {
        $schema = null;
        if (!empty($this->adminConfig->userAttributesSchema())) {
            $schema = $this->additionalSchemaSubManager->get($this->adminConfig->userAttributesSchema());
        }
        return $schema;
    }
}
