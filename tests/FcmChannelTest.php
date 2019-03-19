<?php

namespace AvtoDev\FirebaseNotificationsChannel\Tests;

use AvtoDev\FirebaseNotificationsChannel\Exceptions\CouldNotSendNotification;
use AvtoDev\FirebaseNotificationsChannel\FcmChannel;
use AvtoDev\FirebaseNotificationsChannel\FcmClient;
use AvtoDev\FirebaseNotificationsChannel\FcmMessage;
use AvtoDev\FirebaseNotificationsChannel\Receivers\FcmTopicReceiver;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

/**
 * @coversDefaultClass \AvtoDev\FirebaseNotificationsChannel\FcmChannel
 */
class FcmChannelTest extends AbstractTestCase
{
    /**
     * @var FcmChannel
     */
    protected $firebase_channel;

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->firebase_channel = $this->app->make(FcmChannel::class);
    }

    /**
     * @covers ::__construct()
     *
     * @throws \ReflectionException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testConstruct(): void
    {
        self::assertInstanceOf(FcmClient::class, self::getProperty($this->firebase_channel, 'fcm_client'));
    }

    /**
     * Success notification sending.
     *
     * @covers ::send()
     *
     * @throws CouldNotSendNotification
     * @throws \ReflectionException
     */
    public function testSendSuccess(): void
    {
        $response = new Response(200, [], \json_encode(['message_id' => 'test']));
        $this->mock_handler->append($response);

        $this->firebase_channel->send($this->getNotifiableMock(), $this->getNotificationMock());
    }

    /**
     * Check notification without "toFcm" method.
     *
     * @covers ::send()
     *
     * @throws CouldNotSendNotification
     * @throws \ReflectionException
     */
    public function testSendNoToFcm(): void
    {
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('Can\'t convert notification to FCM message');

        $notification = $this
            ->getMockBuilder(Notification::class)
            ->getMock();

        $this->firebase_channel->send($this->getNotifiableMock(), $notification);
    }

    /**
     * @covers ::send()
     *
     * @throws CouldNotSendNotification
     * @throws \ReflectionException
     */
    public function testSendFailed(): void
    {
        $this->expectException(CouldNotSendNotification::class);

        $response = new Response(300, [], \json_encode(['message_id' => 'test']));
        $this->mock_handler->append($response);

        $this->firebase_channel->send($this->getNotifiableMock(), $this->getNotificationMock());
    }

    /**
     * @covers ::send()
     *
     * @throws CouldNotSendNotification
     * @throws \InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testNoSend(): void
    {
        $history_container = [];

        $stack = HandlerStack::create();
        // Add the history middleware to the handler stack.
        $stack->push(Middleware::history($history_container));

        $fcm_channel = new FcmChannel(
            new FcmClient(
                new Client(['handler' => $stack]),
                ''
            )
        );

        $fcm_channel->send(
            $this
                ->getMockBuilder(Notifiable::class)
                ->setMethods(['routeNotificationForFcm'])
                ->getMockForTrait(),
            $this
                ->getMockBuilder(Notification::class)
                ->setMethods(['toFcm'])
                ->getMock()
        );

        self::assertCount(0, $history_container);
    }

    /**
     * @return Notification
     * @throws \ReflectionException
     */
    protected function getNotificationMock(): Notification
    {
        $notification = $this
            ->getMockBuilder(Notification::class)
            ->setMethods(['toFcm'])
            ->getMock();

        $notification
            ->expects($this->once())
            ->method('toFcm')
            ->willReturn(
                new FcmMessage
            );

        return $notification;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     * @throws \ReflectionException
     */
    protected function getNotifiableMock()
    {
        $notifiable = $this
            ->getMockBuilder(Notifiable::class)
            ->setMethods(['routeNotificationForFcm'])
            ->getMockForTrait();

        $notifiable
            ->expects($this->once())
            ->method('routeNotificationForFcm')
            ->willReturn(
                new FcmTopicReceiver('test')
            );

        return $notifiable;
    }
}
