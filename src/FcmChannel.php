<?php

declare(strict_types=1);

namespace Feugene\FirebaseNotificationsChannel;

use Feugene\FirebaseNotificationsChannel\Exceptions\CouldNotSendNotification;
use Feugene\FirebaseNotificationsChannel\Exceptions\FirebaseException;
use Feugene\FirebaseNotificationsChannel\Receivers\FcmNotificationReceiverInterface;
use Illuminate\Notifications\Notification;

/**
 * Channel to send message to Firebase cloud message.
 */
class FcmChannel
{
    /**
     * @var FcmClient
     */
    protected $fcm_client;

    /**
     * FcmChannel constructor.
     *
     * @param FcmClient $fcm_client
     */
    public function __construct(FcmClient $fcm_client)
    {
        $this->fcm_client = $fcm_client;
    }

    /**
     * Send the given notification.
     *
     * @param $notifiable
     * @param Notification $notification
     *
     * @throws CouldNotSendNotification
     * @throws FirebaseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Php\Support\Exceptions\JsonException
     */
    public function send($notifiable, Notification $notification): void
    {
        $route_notification_for_fcm = $notifiable->routeNotificationFor('fcm', $notification);

        if (!($route_notification_for_fcm instanceof FcmNotificationReceiverInterface)) {
            return;
        }

        if (!\method_exists($notification, $method_name = 'toFcm')) {
            throw CouldNotSendNotification::invalidNotification();
        }

        $response = $this->fcm_client->sendMessage(
            $route_notification_for_fcm,
            $notification->{$method_name}($notifiable)
        );

        if ($response->getStatusCode() !== 200) {
            throw new FirebaseException('Something went wrong', $this->fcm_client->lastRequest, $response);
        }
    }
}
