<?php

namespace AvtoDev\FirebaseNotificationsChannel\Tests\PlatformSettings;

use AvtoDev\FirebaseNotificationsChannel\Tests\AbstractTestCase;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Class AbstractPlatformSettingsTest
 * @package AvtoDev\FirebaseNotificationsChannel\Tests\PlatformSettings
 */
abstract class AbstractPlatformSettingsTest extends AbstractTestCase
{
    /**
     * @return array
     */
    abstract public function dataProvider(): array;

    /**
     * @param $property
     * @param $array_path
     * @param $value
     *
     * @throws \ReflectionException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @dataProvider dataProvider
     */
    public function testSetters($property, $array_path, $value): void
    {
        $platform_settings = $this->getPlatformSetting();

        $platform_settings->{'set' . Str::camel($property)}($value);

        static::assertEquals($value, static::getProperty($platform_settings, $property));
        static::assertEquals($value, Arr::get($platform_settings->toArray(), $array_path));
    }

    /**
     * @return Arrayable
     */
    abstract protected function getPlatformSetting(): Arrayable;
}
