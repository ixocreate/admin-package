<?php
namespace KiwiSuite\Admin\Resource;

interface ResourceInterface
{
    public static function name(): string;

    public function repository(): string;

    public function icon(): string;

    public function createMessage(): string;

    public function updateMessage(): string;

    public function deleteMessage(): string;

    public function createHandler(): array;

    public function updateHandler(): array;

    public function deleteHandler(): array;

    public function indexAction(): ?string;
}
