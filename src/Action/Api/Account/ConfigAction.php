<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Action\Account;

use Ixocreate\Admin\Config\AdminConfig;
use Ixocreate\Admin\Response\ApiSuccessResponse;
use Ixocreate\Schema\AdditionalSchemaInterface;
use Ixocreate\Schema\AdditionalSchema\AdditionalSchemaSubManager;
use Ixocreate\Schema\Builder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ConfigAction implements MiddlewareInterface
{
    /**
     * @var AdminConfig
     */
    private $adminConfig;

    /**
     * @var Builder
     */
    private $builder;

    /**
     * @var AdditionalSchemaSubManager
     */
    private $additionalSchemaSubManager;

    /**
     * ConfigAction constructor.
     * @param AdminConfig $adminConfig
     * @param Builder $builder
     * @param AdditionalSchemaSubManager $additionalSchemaSubManager
     */
    public function __construct(AdminConfig $adminConfig, Builder $builder, AdditionalSchemaSubManager $additionalSchemaSubManager)
    {
        $this->adminConfig = $adminConfig;
        $this->builder = $builder;
        $this->additionalSchemaSubManager = $additionalSchemaSubManager;
    }

    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = [
            'accountAttributesSchema' => null,
            'localeAttributesSchema' => null,
        ];

        $accountAttributesSchema = $this->receiveAccountAttributesSchema();
        if ($accountAttributesSchema !== null) {
            $data['accountAttributesSchema'] = $accountAttributesSchema->additionalSchema($this->builder);
        }

        $localeAttributesSchema = $this->receiveLocaleAttributesSchema();
        if ($localeAttributesSchema !== null) {
            $data['localeAttributesSchema'] = $localeAttributesSchema->additionalSchema($this->builder);
        }

        return new ApiSuccessResponse($data);
    }

    /**
     * @return AdditionalSchemaInterface|null
     */
    private function receiveAccountAttributesSchema(): ?AdditionalSchemaInterface
    {
        $schema = null;

        if (!empty($this->adminConfig->accountAttributesSchema())) {
            $schema = $this->additionalSchemaSubManager->get($this->adminConfig->accountAttributesSchema());
        }
        return $schema;
    }

    /**
     * @return AdditionalSchemaInterface|null
     */
    private function receiveLocaleAttributesSchema(): ?AdditionalSchemaInterface
    {
        $schema = null;

        if (!empty($this->adminConfig->localeAttributesSchema())) {
            $schema = $this->additionalSchemaSubManager->get($this->adminConfig->localeAttributesSchema());
        }
        return $schema;
    }
}
