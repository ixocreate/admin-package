<?php
namespace KiwiSuite\Admin\Entity;

use KiwiSuite\Entity\Entity\Definition;
use KiwiSuite\Entity\Entity\DefinitionCollection;
use KiwiSuite\Entity\Entity\EntityInterface;
use KiwiSuite\Entity\Entity\EntityTrait;
use KiwiSuite\Entity\Type\TypeInterface;

final class SessionData implements EntityInterface
{
    use EntityTrait;

    private $userId;

    private $xsrfToken;

    /**
     * @return int|null
     */
    public function getUserId() :? int
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getXsrfToken() : string
    {
        return $this->xsrfToken;
    }

    private function createDefinitions() : DefinitionCollection
    {
        return new DefinitionCollection([
            new Definition("userId", TypeInterface::TYPE_INT, true, true),
            new Definition("xsrfToken", TypeInterface::TYPE_STRING, false, true),
        ]);
    }
}
