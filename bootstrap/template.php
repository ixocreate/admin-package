<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Template;

use Ixocreate\Template\TemplateConfigurator;

/** @var TemplateConfigurator $template */
$template->addDirectory('admin', __DIR__ . '/../templates/admin');
$template->addDirectory('email', __DIR__ . '/../templates/email');

$template->addExtension(AdminExtension::class);
