<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Schema\User;

use DateTimeZone;
use Ixocreate\Admin\Config\AdminConfig;
use Ixocreate\Collection\Collection;
use Ixocreate\Schema\AdditionalSchemaInterface;
use Ixocreate\Schema\BuilderInterface;
use Ixocreate\Schema\Elements\SelectElement;
use Ixocreate\Schema\Schema;
use Ixocreate\Schema\SchemaInterface;

final class LocaleAttributesSchema implements AdditionalSchemaInterface
{
    /**
     * @var AdminConfig
     */
    protected $adminConfig;

    /**
     * @var array
     */
    protected $locales;

    /**
     * @var array
     */
    protected $numberLocales;

    /**
     * @var array
     */
    protected $timezoneIdentifiers;

    /**
     * AccountAttributesSchema constructor.
     * @param AdminConfig $adminConfig
     */
    public function __construct(AdminConfig $adminConfig)
    {
        $this->adminConfig = $adminConfig;

        $this->locales = [
            'en_US' => 'English (US)',
            'de_AT' => 'German (Austria)',
        ];

        $this->numberLocales = [
            'en_US' => '1,234.56',
            'de_AT' => '1 234,56',
        ];

        $this->timezoneIdentifiers = (new Collection(DateTimeZone::listIdentifiers()))
            ->indexBy(function ($value) {
                return $value;
            })
            ->toArray();
    }

    /**
     * @return string
     */
    public static function serviceName(): string
    {
        return "localeAttributes";
    }

    /**
     * @param BuilderInterface $builder
     * @return SchemaInterface
     */
    public function additionalSchema(BuilderInterface $builder): SchemaInterface
    {
        $schema = new Schema();
        $schema = $schema
            ->withAddedElement(
                $builder->create(SelectElement::class, 'locale')
                    ->withLabel('Language')
                    ->withOptions($this->locales)
            )
            ->withAddedElement(
                $builder->create(SelectElement::class, 'dateLocale')
                    ->withLabel('Date & Time Format')
                    ->withOptions($this->locales)
            )
            /**
             * TODO: remove? not required as dateLocale includes time formatting
             */
            //->withAddedElement(
            //    $builder->create(SelectElement::class, 'timeLocale')
            //        ->withLabel('Time Format')
            //        ->withDescription('Defaults to Locale / Language')
            //        ->withOptions($this->locales)
            //)
            ->withAddedElement(
                $builder->create(SelectElement::class, 'numberLocale')
                    ->withLabel('Number Format')
                    ->withOptions($this->numberLocales)
            )
            ->withAddedElement(
                $builder->create(SelectElement::class, 'timezone')
                    ->withLabel('Timezone')
                    ->withOptions($this->timezoneIdentifiers)
            );
        return $schema;
    }
}
