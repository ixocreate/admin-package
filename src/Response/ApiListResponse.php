<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Response;

use Ixocreate\Resource\ResourceInterface;

class ApiListResponse extends ApiSuccessResponse
{
    /**
     * @var ResourceInterface
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

    public function __construct(ResourceInterface $resource, array $items, array $meta)
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

    public function resource(): ResourceInterface
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
