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

namespace Ixocreate\Admin\Response;

use Ixocreate\Contract\Resource\AdminAwareInterface;

class ApiListResponse extends ApiSuccessResponse
{
    /**
     * @var AdminAwareInterface
     */
    private $resource;

    /**
     * @var array
     */
    private $items;

    /**
     * @var array
     */
    private $meta;

    public function __construct(AdminAwareInterface $resource, array $items, array $meta)
    {
        $data = [
            'items' => $items,
            'meta' => $meta,
        ];
        parent::__construct($data);

        $this->resource = $resource;
        $this->items = $items;
        $this->meta = $meta;
    }

    public function resource(): AdminAwareInterface
    {
        return $this->resource;
    }

    public function items(): array
    {
        return $this->items;
    }

    public function meta(): array
    {
        return $this->meta;
    }
}
