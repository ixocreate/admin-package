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

namespace KiwiSuite\Admin\Resource;

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
