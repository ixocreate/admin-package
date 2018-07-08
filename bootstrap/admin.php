<?php
namespace App;
/** @var \KiwiSuite\Admin\Config\AdminConfigurator $admin */

$admin->setName("kiwi");
$admin->setAuthor("kiwi suite GmbH");
$admin->setDescription("kiwi");
$admin->setCopyright(date("Y"));
$admin->setPoweredBy(true);
$admin->setBackground("/admin/assets/img/kiwi-icon.svg");
$admin->setLogo("/admin/assets/img/kiwi-logo.svg");
$admin->setIcon("/admin/assets/img/kiwi-icon.svg");

$contentGroup = $admin->addNavigationGroup("Content", 5000);
$contentGroup->add("Sitemap", ['admin.api.media.index'], 'fa fa-image', '/page', 2000);
$contentGroup->add("Media", ['admin.api.media.index'], 'fa fa-image', '/media', 1000);

$admin->addNavigationGroup("Settings", 1000)
    ->add('Users', ['admin.api.user.index'], 'fa fa-users', '/admin-user', 1000);
