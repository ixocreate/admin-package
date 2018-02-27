<?php
namespace KiwiSuite\Admin\Resource;

use KiwiSuite\ServiceManager\ServiceManagerConfig;

final class ResourceServiceManagerConfig extends ServiceManagerConfig
{
    /**
     * @var array
     */
    private $resourceMapping = [];

    public function __construct(
        array $resourceMapping = [],
        array $factories = [],
        array $subManagers = [],
        array $delegators = [],
        array $lazyServices = [],
        array $disabledSharing = [],
        array $initializers = []
    ) {
        $this->resourceMapping = $resourceMapping;

        parent::__construct($factories, $disabledSharing, $delegators, $initializers, $lazyServices, $subManagers);
    }

    /**
     * @return array
     */
    public function getResourceMapping()
    {
        return $this->resourceMapping;
    }

    public function serialize()
    {
        return \serialize([
            'resourceMapping' => $this->resourceMapping,
            'config' => $this->getInternalConfig(),
        ]);
    }

    public function unserialize($serialized)
    {
        $data = \unserialize($serialized);

        $this->resourceMapping = $data['resourceMapping'];
        $this->setInternalConfig($data['config']);
    }
}
