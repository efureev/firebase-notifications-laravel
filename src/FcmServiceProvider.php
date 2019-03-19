<?php

declare(strict_types=1);

namespace AvtoDev\FirebaseNotificationsChannel;

use GuzzleHttp\Client;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Tarampampam\Wrappers\Exceptions\JsonEncodeDecodeException;
use Tarampampam\Wrappers\Json;

/**
 * Class FcmServiceProvider
 * @package AvtoDev\FirebaseNotificationsChannel
 */
class FcmServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->app->when(FcmChannel::class)
            ->needs(FcmClient::class)
            ->give(function (Application $app) {
                $credentials = $this->getCredentials($app);

                //Build google client
                $google_client = new \Google_Client;
                $google_client->setAuthConfig($credentials);
                $google_client->addScope('https://www.googleapis.com/auth/firebase.messaging');

                /** @var Client $http_client Guzzle http-client with google-auth middleware */
                $http_client = $google_client->authorize();

                return new FcmClient(
                    $http_client,
                    'https://fcm.googleapis.com/v1/projects/' . $credentials['project_id'] . '/messages:send'
                );
            });
    }

    /**
     * Get Fcm credentials.
     *
     * @param Application $app
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws JsonEncodeDecodeException
     *
     * @return array
     */
    protected function getCredentials(Application $app): array
    {
        /** @var \Illuminate\Config\Repository $config */
        $config = $app->make('config');
        $config_driver = $config->get('services.fcm.driver');

        if ($config_driver === 'file') {
            $credentials_path = $config->get('services.fcm.drivers.file.path', '');

            if (!\file_exists($credentials_path)) {
                throw new \InvalidArgumentException('file does not exist');
            }

            $credentials = Json::decode((string)\file_get_contents($credentials_path));
        } elseif ($config_driver === 'config') {
            $credentials = $config->get('services.fcm.drivers.config.credentials', []);
        } else {
            throw new \InvalidArgumentException('Fcm driver not set');
        }

        return $credentials;
    }
}
