<?php

namespace AvtoDev\FirebaseNotificationsChannel\Tests\Exceptions;

use AvtoDev\FirebaseNotificationsChannel\Exceptions\CouldNotSendNotification;
use AvtoDev\FirebaseNotificationsChannel\Tests\AbstractTestCase;

/**
 * Class CouldNotSendNotificationTest.
 *
 * @coversDefaultClass \AvtoDev\FirebaseNotificationsChannel\Exceptions\CouldNotSendNotification
 */
class CouldNotSendNotificationTest extends AbstractTestCase
{
    /**
     * Check exception message.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testInvalidNotification()
    {
        static::assertInstanceOf(
            CouldNotSendNotification::class,
            CouldNotSendNotification::invalidNotification()
        );

        static::assertEquals(
            'Can\'t convert notification to FCM message',
            CouldNotSendNotification::invalidNotification()->getMessage()
        );
    }
}
