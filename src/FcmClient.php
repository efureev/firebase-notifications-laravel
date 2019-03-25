<?php

declare(strict_types=1);

namespace AvtoDev\FirebaseNotificationsChannel;

use AvtoDev\FirebaseNotificationsChannel\Exceptions\FirebaseRequestException;
use AvtoDev\FirebaseNotificationsChannel\Receivers\FcmNotificationReceiverInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use Tarampampam\Wrappers\Json;

/**
 * Class FcmClient
 * @package AvtoDev\FirebaseNotificationsChannel
 */
class FcmClient
{
    /**
     * @var Client
     */
    protected $http_client;

    /**
     * @var string
     */
    protected $endpoint;

    /**
     * FcmClient constructor.
     *
     * @param Client $http_client
     * @param string $endpoint
     */
    public function __construct(Client $http_client, string $endpoint)
    {
        $this->http_client = $http_client;
        $this->endpoint = $endpoint;
    }

    /** @var Request */
    public $lastRequest;

    /**
     * Send message to firebase cloud messaging server.
     *
     * @param FcmNotificationReceiverInterface $receiver
     * @param FcmMessage $message
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Tarampampam\Wrappers\Exceptions\JsonEncodeDecodeException
     */
    public function sendMessage(FcmNotificationReceiverInterface $receiver, FcmMessage $message): ResponseInterface
    {
        $message_payload = static::filterPayload(\array_merge($receiver->getTarget(), $message->toArray()));
        $jsonMesg = Json::encode([
            'message' => $message_payload,
        ]);

        $this->lastRequest = new Request('POST', $this->endpoint, [
            'Content-Type' => 'application/json'
        ], $jsonMesg);

        try {
            return $this->http_client->send($this->lastRequest);
        } catch (RequestException $e) {
            throw new FirebaseRequestException($e->getMessage(), $e->getRequest(), $e->getResponse(), $e->getPrevious(), $e->getHandlerContext());
        }
    }
    /*
    public function so(): void
    {
        [
            'private_key_id' => env('FCM_CREDENTIALS_private_key_id', 'da80b3bbceaa554442ad67e6be361a66'),
            'private_key' => env('FCM_CREDENTIALS_private_key', '-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----\n'),
            'client_email' => env('FCM_CREDENTIALS_client_email', 'firebase-adminsdk-mwax6@test.iam.gserviceaccount.com'),
            'client_id' => env('FCM_CREDENTIALS_client_id', '22021520333507180281'),
            'auth_uri' => env('FCM_CREDENTIALS_auth_uri', 'https://accounts.google.com/o/oauth2/auth'),
            'token_uri' => env('FCM_CREDENTIALS_token_uri', 'https://oauth2.googleapis.com/token'),
            'auth_provider_x509_cert_url' => env('FCM_CREDENTIALS_auth_provider_x509_cert_url', 'https://www.googleapis.com/oauth2/v1/certs'),
            'client_x509_cert_url' => env('FCM_CREDENTIALS_client_x509_cert_url', 'https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-mwax6%40test.iam.gserviceaccount.com'),
        ];
    }
    */

    /**
     * Unset all empty data from payload.
     *
     * @param array $payload
     *
     * @return array
     */
    protected static function filterPayload(array $payload): array
    {
        foreach ($payload as $key => $value) {
            if ($value === null || $value === '') {
                unset($payload[$key]);
            }

            if (\is_array($value)) {
                $value = static::filterPayload($value);
                $payload[$key] = $value;
            }

            if ($value === []) {
                unset($payload[$key]);
            }
        }

        return $payload;
    }
}
