<?php
namespace KiwiSuite\Admin\Middleware\Factory;

use KiwiSuite\ServiceManager\FactoryInterface;
use KiwiSuite\ServiceManager\ServiceManagerInterface;
use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;
use Zend\Expressive\Helper\BodyParams\JsonStrategy;

final class JsonBodyParamsFactory implements FactoryInterface
{

    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return mixed
     */
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null)
    {
        $bodyParamsFactory = new BodyParamsMiddleware();
        $bodyParamsFactory->clearStrategies();
        $bodyParamsFactory->addStrategy(new JsonStrategy());

        return $bodyParamsFactory;
    }
}
