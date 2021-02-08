<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) 2015-2021 Splash Sync  <www.splashsync.com>
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
 * Base Api Load Action
 */
abstract class AbstractLoadAction extends AbstractAction
{
    /**
     * Execute Objects Load Action.
     *
     * @param string $objectId
     *
     * @return ApiResponse
     */
    public function execute(string $objectId): ApiResponse
    {
        //====================================================================//
        // Build Target Uri
        $uri = $this->visitor->getItemUri($objectId);
        if (!$uri) {
            return new ApiResponse($this->visitor);
        }
        //====================================================================//
        // Execute Get Request
        $rawResponse = $this->visitor->getConnexion()->get($uri);
        if (null === $rawResponse) {
            return new ApiResponse($this->visitor);
        }

//        \Splash\Client\Splash::log()->www("Get", $this->extractData($rawResponse));

        return new ApiResponse($this->visitor, true, $this->extractData($rawResponse));
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
        return $this->visitor->getHydrator()->hydrate($rawResponse, $this->visitor->getModel());
    }
}
