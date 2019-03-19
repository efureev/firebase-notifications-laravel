<?php

namespace AvtoDev\FirebaseNotificationsChannel\Tests;

use AvtoDev\FirebaseNotificationsChannel\FcmMessage;
use AvtoDev\FirebaseNotificationsChannel\PlatformSettings\AndroidFcmPlatformSettings;
use AvtoDev\FirebaseNotificationsChannel\PlatformSettings\AppleFcmPlatformSettings;
use AvtoDev\FirebaseNotificationsChannel\PlatformSettings\WebpushFcmPlatformSettings;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Class FcmMessageTest.
 *
 * @covers \AvtoDev\FirebaseNotificationsChannel\FcmMessage
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
            ['data', ['key' => 'value']],
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
}
