<?php
declare(strict_types=1);

namespace Rugaard\Trustpilot\Services;

use Rugaard\Trustpilot\Exceptions\TrustpilotException;
use Tightenco\Collect\Support\Collection;

/**
 * Class BusinessUnits
 *
 * @package Rugaard\Trustpilot\Services
 */
class BusinessUnits extends AbstractService
{
    /**
     * Get all business units on Trustpilot.
     *
     * @see https://developers.trustpilot.com/business-units-api#get-a-list-of-all-business-units
     *
     * @param  bool  $includeZeroReviews
     * @param  array $options
     * @return \Tightenco\Collect\Support\Collection
     * @throws \Rugaard\Trustpilot\Exceptions\ParsingFailedException
     */
    public function all(bool $includeZeroReviews = false, array $options = []) : Collection
    {
        $response = $this->requestWithApiKey('get', 'business-units' . ($includeZeroReviews ? '/all' : ''), [
            'query' => $options
        ]);

        return Collection::make([
            'data' => Collection::make($response['businessUnits'] ?? []),
            'links' => Collection::make($response['links'] ?? []),
        ]);
    }

    /**
     * Get a list of categories for company.
     *
     * @see https://developers.trustpilot.com/business-units-api#list-categories-for-business-unit
     *
     * @param  string|null $businessUnitId
     * @param  array       $options
     * @return \Tightenco\Collect\Support\Collection
     * @throws \Rugaard\Trustpilot\Exceptions\ParsingFailedException
     */
    public function categories(?string $businessUnitId = null, array $options = []) : Collection
    {
        $response = $this->requestWithApiKey('get', 'business-units/' . ($businessUnitId ?? $this->getBusinessUnitId()) . '/categories', [
            'query' => $options
        ]);

        return Collection::make($response['categories'] ?? []);
    }

    /**
     * Get company's basic profile info.
     *
     * @see https://developers.trustpilot.com/business-units-api#get-public-business-unit
     *
     * @param  string|null $businessUnitId
     * @return \Tightenco\Collect\Support\Collection
     * @throws \Rugaard\Trustpilot\Exceptions\ParsingFailedException
     */
    public function details(?string $businessUnitId = null) : Collection
    {
        $response = $this->requestWithApiKey('get', 'business-units/' . ($businessUnitId ?? $this->getBusinessUnitId()));

        return Collection::make([
            'links' => array_shift($response),
            'data' => $response ?? []
        ])->reverse();
    }

    /**
     * Find business unit by name.
     *
     * @see https://developers.trustpilot.com/business-units-api#find-a-business-unit
     *
     * @param  string $name
     * @return \Tightenco\Collect\Support\Collection
     * @throws \Rugaard\Trustpilot\Exceptions\ParsingFailedException
     */
    public function find(string $name) : Collection
    {
        $response = $this->requestWithApiKey('get', 'business-units/find', [
            'query' => [
                'name' => $name
            ]
        ]);

        return Collection::make($response ?? []);
    }

    /**
     * Get company's guarantee box.
     *
     * @see https://developers.trustpilot.com/business-units-api#get-customer-guarantee-of-business-unit
     *
     * @param  string|null $businessUnitId
     * @return \Tightenco\Collect\Support\Collection
     * @throws \Rugaard\Trustpilot\Exceptions\ParsingFailedException
     */
    public function guaranteeBox(?string $businessUnitId = null) : Collection
    {
        $response = $this->requestWithApiKey('get', 'business-units/' . ($businessUnitId ?? $this->getBusinessUnitId()) . '/customerguarantee');

        return Collection::make([
            'links' => array_shift($response),
            'data' => $response ?? []
        ])->reverse();
    }

    /**
     * Get company images.
     *
     * @see https://developers.trustpilot.com/business-units-api#get-images-of-business-unit
     *
     * @param  string|null $businessUnitId
     * @return \Tightenco\Collect\Support\Collection
     * @throws \Rugaard\Trustpilot\Exceptions\ParsingFailedException
     */
    public function images(?string $businessUnitId = null) : Collection
    {
        $response = $this->requestWithApiKey('get', 'business-units/' . ($businessUnitId ?? $this->getBusinessUnitId()) . '/images');

        return Collection::make([
            'data' => Collection::make($response['profileImage'] ?? []),
            'links' => Collection::make($response['links'] ?? []),
        ]);
    }

    /**
     * Get company logo.
     *
     * @see https://developers.trustpilot.com/business-units-api#get-business-unit-company-logo
     *
     * @param  string|null $businessUnitId
     * @return \Tightenco\Collect\Support\Collection
     * @throws \Rugaard\Trustpilot\Exceptions\ParsingFailedException
     */
    public function logo(?string $businessUnitId = null) : Collection
    {
        $response = $this->requestWithApiKey('get', 'business-units/' . ($businessUnitId ?? $this->getBusinessUnitId()) . '/images/logo');

        return Collection::make([
            'logoUrl' => $response['logoUrl'] ?? null,
            'links' => Collection::make($response['links'] ?? []),
        ]);
    }

