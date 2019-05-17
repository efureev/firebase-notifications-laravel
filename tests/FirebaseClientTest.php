<?php

namespace Feugene\FirebaseNotificationsChannel\Tests;

use Feugene\FirebaseNotificationsChannel\Entity\SubscribeTopicError;
use Feugene\FirebaseNotificationsChannel\Exceptions\EmptyTopicException;
use Feugene\FirebaseNotificationsChannel\FirebaseClient;
use GuzzleHttp\Psr7\Response;

/**
 * Class FirebaseClientTest
 *
 * @coversDefaultClass \Feugene\FirebaseNotificationsChannel\FirebaseClient
 */
class FirebaseClientTest extends AbstractTestCase
{
    /**
     * @var FirebaseClient
     */
    protected $firebase_client;

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->firebase_client = $this->app->make('firebase');
    }

    /**
     * @covers ::subscribe()
     *
     * @throws \Feugene\FirebaseNotificationsChannel\Exceptions\FirebaseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Php\Support\Exceptions\JsonException
     */
    public function testSubscribe(): void
    {
        $tokens = [
            'deviceId_1' => 'eKYl5MT4Ra8:APA91bHZXfYoSZT0X0NQFl-ZPiwIENYrJZQWj_B9qeFLAz7Sg3-OvtGOO0ktZEKtlfwWH-6dv5rPEcqy6cCLsmxoajvPPz4ePt1Vxt9oVKDEHas5t0Ry7KPnCwNhv_tGMs4UxJS9LI_V',
            'deviceId_2' => 'eKYl5MT4Ra8:APA91bHZXfYoSZT0X0NQFl-ZPiwIENYrJZQWj_B9qeFLAz7Sg3-OvtGOO0ktZEKtlfwWH-6dv5rPEcqy6cCLsmxoajvPPz4ePt1Vxt9oVKDEHas5t0Ry7KPnCwNhv_tGMs4UxJS4LI_V',
            'deviceId_3' => 'eKYl5MT4Ra8:APA91bHZXfYoSZT0X0NQFl-ZPiwIENYrJZQWj_B9qeFLAz7Sg3-OvtGOO0ktZEKtlfwWH-6dv5rPEcqy6cCLsmxoajvPPz4ePt1Vxt9oVKDEHas5t0Ry7KPnCwNhv_tGMs4UxJS1LI_V',
        ];

        $response = new Response(200, [], \json_encode(['results' => [
            [],
            ['error' => 'NOT_FOUND'],
            ['error' => 'INVALID_ARGUMENT'],
        ]]));

        $this->mock_handler->append($response);

        $resultList = $this->firebase_client->subscribe('test-topic', $tokens);

        $resp = $this->firebase_client->getLastResponse();
        static::assertJson((string)$resp->getBody());
        static::assertIsArray($resultList);
        static::assertCount(3, $resultList);

        static::assertNull($resultList['deviceId_1']);
        static::assertInstanceOf(SubscribeTopicError::class, $resultList['deviceId_2']);
        static::assertInstanceOf(SubscribeTopicError::class, $resultList['deviceId_3']);
        static::assertEquals('NOT_FOUND', $resultList['deviceId_2']->getName());
        static::assertEquals('INVALID_ARGUMENT', $resultList['deviceId_3']->getName());

        $req = $this->firebase_client->getLastRequest();
        static::assertNotNull($req);
    }

    public function testSubscribeTopicIsEmpty(): void
    {
        $tokens = [
            'deviceId_1' => 'eKYl5MT4Ra8:APA91bHZXfYoSZT0X0NQFl-ZPiwIENYrJZQWj_B9qeFLAz7Sg3-OvtGOO0ktZEKtlfwWH-6dv5rPEcqy6cCLsmxoajvPPz4ePt1Vxt9oVKDEHas5t0Ry7KPnCwNhv_tGMs4UxJS9LI_V',
        ];
        $response = new Response(200, [], \json_encode(['results' => [
            [],
        ]]));

        $this->mock_handler->append($response);
        $this->expectException(EmptyTopicException::class);

        $this->firebase_client->subscribe('', $tokens);
        static::assertNotNull($this->firebase_client->getLastRequest());
    }

    public function testSubscribeTokensIsEmpty(): void
    {
        $tokens = [];
        $response = new Response(200, [], \json_encode(['results' => [
            [],
        ]]));

        $this->mock_handler->append($response);

        $resultList = $this->firebase_client->subscribe('test', $tokens);
        $resp = $this->firebase_client->getLastResponse();

        static::assertNull($resp);
        static::assertIsArray($resultList);
        static::assertEmpty($resultList);
        static::assertCount(0, $resultList);
        static::assertNull($this->firebase_client->getLastRequest());
    }

    public function testUnSubscribe(): void
    {
        $tokens = [
            'deviceId_1' => 'eKYl5MT4Ra8:APA91bHZXfYoSZT0X0NQFl-ZPiwIENYrJZQWj_B9qeFLAz7Sg3-OvtGOO0ktZEKtlfwWH-6dv5rPEcqy6cCLsmxoajvPPz4ePt1Vxt9oVKDEHas5t0Ry7KPnCwNhv_tGMs4UxJS9LI_V',
            'deviceId_2' => 'eKYl5MT4Ra8:APA91bHZXfYoSZT0X0NQFl-ZPiwIENYrJZQWj_B9qeFLAz7Sg3-OvtGOO0ktZEKtlfwWH-6dv5rPEcqy6cCLsmxoajvPPz4ePt1Vxt9oVKDEHas5t0Ry7KPnCwNhv_tGMs4UxJS4LI_V',
            'deviceId_3' => 'eKYl5MT4Ra8:APA91bHZXfYoSZT0X0NQFl-ZPiwIENYrJZQWj_B9qeFLAz7Sg3-OvtGOO0ktZEKtlfwWH-6dv5rPEcqy6cCLsmxoajvPPz4ePt1Vxt9oVKDEHas5t0Ry7KPnCwNhv_tGMs4UxJS1LI_V',
        ];

        $response = new Response(200, [], \json_encode(['results' => [
            [],
            ['error' => 'NOT_FOUND'],
            ['error' => 'INVALID_ARGUMENT'],
        ]]));

        $this->mock_handler->append($response);

        $resultList = $this->firebase_client->unsubscribe('test-topic', $tokens);

        $resp = $this->firebase_client->getLastResponse();
        static::assertJson((string)$resp->getBody());
        static::assertIsArray($resultList);
        static::assertCount(3, $resultList);

        static::assertNull($resultList['deviceId_1']);
        static::assertInstanceOf(SubscribeTopicError::class, $resultList['deviceId_2']);
        static::assertInstanceOf(SubscribeTopicError::class, $resultList['deviceId_3']);
        static::assertEquals('NOT_FOUND', $resultList['deviceId_2']->getName());
        static::assertEquals('INVALID_ARGUMENT', $resultList['deviceId_3']->getName());
    }
}
