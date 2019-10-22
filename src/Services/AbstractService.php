<?php
declare(strict_types=1);

namespace Rugaard\Trustpilot\Services;

use League\OAuth2\Client\Token\AccessTokenInterface;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException as GuzzleClientException;
use GuzzleHttp\Exception\ServerException as GuzzleServerException;
use GuzzleHttp\Exception\GuzzleException;
use Rugaard\Trustpilot\Exceptions\ClientException;
use Rugaard\Trustpilot\Exceptions\ParsingFailedException;
use Rugaard\Trustpilot\Exceptions\ServerException;
use Rugaard\Trustpilot\Exceptions\RequestException;

/**
 * Class AbstractService
 *
 * @package Rugaard\Trustpilot\Services
 */
abstract class AbstractService
{
    /**
     * Base URL of API services.
     *
     * @var string
     */
    protected $baseUrl = 'https://api.trustpilot.com/v1/';

    /**
     * API Key for public endpoints.
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Token for private endpoints.
     *
     * @var \League\OAuth2\Client\Token\AccessTokenInterface|null
     */
    protected $token;

    /**
     * Guzzle HTTP client.
     *
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * Default business unit ID.
     *
     * @var string|null
     */
    protected $businessUnitId;

    /**
     * AbstractService constructor.
     *
     * @param  string                                           $apiKey
     * @param  \League\OAuth2\Client\Token\AccessTokenInterface $token
     * @param  string|null                                      $businessUnitId
     */
    public function __construct(string $apiKey, ?AccessTokenInterface $token = null, ?string $businessUnitId = null)
    {
        $this->setApiKey($apiKey);

        if (!empty($token)) {
            $this->setToken($token);
        }

        if (!empty($businessUnitId)) {
            $this->setBusinessUnitId($businessUnitId);
        }
    }

    /**
     * Request service with API key.
     *
     * @param  string $method
     * @param  string $url
     * @param  array  $options
     * @return array
     * @throws \Rugaard\Trustpilot\Exceptions\ParsingFailedException
     */
    public function requestWithApiKey(string $method, string $url, array $options = []) : array
    {
        // Add API key as an header.
        $options['headers']['apikey'] = $this->getApiKey();

        // Send the request.
        return $this->request($method, $url, $options);
    }

    /**
     * Request service with access token.
     *
     * @param  string $method
     * @param  string $url
     * @param  array  $options
     * @return array
     * @throws \Rugaard\Trustpilot\Exceptions\ParsingFailedException
     */
    public function requestWithToken(string $method, string $url, array $options = []) : array
    {
        // Add Authorization header with token.
        $options['headers']['Authorization'] = 'Bearer ' . $this->getToken()->getToken();

        // Send the request.
        return $this->request($method, $url, $options);
    }

    /**
     * Send request to service.
     *
     * @param  string $method
     * @param  string $url
     * @param  array  $options
     * @return array
     * @throws \Rugaard\Trustpilot\Exceptions\ParsingFailedException
     */
    protected function request(string $method, string $url, array $options = []) : array
    {
        // If no client instance has been set,
        // we'll setup a default one with gzip enabled.
        if (!$this->hasClient()) {
            $this->defaultClient();
        }

        try {
            // Send request.
            /* @var $response \Psr\Http\Message\ResponseInterface */
            $response = $this->getClient()->request($method, $url, $options);

            // Extract body from response.
            $body = (string) $response->getBody();

            // JSON Decode response.
            $data = json_decode($body, true);

            // Make sure that the decoding procedure didn't fail.
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new ParsingFailedException('Could not JSON decode response. Reason: ' . json_last_error_msg(), 400);
            }

            return $data;
        } catch (GuzzleServerException $e) {
            throw new ServerException($e->getMessage(), $e->getRequest(), $e->getResponse(), $e);
        } catch (GuzzleClientException $e) {
            throw new ClientException($e->getMessage(), $e->getRequest(), $e->getResponse(), $e);
        } catch (GuzzleException $e) {
            throw new RequestException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Set API key.
     *
     * @param  string $apiKey
     * @return self
     */
    public function setApiKey(string $apiKey) : self
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * Get API key.
     *
     * @return string
     */
    public function getApiKey() : string
    {
        return $this->apiKey;
    }

    /**
     * Set access token.
     *
     * @param  \League\OAuth2\Client\Token\AccessTokenInterface $token
     * @return self
     */
    public function setToken(AccessTokenInterface $token) : self
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Get access token.
     *
     * @return \League\OAuth2\Client\Token\AccessTokenInterface|null
     */
    public function getToken() :? AccessTokenInterface
    {
        return $this->token;
    }

    /**
     * Set default business unit ID.
     *
     * @param  string $id
     * @return self
     */
    public function setBusinessUnitId(string $id) : self
    {
        $this->businessUnitId = $id;
        return $this;
    }

    /**
     * Get default business unit ID.
     *
     * @return string|null
     */
    public function getBusinessUnitId() :? string
    {
        return $this->businessUnitId;
    }

    /**
     * Set a default client instance.
     *
     * @return void
     */
    protected function defaultClient() : void
    {
        $this->setClient(new GuzzleClient([
            'base_uri' => $this->baseUrl,
        ], [
            'headers' => [
                'Accept-Encoding' => 'gzip',
            ]
        ]));
    }

    /**
     * Check that we have a client instance.
     *
     * @return bool
     */
    public function hasClient() : bool
    {
        return $this->getClient() !== null;
    }

    /**
     * Set client instance.
     *
     * @param  \GuzzleHttp\ClientInterface $client
     * @return $this
     */
    public function setClient(ClientInterface $client) : self
    {
        $this->client = $client;
        return $this;
    }

    /**
     * Get client instance.
     *
     * @return \GuzzleHttp\ClientInterface|null
     */
    public function getClient() :? ClientInterface
    {
        return $this->client;
    }
}
