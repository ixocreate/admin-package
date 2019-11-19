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
use Ixocreate\Intl\LocaleManager;

final class IntlProvider implements ClientConfigProviderInterface
{
    /**
     * @var LocaleManager
     */
    private $localeManager;

    public function __construct(LocaleManager $localeManager)
    {
        $this->localeManager = $localeManager;
    }

    public static function serviceName(): string
    {
        return 'intl';
    }

    public function clientConfig(?UserInterface $user = null): array
    {
        if (empty($user)) {
            return [];
        }

        return [
            'default' => $this->localeManager->defaultLocale(),
            'locales' => $this->localeManager->all(),
        ];
    }
}
