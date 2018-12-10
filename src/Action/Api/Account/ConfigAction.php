<?php
declare(strict_types=1);

namespace KiwiSuite\Admin\Action\Account;


use KiwiSuite\Admin\Config\AdminConfig;
use KiwiSuite\Admin\Response\ApiSuccessResponse;
use KiwiSuite\Contract\Schema\AdditionalSchemaInterface;
use KiwiSuite\Schema\AdditionalSchema\AdditionalSchemaSubManager;
use KiwiSuite\Schema\Builder;
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
        $schema = $this->receiveAdditionalSchema();


        if ($schema !== null) {
            return new ApiSuccessResponse([
                'additionalSchema' => $schema->receiveSchema($this->builder)
            ]);
        }

        return new ApiSuccessResponse();
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