<?php

namespace Tnt\Sendcloud;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Oak\Contracts\Config\RepositoryInterface;
use Oak\Contracts\Container\ContainerInterface;
use Oak\ServiceProvider;
use Tnt\Sendcloud\Client\SendcloudClient;

class SendcloudServiceProvider extends ServiceProvider
{
    public function boot(ContainerInterface $app) {}

    public function register(ContainerInterface $app)
    {
        $config = $app->get(RepositoryInterface::class);

        $app->singleton(ClientInterface::class, function ($app) use ($config) {

            return new Client([
                'base_uri' => $config->get('sendcloud.api_url'),
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'auth' => [
                    $config->get('sendcloud.public_api'),
                    $config->get('sendcloud.secret_api'),
                ],
            ]);
        });

        $app->singleton(SendcloudClient::class, SendcloudClient::class);

        $app->singleton(Api::class, Api::class);
    }
}

