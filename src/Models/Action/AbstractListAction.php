<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\OpenApi\Models\Action;

use Splash\OpenApi\ApiResponse;

/**
 * Base Api List Action
 */
abstract class AbstractListAction extends AbstractAction
{
    /**
     * @var array<string, null|array|string>
     */
    protected $options;

    /**
     * Execute Objects List Action.
     *
     * @param null|string $filter
     * @param null|array  $params
     *
     * @return ApiResponse
     */
    public function execute(string $filter = null, array $params = null): ApiResponse
    {
        //====================================================================//
        // Execute Get Request
        $rawResponse = $this->visitor->getConnexion()->get(
            $this->visitor->getCollectionUri(),
            $this->getQueryParameters($filter, $params)
        );
        if (null === $rawResponse) {
            return $this->getErrorResponse();
        }
        //====================================================================//
        // Extract Results
        $results = $this->extractData($rawResponse);
        //====================================================================//
        // Compute Meta
        $meta = array(
            'current' => count($results),
            'total' => $this->extractTotal($rawResponse, $params)
        );
        if (empty($this->options['raw'])) {
            $results["meta"] = $meta;
        }

        return new ApiResponse($this->visitor, true, $results, $meta);
    }

    /**
     * @return array
     */
    protected function getDefaultOptions(): array
    {
        return  array(
            "filterKey" => null,        // Query Key for Filtering Data
            "pageKey" => "page",        // Query Filter for Page Number
            "offsetKey" => null,        // Or Query key for Results Offset
            "maxKey" => "limit",        // Query Key for Limit Max Number of Results
            "raw" => false,             // Return raw data
        );
    }

    /**
     * Build List request Query Parameters Array
     *
     * @param null|string $filter
     * @param null|array  $params
     *
     * @return null|array
     */
    protected function getQueryParameters(?string $filter, ?array $params) : ?array
    {
        $queryArgs = $this->getQueryFilter($filter);
        if (is_array($params)) {
            $queryArgs = array_merge(
                $queryArgs,
                $this->getQueryPagination($params),
                $this->getQueryExtraArgs($params)
            );
        }

        return !empty($queryArgs) ? $queryArgs : null;
    }

    /**
     * Extract Objects List Data.
     *
     * @param array $rawResponse
     *
     * @return array
     */
    protected function extractData(array $rawResponse): array
    {
        //====================================================================//
        // Hydrate Results
        $results = $this->visitor->getHydrator()->hydrateMany($rawResponse, $this->visitor->getModel());
        //====================================================================//
        // Extract List Results
        return empty($this->options['raw'])
            ? $this->visitor->getHydrator()->extractMany($results)
            : $results;
    }

    /**
     * Extract Objects List Totals.
     *
     * @param array      $rawResponse
     * @param null|array $params
     *
     * @return int
     */
    protected function extractTotal(array $rawResponse, array $params = null): int
    {
        //====================================================================//
        // Simulate List Results Total
        $total = count($rawResponse) + 1;
        if (is_array($params) && isset($params["max"])) {
            $total += (int) (isset($params["offset"]) ? $params["offset"] : $params["max"]);
        }

        return $total;
    }

    /**
     * Build Error Response.
     *
     * @return ApiResponse
     */
    protected function getErrorResponse(): ApiResponse
    {
        return new ApiResponse(
            $this->visitor,
            false,
            array('meta' => array('current' => 0, 'total' => 0))
        );
    }

    /**
     * Build List request Query Filters
     *
     * @param null|string $filter
     *
     * @return array
     */
    private function getQueryFilter(?string $filter) : array
    {
        //====================================================================//
        // Add Filter Args
        if (!empty($filter) && is_string($this->options['filterKey'])) {
            return array($this->options['filterKey'] => $filter);
        }

        return array();
    }

    /**
     * Build List request Query Extra Parameters
     *
     * @param array $params
     *
     * @return array
     */
    private function getQueryExtraArgs(array $params) : array
    {
        //====================================================================//
        // Add Extra Args
        if (isset($params['extraArgs']) && is_array($params['extraArgs'])) {
            return $params['extraArgs'];
        }

        return array();
    }

    /**
     * Build List request Query Pagination
     *
     * @param array $params
     *
     * @return array
     */
    private function getQueryPagination(array $params) : array
    {
        $queryArgs = array();
        //====================================================================//
        // Add Max Args
        if (isset($params["max"]) && is_string($this->options['maxKey'])) {
            $queryArgs[$this->options['maxKey']] = (string) $params["max"];
        }
        //====================================================================//
        // Add Offset Args
        if (isset($params["offset"]) && is_string($this->options['offsetKey'])) {
            $queryArgs[$this->options['offsetKey']] = (string) $params["offset"];
            $this->options['pageKey'] = null;
        }
        //====================================================================//
        // Add Page Args
        if (isset($params["max"], $params["offset"]) && is_string($this->options['pageKey'])) {
            $queryArgs[$this->options['pageKey']] = 1 + (int) ($params["offset"] / $params["max"]);
        }

        return $queryArgs;
    }
}
