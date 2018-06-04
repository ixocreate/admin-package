<?php
namespace KiwiSuite\Admin\Schema;

use KiwiSuite\Admin\Schema\Form\Elements\Form;
use KiwiSuite\Admin\Schema\Form\ElementSubManager;

final class SchemaInstantiator
{
    /**
     * @var ElementSubManager
     */
    private $elementSubManager;

    public function __construct(ElementSubManager $elementSubManager)
    {
        $this->elementSubManager = $elementSubManager;
    }

    public function createSchemaBuilder(): SchemaBuilder
    {
        return new SchemaBuilder($this->elementSubManager->build(Form::class));
    }
}
