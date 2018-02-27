<?php
namespace KiwiSuite\Admin\Resource;

interface ResourceInterface
{
    public static function name(): string;

    public function repository(): string;

    public function icon(): string;
}
