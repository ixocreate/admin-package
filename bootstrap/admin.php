<?php
namespace App;
use Ixocreate\Admin\Config\Client\Provider\IntlProvider;
use Ixocreate\Admin\Config\Client\Provider\NavigationProvider;
use Ixocreate\Admin\Config\Client\Provider\ProjectProvider;
use Ixocreate\Admin\Config\Client\Provider\ResourceProvider;
use Ixocreate\Admin\Config\Client\Provider\RoutesProvider;
use Ixocreate\Admin\Role\AdministratorRole;

/** @var \Ixocreate\Admin\Config\AdminConfigurator $admin */

$admin->addRole(AdministratorRole::class);

$admin->addClientProvider(ProjectProvider::class);
$admin->addClientProvider(NavigationProvider::class);
$admin->addClientProvider(RoutesProvider::class);
$admin->addClientProvider(IntlProvider::class);
$admin->addClientProvider(ResourceProvider::class);

$admin->setName("kiwi");
$admin->setAuthor("kiwi suite GmbH");
$admin->setDescription("kiwi");
$admin->setCopyright(date("Y"));
$admin->setPoweredBy(true);
$admin->setBackground("/admin/kiwi-icon.svg");
$admin->setLogo("/admin//kiwi-logo.svg");
$admin->setIcon("/admin/kiwi-icon.svg");

$contentGroup = $admin->addNavigationGroup("Content", 5000);
$contentGroup->add("Sitemap", ['admin.api.sitemap.index'], 'fa fa-sitemap', '/page', 2000);
$contentGroup->add("Media", ['admin.api.media.index'], 'fa fa-image', '/media', 1000);
$contentGroup->add("Translation", ['admin.api.translation.index'], 'fa fa-globe', '/translation', 500);

$admin->addNavigationGroup("Settings", 1000)
    ->add('Users', ['admin.api.user.index'], 'fa fa-users', '/admin-user', 1000);
