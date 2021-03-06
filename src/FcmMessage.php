<?php

declare(strict_types=1);

namespace Feugene\FirebaseNotificationsChannel;

use Feugene\FirebaseNotificationsChannel\PlatformSettings\AndroidFcmPlatformSettings;
use Feugene\FirebaseNotificationsChannel\PlatformSettings\AppleFcmPlatformSettings;
use Feugene\FirebaseNotificationsChannel\PlatformSettings\WebpushFcmPlatformSettings;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

/**
 * Class FcmMessage
 * @package Feugene\FirebaseNotificationsChannel
 */
class FcmMessage implements Arrayable
{
    /**
     * @var array
     */
    protected $data;

    /**
     * Android specific options for messages sent through FCM connection server.
     *
     * @var AndroidFcmPlatformSettings
     */
    protected $android;

    /**
     * Webpush protocol options.
     *
     * @var WebpushFcmPlatformSettings
     */
    protected $webpush;

    /**
     * Apple Push Notification Service specific options.
     *
     * @var AppleFcmPlatformSettings
     */
    protected $apns;

    /**
     * The notification's title.
     *
     * @var string
     */
    protected $title;

    /**
     * The notification's body text.
     *
     * @var string
     */
    protected $body;

    /**
     * FcmMessage constructor.
     */
    public function __construct()
    {
        $this->android = new AndroidFcmPlatformSettings;
        $this->webpush = new WebpushFcmPlatformSettings;
        $this->apns = new AppleFcmPlatformSettings;
    }

    /**
     * @return WebpushFcmPlatformSettings
     */
    public function getWebpush(): WebpushFcmPlatformSettings
    {
        return $this->webpush;
    }

    /**
     * @param WebpushFcmPlatformSettings $webpush
     *
     * @return self
     */
    public function setWebpush(WebpushFcmPlatformSettings $webpush): self
    {
        $this->webpush = $webpush;

        return $this;
    }

    /**
     * @return AppleFcmPlatformSettings
     */
    public function getApns(): AppleFcmPlatformSettings
    {
        return $this->apns;
    }

    /**
     * @param AppleFcmPlatformSettings $apns
     *
     * @return self
     */
    public function setApns(AppleFcmPlatformSettings $apns): self
    {
        $this->apns = $apns;

        return $this;
    }

    /**
     * Arbitrary key/value payload.
     *
     * An object containing a list of key-value pairs
     *
     * @param array $data
     *
     * @return self
     * @example ['name'=>'wrench','mass'=>'1.3kg','count'=>3]
     *
     */
    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * The notification's title.
     *
     * @param string $title
     *
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * The notification's body text.
     *
     * @param string $body
     *
     * @return self
     */
    public function setBody($body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return AndroidFcmPlatformSettings
     */
    public function getAndroid(): AndroidFcmPlatformSettings
    {
        return $this->android;
    }

    /**
     * @param AndroidFcmPlatformSettings $android
     *
     * @return self
     */
    public function setAndroid(AndroidFcmPlatformSettings $android): self
    {
        $this->android = $android;

        return $this;
    }

    /**
     * Build an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $notification = $this->android->getHideNotification()
            ? null
            : [
                'title' => $this->title,
                'body' => $this->body,
            ];
        return [
            'data' => static::toMap($this->data ?? []),
            'notification' => $notification,
            'android' => $this->android->toArray(),
            'webpush' => $this->webpush->toArray(),
            'apns' => $this->apns->toArray(),
        ];
    }

    /**
     * Convert extra-params to string-map
     *
     * @see https://firebase.google.com/docs/reference/fcm/rest/v1/projects.messages?authuser=0#androidconfig
     *
     * @param array $array
     *
     * @return array
     */
    protected static function toMap(array $array): array
    {
        return array_map(static function ($i) {
            switch (gettype($i)) {
                case 'boolean':
                    return (string)(int)$i;
            }
            return (string)$i;
        }, Arr::dot($array));
    }
}
