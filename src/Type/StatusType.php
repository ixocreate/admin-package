<?php
namespace KiwiSuite\Admin\Type;

use KiwiSuite\Entity\Type\Convert\Convert;
use KiwiSuite\Entity\Type\TypeInterface;

final class StatusType implements TypeInterface
{
    /**
     * @var string
     */
    private $value;

    /**
     * StatusType constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        if (!in_array($value, ['active', 'inactive'])) {
            //TODO Exception
           throw new \Exception("invalid type");
        }
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param $value
     * @return mixed
     */
    public static function convertToInternalType($value)
    {
        return Convert::convertString($value);
    }

    /**
     * @return mixed|string
     */
    public function jsonSerialize()
    {
        return $this->value;
    }
}
