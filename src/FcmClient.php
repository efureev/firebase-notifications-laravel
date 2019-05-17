<?php

declare(strict_types=1);

namespace Feugene\FirebaseNotificationsChannel;

use Feugene\FirebaseNotificationsChannel\Exceptions\FirebaseRequestException;
use Feugene\FirebaseNotificationsChannel\Receivers\FcmNotificationReceiverInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Php\Support\Helpers\Json;
use Psr\Http\Message\ResponseInterface;

/**
 * Class FcmClient
 * @package Feugene\FirebaseNotificationsChannel
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
     *
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Php\Support\Exceptions\JsonException
     */
    public function sendMessage(FcmNotificationReceiverInterface $receiver, FcmMessage $message): ResponseInterface
    {
        $message_payload = static::filterPayload(\array_merge($receiver->getTarget(), $message->toArray()));
        $jsonMsg = Json::encode([
            'message' => $message_payload,
        ]);

        $this->lastRequest = new Request('POST', $this->endpoint, [
            'Content-Type' => 'application/json',
        ], $jsonMsg);

        try {
            return $this->http_client->send($this->lastRequest);
        } catch (RequestException $e) {
            throw new FirebaseRequestException($e->getMessage(), $e->getRequest(), $e->getResponse(), $e->getPrevious(), $e->getHandlerContext());
        }
    }

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
