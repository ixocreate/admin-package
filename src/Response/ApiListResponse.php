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

class ApiListResponse extends ApiSuccessResponse
{
    public function __construct(AdminAwareInterface $resource, array $items, array $schema, array $meta)
    {
        $data = [
            'label' => $resource->label(),
            'items' => $items,
            'schema' => $schema,
            'meta' => $meta,
        ];
        parent::__construct($data);
    }
}
