<?php

namespace Feugene\FirebaseNotificationsChannel\Tests\Receivers;

use Feugene\FirebaseNotificationsChannel\Receivers\FcmDeviceReceiver;
use Feugene\FirebaseNotificationsChannel\Receivers\FcmNotificationReceiverInterface;

/**
 * @coversDefaultClass \Feugene\FirebaseNotificationsChannel\Receivers\FcmDeviceReceiver
 */
class FcmDeviceReceiverTest extends AbstractReceiverTest
{
    protected $target_name = 'token';

    protected $target_value = 'test_token';

    /**
     * @covers ::getToken()
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testGetTarget(): void
    {
        static::assertEquals($this->target_value, $this->getReceiver()->getToken());
    }

    /**
     * {@inheritdoc}
     *
     * @return FcmNotificationReceiverInterface|FcmDeviceReceiver
     */
    protected function getReceiver(): FcmNotificationReceiverInterface
    {
        return new FcmDeviceReceiver($this->target_value);
    }
}
