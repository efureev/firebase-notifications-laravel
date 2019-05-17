<?php

namespace Feugene\FirebaseNotificationsChannel\Tests\Exceptions;

use Feugene\FirebaseNotificationsChannel\Exceptions\CouldNotSendNotification;
use Feugene\FirebaseNotificationsChannel\Tests\AbstractTestCase;

/**
 * Class CouldNotSendNotificationTest.
 *
 * @coversDefaultClass \Feugene\FirebaseNotificationsChannel\Exceptions\CouldNotSendNotification
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
