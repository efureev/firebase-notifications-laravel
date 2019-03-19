<?php

declare(strict_types=1);

namespace AvtoDev\FirebaseNotificationsChannel\Exceptions;

/**
 * Class CouldNotSendNotification
 * @package AvtoDev\FirebaseNotificationsChannel\Exceptions
 */
class CouldNotSendNotification extends \Exception
{
    /**
     * @return CouldNotSendNotification
     */
    public static function invalidNotification(): self
    {
        return new self('Can\'t convert notification to FCM message');
    }
}
