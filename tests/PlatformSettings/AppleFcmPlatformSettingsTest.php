<?php

namespace Feugene\FirebaseNotificationsChannel\Tests\PlatformSettings;

use Feugene\FirebaseNotificationsChannel\PlatformSettings\AppleFcmPlatformSettings;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @coversDefaultClass \Feugene\FirebaseNotificationsChannel\PlatformSettings\AppleFcmPlatformSettings
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
            ['title', 'payload.aps.alert.title', 'title_test'],
            ['body', 'payload.aps.alert.body', 'body_test'],
            ['title_loc_key', 'payload.aps.alert.title-loc-key', 'title_loc_key_test'],
            ['title_loc_args', 'payload.aps.alert.title-loc-args', ['title_loc_args_1', 'title_loc_args_2']],
            ['action_loc_key', 'payload.aps.alert.action-loc-key', 'action_loc_key_test'],
            ['loc_key', 'payload.aps.alert.loc-key', 'loc_key_test'],
            ['loc_args', 'payload.aps.alert.loc-args', ['loc_args_1', 'loc_args_2']],
            ['launch_image', 'payload.aps.alert.launch-image', 'launch_image_test'],
            ['badge', 'payload.aps.badge', 123],
            ['sound', 'payload.aps.sound', 'sound_test'],
            ['content_available', 'payload.aps.content-available', 234],
            ['category', 'payload.aps.category', 'category_test'],
            ['thread_id', 'payload.aps.thread-id', 'thread_id_test'],
            ['mutable_content', 'payload.aps.mutable-content', 1],
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
