<?php

namespace Feugene\FirebaseNotificationsChannel\Tests;

use Feugene\FirebaseNotificationsChannel\FcmMessage;
use Feugene\FirebaseNotificationsChannel\PlatformSettings\AndroidFcmPlatformSettings;
use Feugene\FirebaseNotificationsChannel\PlatformSettings\AppleFcmPlatformSettings;
use Feugene\FirebaseNotificationsChannel\PlatformSettings\WebpushFcmPlatformSettings;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Class FcmMessageTest.
 *
 * @covers \Feugene\FirebaseNotificationsChannel\FcmMessage
 */
class FcmMessageTest extends AbstractTestCase
{
    /**
     * @var FcmMessage
     */
    protected $fcm_message;

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->fcm_message = new FcmMessage;
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            ['data', ['key' => 'value', 'obj' => ['id' => 2, 'value' => ['test' => true]]]],
            ['title', 'title text', 'notification.title'],
            ['body', 'body text', 'notification.body'],
            ['android', new AndroidFcmPlatformSettings],
            ['webpush', new WebpushFcmPlatformSettings],
            ['apns', new AppleFcmPlatformSettings],
        ];
    }

    /**
     * @param $property
     * @param $value
     * @param $path
     *
     * @throws \ReflectionException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @dataProvider dataProvider
     */
    public function testSetters($property, $value, $path = null): void
    {
        if ($property === 'data') {
            $this->datumLine($property, $value);
            return;
        }

        $this->fcm_message->{'set' . Str::title($property)}($value);

        static::assertEquals($value, static::getProperty($this->fcm_message, $property));

        if ($path === null) {
            $path = $property;
        }

        if ($value instanceof Arrayable) {
            static::assertEquals($value, $this->fcm_message->{'get' . Str::title($property)}());
            $value = $value->toArray();
        }

        static::assertEquals($value, Arr::get($this->fcm_message->toArray(), $path));
    }

    /**
     * @param $property
     * @param $value
     * @throws \ReflectionException
     */
    public function datumLine($property, $value): void
    {
        $this->fcm_message->{'set' . Str::title($property)}($value);

        static::assertJsonStringEqualsJsonString(\json_encode($value), \json_encode(static::getProperty($this->fcm_message, $property)));

        static::assertEquals([
            'key' => 'value',
            'obj.id' => '2',
            'obj.value.test' => '1'
        ], $this->fcm_message->toArray()['data']);
    }
}
