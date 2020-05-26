<?php

namespace Tnt\Sendcloud\Console;

use dry\db\FetchException;
use Oak\Console\Command\Command;
use Oak\Console\Command\Signature;
use Oak\Contracts\Console\InputInterface;
use Oak\Contracts\Console\OutputInterface;
use Oak\Contracts\Container\ContainerInterface;
use Tnt\Sendcloud\Api;
use Tnt\Sendcloud\Model\ShipmentMethod;

class SyncShipmentMethods extends Command
{
    /**
     * @var Api
     */
    private $apiClient;

    /**
     * SyncShipmentMethods constructor.
     * @param Api $apiClient
     * @param ContainerInterface $app
     */
    public function __construct(Api $apiClient, ContainerInterface $app)
    {
        $this->apiClient = $apiClient;

        parent::__construct($app);
    }

    /**
     * @param Signature $signature
     * @return Signature
     */
    protected function createSignature(Signature $signature): Signature
    {
        return $signature->setName('sync-shipment-methods');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeLine('Start Sendcloud Shipment methods sync');

        $shipmentMethods = $this->apiClient->getShippingMethods();

        foreach ($shipmentMethods as $shipmentMethodData) {

            try {

                $shipmentMethod = ShipmentMethod::load_by('sendcloud_id', $shipmentMethodData['id']);
                $shipmentMethod->updated = time();
                $shipmentMethod->save();

                $output->writeLine('Updated shipment method '.$shipmentMethodData['name'], OutputInterface::TYPE_WARNING);

            } catch (FetchException $e) {

                $shipmentMethod = new ShipmentMethod();
                $shipmentMethod->created = time();
                $shipmentMethod->updated = time();
                $shipmentMethod->sendcloud_id = $shipmentMethodData['id'];
                $shipmentMethod->name = $shipmentMethodData['name'];
                $shipmentMethod->carrier = $shipmentMethodData['carrier'];
                $shipmentMethod->save();

                $output->writeLine('Created shipment method '.$shipmentMethodData['name'], OutputInterface::TYPE_INFO);
            }
        }

        foreach (ShipmentMethod::all() as $shipmentMethod) {

            if (date('Ymd', $shipmentMethod->updated) !== date('Ymd')) {

                $output->writeLine('Deleted shipment method '.$shipmentMethod->name, OutputInterface::TYPE_INFO);

                $shipmentMethod->delete();
            }
        }
    }
}