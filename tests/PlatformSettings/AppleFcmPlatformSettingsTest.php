<?php

namespace AvtoDev\FirebaseNotificationsChannel\Tests\PlatformSettings;

use AvtoDev\FirebaseNotificationsChannel\PlatformSettings\AppleFcmPlatformSettings;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @coversDefaultClass \AvtoDev\FirebaseNotificationsChannel\PlatformSettings\AppleFcmPlatformSettings
 */
class AppleFcmPlatformSettingsTest extends AbstractPlatformSettingsTest
{
    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            ['headers', 'headers', ['test_header', 'test_header2']],
            ['title', 'payload.alert.title', 'title_test'],
            ['body', 'payload.alert.body', 'body_test'],
            ['title_loc_key', 'payload.alert.title-loc-key', 'title_loc_key_test'],
            ['title_loc_args', 'payload.alert.title-loc-args', ['title_loc_args_1', 'title_loc_args_2']],
            ['action_loc_key', 'payload.alert.action-loc-key', 'action_loc_key_test'],
            ['loc_key', 'payload.alert.loc-key', 'loc_key_test'],
            ['loc_args', 'payload.alert.loc-args', ['loc_args_1', 'loc_args_2']],
            ['launch_image', 'payload.alert.launch-image', 'launch_image_test'],
            ['badge', 'payload.badge', 123],
            ['sound', 'payload.sound', 'sound_test'],
            ['content_available', 'payload.content-available', 234],
            ['category', 'payload.category', 'category_test'],
            ['thread_id', 'payload.thread-id', 'thread_id_test'],
        ];
    }

    /**
     * @throws \ReflectionException
     */
    public function testMutableContent(): void
    {
        /** @var AppleFcmPlatformSettings $platform_settings */
        $platform_settings = $this->getPlatformSetting();

        static::assertNull(static::getProperty($platform_settings, 'mutable_content'));
        $platform_settings->enableMutableContent();
        static::assertTrue(static::getProperty($platform_settings, 'mutable_content'));
        $platform_settings->disableMutableContent();
        static::assertNull(static::getProperty($platform_settings, 'mutable_content'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getPlatformSetting(): Arrayable
    {
        return new AppleFcmPlatformSettings;
    }
}
