<?php

namespace Feugene\FirebaseNotificationsChannel\Tests\Receivers;

use Feugene\FirebaseNotificationsChannel\Receivers\FcmNotificationReceiverInterface;
use Feugene\FirebaseNotificationsChannel\Receivers\FcmTopicReceiver;

/**
 * @coversDefaultClass \Feugene\FirebaseNotificationsChannel\Receivers\FcmTopicReceiver
 */
class FcmTopicReceiverTest extends AbstractReceiverTest
{
    protected $target_name = 'topic';

    protected $target_value = 'test_topic';

    /**
     * @covers ::getTopic()
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testGetTarget(): void
    {
        static::assertEquals($this->target_value, $this->getReceiver()->getTopic());
    }

    /**
     * @return FcmNotificationReceiverInterface|FcmTopicReceiver
     */
    protected function getReceiver(): FcmNotificationReceiverInterface
    {
        return new FcmTopicReceiver($this->target_value);
    }
}
