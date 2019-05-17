<?php

declare(strict_types=1);

namespace Feugene\FirebaseNotificationsChannel\Receivers;

/**
 * Interface FcmNotificationReceiverInterface
 * @package Feugene\FirebaseNotificationsChannel\Receivers
 */
interface FcmNotificationReceiverInterface
{
    /**
     * Get target (token or topic).
     *
     * @return array
     */
    public function getTarget(): array;
}
