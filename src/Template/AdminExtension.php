<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Template;

use Ixocreate\Admin\Config\AdminConfig;
use Ixocreate\Admin\Router\AdminRouter;
use Ixocreate\Template\Extension\ExtensionInterface;
use PackageVersions\Versions;

class AdminExtension implements ExtensionInterface
{
    /**
     * @var AdminConfig
     */
    protected $adminConfig;

    /**
     * @var AdminRouter
     */
    private $adminRouter;

    /**
     * IndexAction constructor.
     * @param AdminConfig $adminConfig
     * @param AdminRouter $adminRouter
     */
    public function __construct(AdminConfig $adminConfig, AdminRouter $adminRouter)
    {
        $this->adminConfig = $adminConfig;
        $this->adminRouter = $adminRouter;
    }

    public function __invoke()
    {
        return $this;
    }

    public static function getName(): string
    {
        return 'admin';
    }

    public function getAdminConfig()
    {
        return $this->adminConfig;
    }

    public function generateUri($name)
    {
        return $this->adminRouter->generateUri($name);
    }

    /**
     * @return array
     */
    public function assetsPaths()
    {
        $scripts = [
            'runtime' => null,
            'polyfills' => null,
            'scripts' => null,
            'main' => null,
        ];

        $styles = [
            'styles' => null,
        ];

        $version = \sha1(\uniqid());

        try {
            $version = Versions::getVersion(Versions::ROOT_PACKAGE_NAME);
        } catch (\Exception $exception) {
            try {
                $version = Versions::getVersion('ixocreate/admin-frontend');
            } catch (\Exception $exception) {
            }
        }

        if (\mb_strpos($version, '@') !== false) {
            $version = \mb_substr($version, \mb_strpos($version, '@') + 1);
        }

        foreach (\array_keys($this->adminConfig->adminBuildFiles()) as $name) {
            foreach ($scripts as $scriptName => $value) {
                if ($value !== null) {
                    continue;
                }
                if (\mb_substr($name, 0, \mb_strlen($scriptName)) === $scriptName) {
                    $scripts[$scriptName] = $this->adminRouter->generateUri('admin.static', ['file' => $name]) . '?v=' . $version;

                    continue 2;
                }
            }

            foreach ($styles as $stylesName => $value) {
                if ($value !== null) {
                    continue;
                }

                if (\mb_substr($name, 0, \mb_strlen($stylesName)) === $stylesName) {
                    $styles[$stylesName] = $this->adminRouter->generateUri('admin.static', ['file' => $name]) . '?v=' . $version;
                    continue 2;
                }
            }
        }

        return ['scripts' => $scripts, 'styles' => $styles];
    }
}
