<?php
declare(strict_types=1);

namespace Ixocreate\Admin\Schema\User;

use DateTimeZone;
use Ixocreate\Admin\Config\AdminConfig;
use Ixocreate\Contract\Schema\AdditionalSchemaInterface;
use Ixocreate\Contract\Schema\BuilderInterface;
use Ixocreate\Contract\Schema\SchemaInterface;
use Ixocreate\Schema\Elements\SelectElement;
use Ixocreate\Schema\Schema;

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

        $this->timezoneIdentifiers = DateTimeZone::listIdentifiers();
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
                    ->withLabel('Locale / Language')
                    ->withDescription('Primary Language & Formats / Defaults to system locale')
                    ->withOptions($this->locales)
            )
            ->withAddedElement(
                $builder->create(SelectElement::class, 'locale_date')
                    ->withLabel('Date Locale')
                    ->withDescription('Defaults to Locale / Language')
                    ->withOptions($this->locales)
            )
            ->withAddedElement(
                $builder->create(SelectElement::class, 'locale_time')
                    ->withLabel('Time Locale')
                    ->withDescription('Defaults to Locale / Language')
                    ->withOptions($this->locales)
            )
            ->withAddedElement(
                $builder->create(SelectElement::class, 'locale_number')
                    ->withLabel('Number Format Locale')
                    ->withDescription('Defaults to Locale / Language')
                    ->withOptions($this->locales)
            )
            ->withAddedElement(
                $builder->create(SelectElement::class, 'timezone')
                    ->withLabel('Timezone')
                    ->withDescription('Defaults to UTC')
                    ->withOptions($this->timezoneIdentifiers)
            );
        return $schema;
    }
}
