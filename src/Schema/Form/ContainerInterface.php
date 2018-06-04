<?php
namespace KiwiSuite\Admin\Schema\Form;

interface ContainerInterface
{
    public function addElement(ElementInterface $element): ContainerInterface;

    public function createElement(string $name): ElementInterface;

    public function isAvailable(string $name): bool;

    public function add(\Closure $closure): ContainerInterface;

    public function has(string $name): bool;

    public function get(string $name): ElementInterface;

    public function remove(string $name): ContainerInterface;

    public function elements(): array;
}
