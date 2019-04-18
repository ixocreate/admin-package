<?php
declare(strict_types=1);

namespace Ixocreate\Admin\Package;

use Ixocreate\Admin\Package\Template\AdminExtension;
use Ixocreate\Template\Package\TemplateConfigurator;

/** @var TemplateConfigurator $template */

$template->addDirectory('admin', __DIR__ . '/../templates/admin');
$template->addDirectory('email', __DIR__ . '/../templates/email');

$template->addExtension(AdminExtension::class);
