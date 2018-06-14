<?php

namespace KiwiSuite\Admin\Schema;

use KiwiSuite\Schema\Builder;
use KiwiSuite\Schema\Schema;

final class SchemaBuilder implements \JsonSerializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $namePlural;

    /**
     * @var array
     */
    private $list = [];

    /**
     * @var Schema
     */
    private $schema;

    /**
     * @var Builder
     */
    private $builder;

    /**
     * SchemaBuilder constructor.
     * @param Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param string $name
     * @return SchemaBuilder
     */
    public function withName(string $name): SchemaBuilder
    {
        $schemaBuilder = clone $this;
        $schemaBuilder->name = $name;

        return $schemaBuilder;
    }

    /**
     * @param string $namePlural
     * @return SchemaBuilder
     */
    public function withNamePlural(string $namePlural): SchemaBuilder
    {
        $schemaBuilder = clone $this;
        $schemaBuilder->namePlural = $namePlural;

        return $schemaBuilder;
    }

    /**
     * @param string $key
     * @param string $label
     * @return SchemaBuilder
     */
    public function withAddListField(string $key, string $label): SchemaBuilder
    {
        $schemaBuilder = clone $this;

        $schemaBuilder->list[$key] = [
            'key' => $key,
            'name' => $label,
        ];
        return $schemaBuilder;
    }

    /**
     * @param Schema $schema
     * @return SchemaBuilder
     */
    public function withSchema(Schema $schema): SchemaBuilder
    {
        $schemaBuilder = clone $this;
        $schemaBuilder->schema = $schema;

        return $schemaBuilder;
    }

    /**
     * @return Builder
     */
    public function builder(): Builder
    {
        return $this->builder;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'name'       => $this->name,
            'namePlural' => $this->namePlural,
            'list'       => \array_values($this->list),
            'filter'     => [],
            'form'       => $this->schema
        ];
    }
}
