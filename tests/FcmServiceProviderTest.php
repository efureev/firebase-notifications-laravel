<?php

declare(strict_types=1);

namespace Feugene\FirebaseNotificationsChannel\Tests;

use Feugene\FirebaseNotificationsChannel\FcmChannel;
use Feugene\FirebaseNotificationsChannel\FcmClient;
use Feugene\FirebaseNotificationsChannel\FcmServiceProvider;
use Feugene\FirebaseNotificationsChannel\FirebaseClient;
use GuzzleHttp\Client;
use Php\Support\Exceptions\JsonException;
use Php\Support\Helpers\Json;

/**
 * @coversDefaultClass \Feugene\FirebaseNotificationsChannel\FcmServiceProvider
 */
class FcmServiceProviderTest extends AbstractTestCase
{
    /**
     * @var FcmServiceProvider
     */
    protected $service_provider;

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->service_provider = new FcmServiceProvider($this->app);

        $stub_config = require __DIR__ . '/config/services.php';

        $this->app->make('config')->set('services', $stub_config);
    }

    /**
     * @covers ::getCredentials()
     *
     * @throws JsonException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \ReflectionException
     */
    public function testGetCredentialsFromFile(): void
    {
        $this->setUpConfigFile();
        self::assertEquals(
            Json::decode(\file_get_contents(__DIR__ . '/Stubs/firebase.json')),
            self::callMethod($this->service_provider, 'getCredentials', [$this->app])
        );
    }

    /**
     * @covers ::getCredentials()
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \ReflectionException
     */
    public function testGetCredentialsFileNotFound(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('file does not exist');

        $this->setUpConfigFile('');
        self::callMethod($this->service_provider, 'getCredentials', [$this->app]);
    }

    /**
     * @covers ::getCredentials()
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \ReflectionException
     */
    public function testGetCredentialsFromFileInvalidJson(): void
    {
        $this->expectException(JsonException::class);
        $this->expectExceptionCode(JSON_ERROR_SYNTAX);
        $this->setUpConfigFile(__DIR__ . '/Stubs/invalid_firebase.json');
        self::callMethod($this->service_provider, 'getCredentials', [$this->app]);
    }

    /**
     * @covers ::getCredentials()
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \ReflectionException
     */
    public function testGetCredentialsFromConfig(): void
    {
        /** @var \Illuminate\Config\Repository $config */
        $config = $this->app->make('config');

        $config->set('services.fcm.driver', 'config');

        self::assertEquals(
            $config->get('services.fcm.drivers.config.credentials', []),
            self::callMethod($this->service_provider, 'getCredentials', [$this->app])
        );
    }

    /**
     * @covers ::getCredentials()
     *
     * @throws \ReflectionException
     */
    public function testGetCredentialsDriverNotSet(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Fcm driver not set');

        self::callMethod($this->service_provider, 'getCredentials', [$this->app]);
    }

    /**
     * @covers ::boot()
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \ReflectionException
     */
    public function testBoot(): void
    {
        $this->setUpConfigFile();
        $this->service_provider->boot();

        $fcm_channel = $this->app->make(FcmChannel::class);

        /** @var FcmClient $fcm_client */
        $fcm_client = self::getProperty($fcm_channel, 'fcm_client');

        static::assertStringContainsString(
            'https://fcm.googleapis.com/v1/projects/test/messages:send',
            self::getProperty($fcm_client, 'endpoint')
        );

        /** @var FirebaseClient $firebase */
        $firebase = $this->app->make('firebase');
        $firebase_client = self::getProperty($firebase, 'http_client');
        static::assertInstanceOf(Client::class, $firebase_client);
    }

    /**
     * @param null $path
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function setUpConfigFile($path = null): void
    {
        if ($path === null) {
            $path = __DIR__ . '/Stubs/firebase.json';
        }
        /** @var \Illuminate\Config\Repository $config */
        $config = $this->app->make('config');

        $config->set('services.fcm.driver', 'file');
        $config->set('services.fcm.drivers.file.path', $path);
    }
}
