<?php
namespace KiwiSuite\Admin;

/** @var ResourceConfigurator $resource */
use KiwiSuite\Admin\Resource\UserResource;
use KiwiSuite\Resource\SubManager\ResourceConfigurator;

$resource->addResource(UserResource::class);
