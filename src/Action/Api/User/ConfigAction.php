<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Action\Api\User;

use Ixocreate\Admin\Config\AdminConfig;
use Ixocreate\Admin\Repository\UserRepository;
use Ixocreate\Admin\Response\ApiSuccessResponse;
use Ixocreate\Admin\Schema\Type\RoleType;
use Ixocreate\Admin\Schema\Type\StatusType;
use Ixocreate\Schema\AdditionalSchemaInterface;
use Ixocreate\Schema\Builder\BuilderInterface;
use Ixocreate\Schema\Element\TextElement;
use Ixocreate\Schema\Schema;
use Ixocreate\Schema\SchemaInterface;
use Ixocreate\Schema\SchemaSubManager;
use Ixocreate\Schema\Type\TypeSubManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ConfigAction implements MiddlewareInterface
{
    /**
     * @var BuilderInterface
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
     * @var SchemaSubManager
     */
    private $additionalSchemaSubManager;

    /**
     * @var AdminConfig
     */
    private $adminConfig;

    /**
     * ConfigAction constructor.
     * @param BuilderInterface $builder
     * @param TypeSubManager $typeSubManager
     * @param UserRepository $userRepository
     * @param SchemaSubManager $additionalSchemaSubManager
     * @param AdminConfig $adminConfig
     */
    public function __construct(
        BuilderInterface $builder,
        TypeSubManager $typeSubManager,
        UserRepository $userRepository,
        SchemaSubManager $additionalSchemaSubManager,
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
