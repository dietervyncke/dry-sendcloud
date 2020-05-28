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
                $this->parseResponse($e->getResponse());
            }
        }

        throw new SendcloudException('Sendcloud error (method: GET)', $e->getResponse()->getStatusCode());
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

        } catch (RequestException $e) {

            if ($e->hasResponse()) {
                $this->parseResponse($e->getResponse());
            }

            throw new SendcloudException('Sendcloud error (method: POST) '.$e->getResponse()->getBody()->getContents(), $e->getResponse()->getStatusCode());
        }
    }

    public function put(string $endPoint, $body): array
    {
        try {
            $response = $this->client->put($endPoint, [
                'body' => json_encode($body),
            ]);

            return $this->parseResponse($response);

        } catch (ClientException $e) {
            throw new SendcloudException('Sendcloud error (ClientException)'. $e->getResponse()->getBody()->getContents(), $e->getResponse()->getStatusCode());

        } catch (RequestException $e) {

            if ($e->hasResponse()) {
                $this->parseResponse($e->getResponse());
            }
        }

        throw new SendcloudException('Sendcloud error (method: PUT)', $e->getResponse()->getStatusCode());
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
                $this->parseResponse($e->getResponse());
            }

            throw new SendcloudException('Sendcloud error (method: DELETE)', $e->getResponse()->getStatusCode());
        }
    }

    /**
     * @param $url
     * @return mixed
     * @throws SendcloudException
     */
    public function download($url)
    {
        try {
            $result = $this->client->get($url);
            return $result->getBody()->getContents();

        } catch (RequestException $e) {
            throw new SendcloudException('Sendcloud error: ' . $e->getMessage(), $e->getResponse()->getStatusCode());
        }
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

            if (! is_array($resultArray)) {
                throw new SendcloudException(sprintf('SendCloud error %s: %s', $response->getStatusCode(), $responseBody), $response->getStatusCode());
            }

            if (array_key_exists('error', $resultArray)
                && is_array($resultArray['error'])
                && array_key_exists('message', $resultArray['error'])
            ) {
                throw new SendcloudException('SendCloud error: ' . $resultArray['error']['message'], $resultArray['error']['code']);
            }

            return $resultArray;

        } catch (\RuntimeException $e) {
            throw new SendcloudException('Sendcloud error '.$e->getMessage());
        }
    }
}