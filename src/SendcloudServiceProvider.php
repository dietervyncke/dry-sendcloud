<?php

namespace Tnt\Sendcloud;

use dry\route\Router;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Oak\Contracts\Config\RepositoryInterface;
use Oak\Contracts\Console\KernelInterface;
use Oak\Contracts\Container\ContainerInterface;
use Oak\Migration\MigrationManager;
use Oak\Migration\Migrator;
use Oak\ServiceProvider;
use Tnt\Sendcloud\Client\SendcloudClient;
use Tnt\Sendcloud\Console\Sendcloud;
use Tnt\Sendcloud\Controller\WebhookController;
use Tnt\Sendcloud\Revisions\CreateLabelTable;
use Tnt\Sendcloud\Revisions\CreateParcelTable;
use Tnt\Sendcloud\Revisions\CreateShipmentMethodTable;

class SendcloudServiceProvider extends ServiceProvider
{
    public function boot(ContainerInterface $app)
    {
        if ($app->isRunningInConsole()) {

            $migrator = $app->getWith(Migrator::class, [
                'name' => 'sendcloud',
            ]);

            $migrator->setRevisions([
                CreateLabelTable::class,
                CreateShipmentMethodTable::class,
                CreateParcelTable::class,
            ]);

            $app->get(MigrationManager::class)
                ->addMigrator($migrator);

            $app->get(KernelInterface::class)
                ->registerCommand(Sendcloud::class);
        }

        Router::register('nl', null, [
            'sendcloud-webhook/' => function($request) use ($app) {
                call_user_func_array(
                    [WebhookController::class, 'process',],
                    [$request, $app->get(RepositoryInterface::class)->get('sendcloud.secret_api')]
                );
            }
        ]);
    }

    public function register(ContainerInterface $app)
    {
        $config = $app->get(RepositoryInterface::class);

        $app->singleton(ClientInterface::class, function () use ($config) {

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

