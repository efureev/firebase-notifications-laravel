<?php

namespace AvtoDev\FirebaseNotificationsChannel\Tests\Receivers;

use AvtoDev\FirebaseNotificationsChannel\Receivers\FcmNotificationReceiverInterface;
use AvtoDev\FirebaseNotificationsChannel\Tests\AbstractTestCase;

/**
 * Class AbstractReceiverTest
 * @package AvtoDev\FirebaseNotificationsChannel\Tests\Receivers
 */
abstract class AbstractReceiverTest extends AbstractTestCase
{
    protected $target_name;

    protected $target_value;

    /**
     * @covers ::__construct
     *
     * @throws \ReflectionException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testGetConstruct(): void
    {
        static::assertEquals($this->target_value, self::getProperty($this->getReceiver(), $this->target_name));
    }

    /**
     * @covers ::getTarget()
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testGetTargetArray(): void
    {
        static::assertEquals([$this->target_name => $this->target_value], $this->getReceiver()->getTarget());
    }

    /**
     * @return FcmNotificationReceiverInterface
     */
    abstract protected function getReceiver(): FcmNotificationReceiverInterface;
}
