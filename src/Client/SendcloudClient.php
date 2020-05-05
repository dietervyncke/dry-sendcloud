<?php

namespace Tnt\Sendcloud\Client;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Tnt\Sendcloud\Exception\SendcloudException;

class SendcloudClient
{
    /**
     * @var
     */
    private $client;

    /**
     * SendcloudClient constructor.
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param $endPoint
     * @param array $params
     * @return array
     * @throws SendcloudException
     */
    public function get(string $endPoint, $params = []): array
    {
        try {
            return $this->parseResponse($this->client->get($endPoint, ['query' => $params]));

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                throw new SendcloudException($e->getResponse());
            }
        }

        throw new SendcloudException('Sendcloud error '.$e->getResponse()->getStatusCode());
    }

    /**
     * @param string $endPoint
     * @param $body
     * @return array
     * @throws SendcloudException
     */
    public function post(string $endPoint, $body = []): array
    {
        try {
            $response = $this->client->post($endPoint, [
                'body' => json_encode($body),
            ]);

            return $this->parseResponse($response);

        } catch (ClientException $e) {
            throw new SendcloudException('Sendcloud error '.$e->getResponse()->getStatusCode());

        } catch (RequestException $e) {

            if ($e->hasResponse()) {
                $this->parseResponse($e->getResponse());
            }
        }

        throw new SendcloudException('Sendcloud error '.$e->getResponse()->getStatusCode());
    }

    public function put(string $endPoint, $body): array
    {
        try {
            $response = $this->client->put($endPoint, [
                'body' => json_encode($body),
            ]);

            return $this->parseResponse($response);

        } catch (ClientException $e) {
            throw new SendcloudException('Sendcloud error '.$e->getResponse()->getStatusCode());

        } catch (RequestException $e) {

            if ($e->hasResponse()) {
                $this->parseResponse($e->getResponse());
            }
        }

        throw new SendcloudException('Sendcloud error '.$e->getResponse()->getStatusCode());
    }

    /**
     * @param $endPoint
     * @return array
     * @throws SendcloudException
     */
    public function delete(string $endPoint): array
    {
        try {
            return $this->parseResponse($this->client->delete($endPoint));

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                throw new SendcloudException($e->getResponse());
            }
        }

        throw new SendcloudException('Sendcloud error '.$e->getResponse()->getStatusCode());
    }

    /**
     * @param ResponseInterface $response
     * @return array
     * @throws SendcloudException
     */
    private function parseResponse(ResponseInterface $response): array
    {
        try {

            $responseBody = $response->getBody()->getContents();
            $resultArray = json_decode($responseBody, true);

            return $resultArray;

        } catch (\RuntimeException $e) {
            throw new SendcloudException('Sendcloud error '.$e->getMessage());
        }
    }
}