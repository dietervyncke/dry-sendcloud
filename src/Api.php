<?php

namespace Tnt\Sendcloud;

use Tnt\Sendcloud\Client\SendcloudClient;

class Api
{
    /**
     * @var SendcloudClient
     */
    private $client;

    /**
     * Api constructor.
     * @param SendcloudClient $client
     */
    public function __construct(SendcloudClient $client)
    {
        $this->client = $client;
    }

    /**
     * @return mixed
     * @throws Exception\SendcloudException
     */
    public function getParcels()
    {
        $response = $this->client->get('parcels');
        return $response['parcels'];
    }

    /**
     * @param int $id
     * @return array
     * @throws Exception\SendcloudException
     */
    public function getParcel(int $id)
    {
        $response = $this->client->get('parcels/'.$id);
        return $response['parcel'];
    }

    /**
     * @param $parcel
     * @return mixed
     * @throws Exception\SendcloudException
     */
    public function createParcel($parcel)
    {
        $response = $this->client->post('parcels', $parcel);
        return $response['parcel'];
    }

    /**
     * @param int $id
     * @return mixed
     * @throws Exception\SendcloudException
     */
    public function cancelParcel(int $id)
    {
        return $this->client->post('parcels/'.$id.'/cancel');
    }

    /**
     * @return array
     * @throws Exception\SendcloudException
     */
    public function getShippingMethods()
    {
        $response = $this->client->get('shipping_methods');
        return $response['shipping_methods'];
    }
}