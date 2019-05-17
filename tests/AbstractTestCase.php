<?php

namespace Feugene\FirebaseNotificationsChannel\Tests;

use AvtoDev\DevTools\Tests\PHPUnit\AbstractLaravelTestCase;
use Feugene\FirebaseNotificationsChannel\FcmClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

abstract class AbstractTestCase extends AbstractLaravelTestCase
{
    /**
     * @var MockHandler
     */
    protected $mock_handler;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->bind(FcmClient::class, function () {
            $this->mock_handler = new MockHandler;

            $handler = HandlerStack::create($this->mock_handler);

            $http_client = new Client(['handler' => $handler]);

            return new FcmClient(
                $http_client,
                'https://fcm.googleapis.com/v1/projects/' . config('fcm.project_id') . '/messages:send'
            );
        });
    }

    public function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