    /**
     * Get company profile info.
     *
     * @see https://developers.trustpilot.com/business-units-api#get-profile-info-of-business-unit
     *
     * @param  string|null $businessUnitId
     * @return \Tightenco\Collect\Support\Collection
     * @throws \Rugaard\Trustpilot\Exceptions\ParsingFailedException
     */
    public function profileInfo(?string $businessUnitId = null) : Collection
    {
        $response = $this->requestWithApiKey('get', 'business-units/' . ($businessUnitId ?? $this->getBusinessUnitId()) . '/profileinfo');

        return Collection::make([
            'links' => array_shift($response),
            'data' => $response ?? []
        ])->reverse();
    }

    /**
     * Get company's promotion box.
     *
     * @see https://developers.trustpilot.com/business-units-api#get-profile-promotion-of-business-unit
     *
     * @param  string|null $businessUnitId
     * @return \Tightenco\Collect\Support\Collection
     * @throws \Rugaard\Trustpilot\Exceptions\ParsingFailedException
     */
    public function promotionBox(?string $businessUnitId = null) : Collection
    {
        $response = $this->requestWithApiKey('get', 'business-units/' . ($businessUnitId ?? $this->getBusinessUnitId()) . '/profilepromotion');

        return Collection::make([
            'links' => array_shift($response),
            'data' => $response ?? []
        ])->reverse();
    }

    /**
     * Get all private reviews.
     *
     * @see https://developers.trustpilot.com/business-units-api#business-unit-private-reviews
     *
     * @param  string|null $businessUnitId
     * @param  array       $options
     * @return \Tightenco\Collect\Support\Collection
     * @throws \Rugaard\Trustpilot\Exceptions\TrustpilotException
     */
    public function privateReviews(?string $businessUnitId = null, array $options = []) : Collection
    {
        throw new TrustpilotException('Endpoint not implemented yet.', 500);
    }

    /**
     * Get all reviews.
     *
     * @see https://developers.trustpilot.com/business-units-api#get-a-business-unit's-reviews
     *
     * @param  string|null $businessUnitId
     * @param  array       $options
     * @return \Tightenco\Collect\Support\Collection
     * @throws \Rugaard\Trustpilot\Exceptions\ParsingFailedException
     */
    public function reviews(?string $businessUnitId = null, array $options = []) : Collection
    {
        $response = $this->requestWithApiKey('get', 'business-units/' . ($businessUnitId ?? $this->getBusinessUnitId()) . '/reviews', [
            'query' => $options
        ]);

        return Collection::make([
            'data' => Collection::make($response['reviews'] ?? []),
            'links' => Collection::make($response['links'] ?? []),
        ]);
    }

    /**
     * Search for business units.
     *
     * @see https://developers.trustpilot.com/business-units-api#search-for-business-units
     *
     * @param  string $query
     * @param  array  $options
     * @return \Tightenco\Collect\Support\Collection
     * @throws \Rugaard\Trustpilot\Exceptions\ParsingFailedException
     */
    public function search(string $query, array $options = []) : Collection
    {
        $response = $this->requestWithApiKey('get', 'business-units/search', [
            'query' => array_merge($options, ['query' => $query])
        ]);

        return Collection::make([
            'data' => Collection::make($response['businessUnits'] ?? []),
            'links' => Collection::make($response['links'] ?? []),
        ]);
    }

    /**
     * Get web links related to business unit.
     *
     * @see https://developers.trustpilot.com/business-units-api#get-a-business-unit's-web-links
     *
     * @param  string|null $businessUnitId
     * @param  array       $options
     * @return \Tightenco\Collect\Support\Collection
     * @throws \Rugaard\Trustpilot\Exceptions\ParsingFailedException
     */
    public function webLinks(?string $businessUnitId = null, array $options = []) : Collection
    {
        $response = $this->requestWithApiKey('get', 'business-units/' . ($businessUnitId ?? $this->getBusinessUnitId()) . '/web-links', [
            'query' => $options
        ]);

        return Collection::make([
            'data' => [
                'locale' => $response['locale'] ?? null,
                'profileUrl' => $response['profileUrl'] ?? null,
                'evaluateEmbedUrl' => $response['evaluateEmbedUrl'] ?? null,
                'evaluateUrl' => $response['evaluateUrl'] ?? null,
            ],
            'links' => $response['links'] ?? [],
        ]);
    }
}
