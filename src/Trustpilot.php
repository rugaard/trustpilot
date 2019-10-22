<?php
declare(strict_types=1);

namespace Rugaard\Trustpilot;

use League\OAuth2\Client\Token\AccessTokenInterface;
use Rugaard\OAuth2\Client\Trustpilot\Provider\Trustpilot as TrustpilotProvider;
use Rugaard\Trustpilot\Services\BusinessUnits;

/**
 * Class Trustpilot
 *
 * @package Rugaard\Trustpilot
 */
class Trustpilot
{
    /**
     * Trustpilot API key.
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Trustpilot API secret.
     *
     * @var string
     */
    protected $apiSecret;

    /**
     * Trustpilot OAuth provider.
     *
     * @var \Rugaard\OAuth2\Client\Trustpilot\Provider\Trustpilot
     */
    protected $provider;

    /**
     * Trustpilot OAuth Access Token.
     *
     * @var \League\OAuth2\Client\Token\AccessTokenInterface|null
     */
    protected $accessToken;

    /**
     * Grant type.
     *
     * @var string
     */
    protected $grantType = 'code';

    /**
     * Username when using password grant.
     *
     * @var string|null
     */
    protected $username;

    /**
     * Password when using password grant.
     *
     * @var string|null
     */
    protected $password;

    /**
     * Default business unit ID.
     *
     * @var string|null
     */
    protected $businessUnitId;

    /**
     * Trustpilot constructor.
     *
     * @param  string      $apiKey
     * @param  string      $apiSecret
     * @param  string|null $redirectUrl
     * @param  string|null $username
     * @param  string|null $password
     */
    public function __construct(string $apiKey, string $apiSecret, ?string $redirectUrl = null, ?string $username = null, ?string $password = null)
    {
        // Set API key and secret.
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;

        // Trustpilot OAuth provider.
        $this->provider = new TrustpilotProvider([
            'clientId' => $apiKey,
            'clientSecret' => $apiSecret,
            'redirectUri' => $redirectUrl ?: request()->url()
        ]);

        // If a username and password has been provided,
        // we'll switch the provider to use the "password" grant type.
        if (!empty($username) && !empty($password)) {
            $this->usePasswordGrantType($username, $password);
        }
    }

    /**
     * Use "password" grant type.
     *
     * @param  string $username
     * @param  string $password
     * @return \Rugaard\Trustpilot\Trustpilot
     */
    public function usePasswordGrantType(string $username, string $password) : self
    {
        // Set grant type to password.
        $this->grantType = 'password';

        // Set username and password.
        $this->username = $username;
        $this->password = $password;

        return $this;
    }

    /**
     * Get "Business Units" service.
     *
     * @return \Rugaard\Trustpilot\Services\BusinessUnits
     */
    public function businessUnits() : BusinessUnits
    {
        return new BusinessUnits($this->getApiKey(), $this->getAccessToken(), $this->getBusinessUnitId());
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
     * Get access token.
     *
     * @return \League\OAuth2\Client\Token\AccessTokenInterface|null
     */
    public function getAccessToken() :? AccessTokenInterface
    {
        return $this->accessToken;
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
}
