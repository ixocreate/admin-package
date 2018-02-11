<?php
namespace KiwiSuite\Admin\Authentication;

interface UserInterface
{
    public function getRole() : string;
}
