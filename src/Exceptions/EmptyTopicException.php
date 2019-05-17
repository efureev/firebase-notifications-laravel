<?php

declare(strict_types=1);

namespace Feugene\FirebaseNotificationsChannel\Exceptions;

/**
 * Class EmptyTopicException
 * @package Feugene\FirebaseNotificationsChannel\Exceptions
 */
class EmptyTopicException extends \LogicException
{
    public static function instance(): self
    {
        return new self('Topic can\'t be empty');
    }
}
