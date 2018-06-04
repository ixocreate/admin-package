<?php
namespace KiwiSuite\Admin\Schema\Form\Elements;

use KiwiSuite\Admin\Schema\Form\ContainerInterface;
use KiwiSuite\Admin\Schema\Form\ElementInterface;
use KiwiSuite\Admin\Schema\Form\ElementSubManager;
use KiwiSuite\Entity\Entity\Definition;

class Container extends AbstractProxyElement implements ContainerInterface
{
    protected $elements = [];

    protected $wrappers = [];

    /**
     * @var ElementSubManager
     */
    private $elementSubManager;

    public function __construct(ElementSubManager $elementSubManager)
    {
        parent::__construct();
        $this->elementSubManager = $elementSubManager;
    }

    public function addElement(ElementInterface $element): ContainerInterface
    {
        $this->elements[$element->getName()] = $element;
        return $this;
    }

    public function createElement(string $type): ElementInterface
    {
        return $this->elementSubManager->build($type);
    }

    public function createElementForType(string $type): ElementInterface
    {
        return $this->elementSubManager->build($this->elementSubManager->typeMappingFor($type));
    }
    
    public function isAvailable(string $name): bool
    {
        return $this->elementSubManager->has($name);
    }

    public function add(\Closure $closure): ContainerInterface
    {
        $reflectionFunction = new \ReflectionFunction($closure);
        $parameters = $reflectionFunction->getParameters();
        $parameter = current($parameters);

        $element = $this->createElement((string)$parameter->getType());

        $closure($element);

        $this->addElement($element);

        return $this;
    }

    public function has(string $name): bool
    {
        return array_key_exists($name, $this->elements);
    }

    public function get(string $name): ElementInterface
    {
        if (!$this->has($name)) {
            //TODO Exception
            throw new \Exception(sprintf("Element with name '%s' doesn't exist", $name));
        }

        return $this->elements[$name];
    }

    public function remove(string $name): ContainerInterface
    {
        if (!$this->has($name)) {
            return $this;
        }

        unset($this->elements[$name]);

        return $this;
    }

    public function addWrapper(string $wrapper): ContainerInterface
    {
        $this->wrappers[] = $wrapper;

        return $this;
    }

    public function wrappers(): array
    {
        return $this->wrappers;
    }

    public function elements(): array
    {
        return \array_values($this->elements);
    }

    public function fromEntity(string $entity)
    {
        $definitions = $entity::getDefinitions();

        /** @var Definition $definition */
        foreach ($definitions as $definition) {
            if ($definition->getName() === "id") {
                continue;
            }
            if ($definition->getName() === "createdAt") {
                continue;
            }
            if ($definition->getName() === "updatedAt") {
                continue;
            }

            $element = $this->createElementForType($definition->getType());
            $element->setName($definition->getName());
            $element->setLabel(ucfirst($definition->getName()));

            $this->addElement($element);
        }
    }
}
