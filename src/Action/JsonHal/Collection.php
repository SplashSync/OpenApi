<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) 2015-2020 Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\OpenApi\Action\JsonHal;

use Splash\OpenApi\Models\OpenApiAwareInterface;

/**
 * Read Objects List form Remote Server
 */
class Collection extends AbstractJsonHalAction
{
    public static $embeddedIndex = "_embedded";

    public static $totalIndexes = array(
        "total", "totalItems", "total_items"
    );

    /**
     * Execute Objects List Action.
     *
     * @param OpenApiAwareInterface $apiAware
     * @param null|string           $path
     */
    public function __construct(OpenApiAwareInterface $apiAware, string $path = null, array $parameters = array())
    {
        $this->api = $apiAware;
        //====================================================================//
        // Init Empty Results
        $this->setResults(array('meta' => array('current' => 0, 'total' => 0)));
        //====================================================================//
        // Execute Get Request
        $rawResponse = $this->api->getConnexion()->get($path, $parameters);
        if (null === $rawResponse) {
            return;
        }
        //====================================================================//
        // Extract Results
        $results = $this->extractData($rawResponse);
        //====================================================================//
        // Compute Meta
        $results["meta"] = array(
            'current' => count($results),
            'total' => $this->extractTotal($rawResponse)
        );
        //====================================================================//
        // Store Results
        $this->setResults($results);
        $this->setSuccessful();
    }

    /**
     * Extract Objects List Data.
     *
     * @param array $rawResponse
     *
     * @return array
     */
    public function extractData(array $rawResponse): array
    {
        //====================================================================//
        // Extract First Item from Raw Json Hal Data.
        $firstItem = $this->getFirstItem($rawResponse);
        if (empty($firstItem)) {
            return array();
        }
        //====================================================================//
        // Hydrate Results
        $results = $this->api->getHydrator()->hydrateMany($firstItem, $this->api->getModel());
        //====================================================================//
        // Extract List Results
        return $this->api->getHydrator()->extractMany($results);
    }

    /**
     * Extract Objects List Totals.
     *
     * @param array $rawResponse
     *
     * @return int
     */
    public function extractTotal(array $rawResponse): int
    {
        //====================================================================//
        // Extract List Total
        foreach (self::$totalIndexes as $index) {
            if (isset($rawResponse[$index])) {
                return (int) $rawResponse[$index];
            }
        }

        return 0;
    }
}
