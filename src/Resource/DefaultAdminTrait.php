<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Package\Admin\Resource;

trait DefaultAdminTrait
{
    /**
     * @return null|string
     */
    public function indexAction(): ?string
    {
        return null;
    }

    /**
     * @return null|string
     */
    public function detailAction(): ?string
    {
        return null;
    }

    /**
     * @return null|string
     */
    public function createSchemaAction(): ?string
    {
        return null;
    }

    /**
     * @return null|string
     */
    public function createAction(): ?string
    {
        return null;
    }

    /**
     * @return null|string
     */
    public function updateAction(): ?string
    {
        return null;
    }

    /**
     * @return null|string
     */
    public function deleteAction(): ?string
    {
        return null;
    }
}
