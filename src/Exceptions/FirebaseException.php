<?php

declare(strict_types=1);

namespace Feugene\FirebaseNotificationsChannel\Exceptions;

use Illuminate\Contracts\Support\Arrayable;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class FirebaseException
 * @package Feugene\FirebaseNotificationsChannel\Exceptions
 */
class FirebaseException extends \Exception implements Arrayable
{
    /** @var RequestInterface */
    private $request;

    /** @var ResponseInterface */
    private $response;

    /**
     * FirebaseException constructor.
     * @param $message
     * @param RequestInterface $request
     * @param ResponseInterface|null $response
     * @param \Exception|null $previous
     */
    public function __construct(
        $message,
        RequestInterface $request,
        ResponseInterface $response = null,
        \Exception $previous = null)
    {

        $code = $response ? $response->getStatusCode() : 0;
        parent::__construct($message, $code, $previous);
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    /**
     * @return bool
     */
    public function hasResponse(): bool
    {
        return $this->response !== null;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $response = !$this->hasResponse()
            ? null
            : [
                'headers' => $this->getResponse()->getHeaders(),
                'statusCode' => $this->getResponse()->getStatusCode(),
                'body' => (string)$this->getResponse()->getBody(),
                'reason' => $this->getResponse()->getReasonPhrase(),
            ];


        return [
            'error' => [
                'message' => $this->getMessage(),
                'code' => $this->getCode(),
            ],
            'request' => [
                'body' => (string)$this->getRequest()->getBody(),
                'method' => $this->getRequest()->getMethod(),
                'uri' => $this->getRequest()->getUri(),
                'target' => $this->getRequest()->getRequestTarget(),
                'headers' => $this->getRequest()->getHeaders(),
            ],
            'response' => $response,

        ];
    }
}
