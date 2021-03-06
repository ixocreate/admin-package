<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin;

use Ixocreate\Application\Config\ConfigProviderInterface;

final class ConfigProvider implements ConfigProviderInterface
{
    public function __invoke(): array
    {
        return [
            'admin' => [
                'uri' => '/admin',
            ],
        ];
    }

    public function configName(): string
    {
        return 'admin';
    }

    public function configContent(): string
    {
        return \file_get_contents(__DIR__ . '/../resources/admin.config.example.php');
    }
}
