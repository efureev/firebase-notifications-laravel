<?php

declare(strict_types=1);

namespace Feugene\FirebaseNotificationsChannel\Entity;

/**
 * Class FcmClient
 * @package Feugene\FirebaseNotificationsChannel
 */
class SubscribeTopicError
{
    public const NOT_FOUND = 'NOT_FOUND';
    public const INVALID_ARGUMENT = 'INVALID_ARGUMENT';
    public const INTERNAL = 'INTERNAL';
    public const TOO_MANY_TOPICS = 'TOO_MANY_TOPICS';

    public const ERRORS_MESSAGES = [
        self::NOT_FOUND => 'The registration token has been deleted or the app has been uninstalled.',
        self::INVALID_ARGUMENT => 'The registration token provided is not valid for the Sender ID.',
        self::INTERNAL => 'The backend server failed for unknown reasons. Retry the request.',
        self::TOO_MANY_TOPICS => 'The backend server failed for unknown reasons. Retry the request.',
    ];

    /** @var string */
    protected $message = '';

    /** @var string */
    protected $name;


    public function __construct(string $name)
    {
        $this->name = $name;

        if (defined(__CLASS__ . '::' . $this->name)) {
            $this->message = self::ERRORS_MESSAGES[$this->name] ?? null;
        }
    }

    public function __toString(): string
    {
        return $this->message ?: $this->name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $error
     *
     * @return string|null
     */
    public static function getMessageByError(string $error): ?string
    {
        return self::ERRORS_MESSAGES[$error] ?? null;
    }
}
