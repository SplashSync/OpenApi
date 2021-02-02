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
 * Read Objects Data form Remote Server
 */
class Get extends AbstractAction
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
        $this->setResults(null);
        //====================================================================//
        // Execute Get Request
        $rawResponse = $this->api->getConnexion()->get($path);
        if (null === $rawResponse) {
            return;
        }
        //====================================================================//
        // Store Results
        $this->setResults(self::extractData($rawResponse));
        $this->setSuccessful();
    }

    /**
     * Extract Objects List Data.
     *
     * @param array $rawResponse
     *
     * @return object
     */
    public function extractData(array $rawResponse): object
    {
        //====================================================================//
        // Hydrate Results
        return $this->api->getHydrator()->hydrate($rawResponse, $this->api->getModel());
    }
}
