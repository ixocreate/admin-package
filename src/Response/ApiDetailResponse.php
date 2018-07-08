<?php
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package kiwi-suite/admin
 * @see https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace KiwiSuite\Admin\Response;

use KiwiSuite\Contract\Resource\AdminAwareInterface;
use KiwiSuite\Contract\Schema\SchemaInterface;

class ApiDetailResponse extends ApiSuccessResponse
{
    public function __construct(AdminAwareInterface $resource, array $item, SchemaInterface $schema, array $meta)
    {
        $data = [
            'label' => $resource->label(),
            'item' => $item,
            'schema' => $schema,
            'meta' => $meta,
        ];
        parent::__construct($data);
    }
}
