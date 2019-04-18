<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Router;

use Ixocreate\Admin\Config\AdminConfig;
use Zend\Expressive\Router\FastRouteRouter;

final class AdminRouter extends FastRouteRouter
{
    /**
     * @var AdminConfig
     */
    private $adminConfig;

    /**
     * AdminRouter constructor.
     * @param AdminConfig $adminConfig
     */
    public function __construct(AdminConfig $adminConfig)
    {
        $this->adminConfig = $adminConfig;
        parent::__construct();
    }

    /**
     * @param string $name
     * @param array $substitutions
     * @param array $options
     * @return string
     */
    public function generateUri(string $name, array $substitutions = [], array $options = []) : string
    {
        $path = parent::generateUri($name, $substitutions, $options);

        $uri = $this->adminConfig->uri()->withPath($this->adminConfig->uri()->getPath() . $path);

        return (string) $uri;
    }
}
