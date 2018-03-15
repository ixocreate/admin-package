<?php
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package kiwi-suite/admin
 * @see https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);

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
