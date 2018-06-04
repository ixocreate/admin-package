<?php
declare(strict_types=1);
namespace KiwiSuite\Admin\Schema\Form\Elements;

use KiwiSuite\Admin\Schema\Form\ElementInterface;

abstract class AbstractProxyElement implements ElementInterface
{
    /**
     * @var Element
     */
    protected $element;

    public function __construct()
    {
        $this->element = new Element();
    }

    public function toArray(): array
    {
        return $this->element->toArray();
    }

    public function setName(string $name): ElementInterface
    {
        $this->element->setName($name);

        return $this;
    }

    public function getName(): string
    {
        return $this->element->getName();
    }

    public function setLabel(string $label): ElementInterface
    {
        $this->element->setLabel($label);

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->element->getLabel();
    }

    public function addOption(string $key, $value): ElementInterface
    {
        $this->element->addOption($key, $value);

        return $this;
    }

    public function getOptions(): array
    {
        return $this->element->getOptions();
    }

    public function setRequired(bool $required): ElementInterface
    {
        $this->element->setRequired($required);

        return $this;
    }

    public function isRequired(): bool
    {
        return $this->element->isRequired();
    }

    public function setReadonly(bool $readonly): ElementInterface
    {
        $this->element->setReadonly($readonly);

        return $this;
    }

    public function isReadonly(): bool
    {
        return $this->element->isReadonly();
    }

    public function getType(): string
    {
        return $this->element->getType();
    }
}
