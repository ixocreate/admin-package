<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Response;

use Ixocreate\Contract\Resource\AdminAwareInterface;

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
     * @var array
     */
    private $meta;

    public function __construct(AdminAwareInterface $resource, array $item, array $meta)
    {
        $data = [
            'label' => $resource->label(),
            'item' => (object)$item, // make sure an empty array here is an empty object in json
            'meta' => $meta,
        ];
        parent::__construct($data);
        $this->resource = $resource;
        $this->item = $item;
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

    public function meta(): array
    {
        return $this->meta;
    }
}
