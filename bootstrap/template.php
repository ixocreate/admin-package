<?php
declare(strict_types=1);

namespace Ixocreate\Package\Admin;

use Ixocreate\Package\Admin\Template\AdminExtension;
use Ixocreate\Package\Template\TemplateConfigurator;

/** @var TemplateConfigurator $template */

$template->addDirectory('admin', __DIR__ . '/../templates/admin');
$template->addDirectory('email', __DIR__ . '/../templates/email');

$template->addExtension(AdminExtension::class);
