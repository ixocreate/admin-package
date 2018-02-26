<?php
namespace KiwiSuite\Admin\Role;

use KiwiSuite\ServiceManager\ServiceManagerConfig;

final class RoleServiceManagerConfig extends ServiceManagerConfig
{
    /**
     * @var array
     */
    private $roleMapping = [];

    public function __construct(
        array $roleMapping = [],
        array $factories = [],
        array $subManagers = [],
        array $delegators = [],
        array $lazyServices = [],
        array $disabledSharing = [],
        array $initializers = []
    ) {
        $this->roleMapping = $roleMapping;

        parent::__construct($factories, $disabledSharing, $delegators, $initializers, $lazyServices, $subManagers);
    }

    /**
     * @return array
     */
    public function getRoleMapping()
    {
        return $this->roleMapping;
    }

    public function serialize()
    {
        return \serialize([
            'roleMapping' => $this->roleMapping,
            'config' => $this->getInternalConfig(),
        ]);
    }

    public function unserialize($serialized)
    {
        $data = \unserialize($serialized);

        $this->roleMapping = $data['roleMapping'];
        $this->setInternalConfig($data['config']);
    }
}
