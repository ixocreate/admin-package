<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package;

use Ixocreate\ServiceManager\NamedServiceInterface;

interface ClientConfigProviderInterface extends NamedServiceInterface
{
    /**
     * @param UserInterface|null $user
     * @return array
     */
    public function clientConfig(?UserInterface $user = null): array;
}
