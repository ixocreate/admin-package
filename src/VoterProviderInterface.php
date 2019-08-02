<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin;

interface VoterProviderInterface
{
    /**
     * @return string[]
     */
    public function voters(): array;
}
