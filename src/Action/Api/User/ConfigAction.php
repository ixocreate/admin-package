<?php
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package kiwi-suite/admin
 * @link https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace KiwiSuite\Admin\Action\Api\User;

use KiwiSuite\Admin\Repository\UserRepository;
use KiwiSuite\Admin\Response\ApiSuccessResponse;
use KiwiSuite\Admin\Type\RoleType;
use KiwiSuite\Admin\Type\StatusType;
use KiwiSuite\Contract\Schema\SchemaInterface;
use KiwiSuite\Entity\Type\TypeSubManager;
use KiwiSuite\Schema\Builder;
use KiwiSuite\Schema\Elements\GroupElement;
use KiwiSuite\Schema\Elements\TabbedGroupElement;
use KiwiSuite\Schema\Elements\TextElement;
use KiwiSuite\Schema\ElementSubManager;
use KiwiSuite\Schema\Schema;
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
     * ConfigAction constructor.
     * @param Builder $builder
     * @param TypeSubManager $typeSubManager
     * @param ElementSubManager $elementSubManager
     * @param UserRepository $userRepository
     */
    public function __construct(
        Builder $builder,
        TypeSubManager $typeSubManager,
        ElementSubManager $elementSubManager,
        UserRepository $userRepository
    ) {
        $this->builder = $builder;
        $this->typeSubManager = $typeSubManager;
        $this->elementSubManager = $elementSubManager;
        $this->userRepository = $userRepository;
    }

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
}
