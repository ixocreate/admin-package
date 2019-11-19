<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin;

use Ixocreate\Admin\Config\Client\Provider\IntlProvider;
use Ixocreate\Admin\Config\Client\Provider\NavigationProvider;
use Ixocreate\Admin\Config\Client\Provider\ProjectProvider;
use Ixocreate\Admin\Config\Client\Provider\ResourceProvider;
use Ixocreate\Admin\Config\Client\Provider\RoutesProvider;
use Ixocreate\Admin\Config\Client\Provider\TranslationsProvider;
use Ixocreate\Admin\Role\AdministratorRole;

/** @var AdminConfigurator $admin */
$admin->addRole(AdministratorRole::class);

$admin->addClientProvider(ProjectProvider::class);
$admin->addClientProvider(NavigationProvider::class);
$admin->addClientProvider(RoutesProvider::class);
$admin->addClientProvider(IntlProvider::class);
$admin->addClientProvider(ResourceProvider::class);
$admin->addClientProvider(TranslationsProvider::class);

$admin->setName("IXOCREATE");
$admin->setAuthor("IXOLIT GmbH");
$admin->setDescription("IXOCREATE");
$admin->setCopyright(\date("Y"));
$admin->setPoweredBy(true);

$admin->setDefaultLocale('en_US');
$admin->setDefaultTimezone('UTC');

$contentGroup = $admin->addNavigationGroup("Content", 5000);
$contentGroup->add("Sitemap", ['admin.api.sitemap.index'], 'fa fa-sitemap', '/page', 2000);
$contentGroup->add("Media", ['admin.api.media.index'], 'fa fa-image', '/media', 1000);
$contentGroup->add("Translation", ['admin.api.translation.index'], 'fa fa-globe', '/translation', 500);

$admin->addNavigationGroup("Settings", 1000)
    ->add('Users', ['admin.api.edituser.user.index'], 'fa fa-users', '/admin-user', 1000);
