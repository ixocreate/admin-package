<?php
namespace KiwiSuite\Admin\Pipe;

use KiwiSuite\Contract\Application\ConfiguratorInterface;
use KiwiSuite\Contract\Application\ServiceRegistryInterface;

/**
 * @method void any(string $path, string $action, string $name, callable $callback = null)
 * @method void get(string $path, string $action, string $name, callable $callback = null)
 * @method void post(string $path, string $action, string $name, callable $callback = null)
 * @method void patch(string $path, string $action, string $name, callable $callback = null)
 * @method void put(string $path, string $action, string $name, callable $callback = null)
 * @method void delete(string $path, string $action, string $name, callable $callback = null)
 * @method array getRoutes()
 * @method array getMiddlewarePipe()
 * @method void group(callable $callback)
 * @method void segment(string $segment, callable $callback, int $priority = 1000000)
 * @method void pipe(string $middleware, int $priority = 1000000)
 */
final class PipeConfigurator implements ConfiguratorInterface
{
    /**
     * @var \KiwiSuite\ApplicationHttp\Pipe\PipeConfigurator
     */
    private $internalPipeConfigurator;

    /**
     * PipeConfigurator constructor.
     * @param string $prefix
     */
    public function __construct(string $prefix = "")
    {
        $this->internalPipeConfigurator = new \KiwiSuite\ApplicationHttp\Pipe\PipeConfigurator($prefix);
    }

    /**
     * @param string $name
     * @param mixed $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->internalPipeConfigurator, $name), $arguments);
    }

    /**
     * @param ServiceRegistryInterface $serviceRegistry
     * @return void
     */
    public function registerService(ServiceRegistryInterface $serviceRegistry): void
    {
        $serviceRegistry->add(PipeConfig::class, new PipeConfig($this->internalPipeConfigurator));
    }
}
