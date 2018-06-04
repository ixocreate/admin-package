<?php
namespace KiwiSuite\Admin\Schema\Form;

interface ElementInterface
{
    public function toArray(): array;

    public function setName(string $name): ElementInterface;

    public function getName(): string;

    public function setLabel(string $label): ElementInterface;

    public function getLabel(): ?string;

    public function addOption(string $key, $value): ElementInterface;

    public function getOptions(): array;

    public function setRequired(bool $required): ElementInterface;

    public function isRequired(): bool;

    public function setReadonly(bool $readonly): ElementInterface;

    public function isReadonly(): bool;

    public function getType(): string;
}
