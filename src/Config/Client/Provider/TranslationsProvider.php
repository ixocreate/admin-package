<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Config\Client\Provider;

use Ixocreate\Admin\ClientConfigProviderInterface;
use Ixocreate\Admin\UserInterface;

final class TranslationsProvider implements ClientConfigProviderInterface
{
    public function __construct()
    {
    }

    public static function serviceName(): string
    {
        return 'translations';
    }

    public function clientConfig(?UserInterface $user = null): array
    {
        $translations = [];

        /**
         * TODO: read translations
         */

        return $translations;
    }
}
