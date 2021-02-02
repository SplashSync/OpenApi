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

namespace Splash\OpenApi\Action\Json;

use Splash\OpenApi\Models\Action\AbstractAction;
use Splash\OpenApi\Models\OpenApiAwareInterface;

/**
 * Read Objects List form Remote Server
 */
class Collection extends AbstractAction
{
    /**
     * Execute Objects List Action.
     *
     * @param OpenApiAwareInterface $apiAware
     * @param null|string           $path
     */
    public function __construct(OpenApiAwareInterface $apiAware, string $path = null)
    {
        $this->api = $apiAware;
        //====================================================================//
        // Init Empty Results
        $this->setResults(array('meta' => array('current' => 0, 'total' => 0)));
        //====================================================================//
        // Execute Get Request
        $rawResponse = $this->api->getConnexion()->get($path);
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
            'total' => $this->extractTotal()
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
        // Hydrate Results
        $results = $this->api->getHydrator()->hydrateMany($rawResponse, $this->api->getModel());
        //====================================================================//
        // Extract List Results
        return $this->api->getHydrator()->extractMany($results);
    }

    /**
     * Extract Objects List Totals.
     *
     * @return int
     */
    public function extractTotal(): int
    {
        //====================================================================//
        // Extract List Results
        return 0;
    }
}
