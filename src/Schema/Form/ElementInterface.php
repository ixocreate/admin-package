<?php
namespace KiwiSuite\Admin\Schema\Form;

interface ElementInterface
{
    public function toArray(): array;

    public function addOption(string $key, $value): ElementInterface;

    public function setRequired(bool $required): ElementInterface;

    public function setReadonly(bool $readonly): ElementInterface;
}
