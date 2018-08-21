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

namespace KiwiSuite\Admin\Response;

use KiwiSuite\Contract\Resource\AdminAwareInterface;
use KiwiSuite\Contract\Schema\SchemaInterface;

class ApiDetailResponse extends ApiSuccessResponse
{
    /**
     * @var AdminAwareInterface
     */
    private $resource;
    /**
     * @var array
     */
    private $item;
    /**
     * @var SchemaInterface
     */
    private $schema;
    /**
     * @var array
     */
    private $meta;

    public function __construct(AdminAwareInterface $resource, array $item, SchemaInterface $schema, array $meta)
    {
        $data = [
            'label' => $resource->label(),
            'item' => (object)$item, // make sure an empty array here is an empty object in json
            'schema' => $schema,
            'meta' => $meta,
        ];
        parent::__construct($data);
        $this->resource = $resource;
        $this->item = $item;
        $this->schema = $schema;
        $this->meta = $meta;
    }

    public function resource(): AdminAwareInterface
    {
        return $this->resource;
    }

    public function item(): array
    {
        return $this->item;
    }

    public function schema(): SchemaInterface
    {
        return $this->schema;
    }

    public function meta(): array
    {
        return $this->meta;
    }
}
