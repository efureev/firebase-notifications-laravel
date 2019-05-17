<?php

declare(strict_types=1);

namespace Feugene\FirebaseNotificationsChannel;

use Feugene\FirebaseNotificationsChannel\Entity\SubscribeTopicError;
use Feugene\FirebaseNotificationsChannel\Exceptions\EmptyTopicException;
use Feugene\FirebaseNotificationsChannel\Exceptions\FirebaseException;
use Feugene\FirebaseNotificationsChannel\Exceptions\FirebaseRequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Php\Support\Helpers\Json;
use Psr\Http\Message\ResponseInterface;

/**
 * Class FirebaseClient
 */
class FirebaseClient
{
    /**
     * @var Client
     */
    protected $http_client;

    /**
     * @param Client $http_client
     */
    public function __construct(Client $http_client)
    {
        $this->http_client = $http_client;
    }

    /** @var Request */
    protected $lastRequest;

    /** @var ResponseInterface */
    protected $lastResponse;


    /**
     * @param string $type
     * @param string $topic
     * @param array $tokens
     *
     * @return array
     * @throws FirebaseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Php\Support\Exceptions\JsonException
     */
    protected function baseSubscribe(string $type, string $topic, array $tokens): array
    {
        if (!$topic) {
            throw EmptyTopicException::instance();
        }

        if (!$tokens) {
            return [];
        }

        $jsonMsg = Json::encode([
            'to' => "/topics/$topic",
            'registration_tokens' => array_values($tokens),
        ]);

        $this->lastRequest = new Request('POST', "https://iid.googleapis.com/iid/v1:$type", [], $jsonMsg);

        try {
            $this->lastResponse = $this->http_client->send($this->lastRequest);

            $responseTokens = Json::decode((string)$this->lastResponse->getBody());

            if (!isset($responseTokens['results']) || !is_array($responseTokens['results'])) {
                throw new FirebaseException('Something went wrong...', $this->lastRequest, $this->lastResponse);
            }

            $resultTokens = [];
            $tokensKeys = array_keys($tokens);
            foreach ($responseTokens['results'] as $i => $t) {
                $resultTokens[$tokensKeys[$i]] = $t && !empty($t['error'])
                    ? new SubscribeTopicError($t['error'])
                    : null;
            }

            return $resultTokens;
        } catch (RequestException $e) {
            throw new FirebaseRequestException($e->getMessage(), $e->getRequest(), $e->getResponse(), $e->getPrevious(), $e->getHandlerContext());
        }
    }

    /**
     * Subscribe device at the topic
     *
     * @param string $topic
     * @param array $tokens
     *
     * @return array
     * @throws FirebaseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Php\Support\Exceptions\JsonException
     */
    public function subscribe(string $topic, array $tokens): array
    {
        return $this->baseSubscribe('batchAdd', $topic, $tokens);
    }

    /**
     * UnSubscribe device at the topic
     *
     * @param string $topic
     * @param array $tokens
     *
     * @return array
     * @throws FirebaseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Php\Support\Exceptions\JsonException
     */
    public function unsubscribe(string $topic, array $tokens): array
    {
        return $this->baseSubscribe('batchRemove', $topic, $tokens);
    }

    /**
     * @return Request|null
     */
    public function getLastRequest(): ?Request
    {
        return $this->lastRequest;
    }

    /**
     * @return ResponseInterface|null
     */
    public function getLastResponse(): ?ResponseInterface
    {
        return $this->lastResponse;
    }


}
