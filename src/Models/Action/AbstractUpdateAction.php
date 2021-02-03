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

namespace Splash\OpenApi\Models\Action;

use Splash\OpenApi\ApiResponse;

/**
 * Base Api Update Action
 */
abstract class AbstractUpdateAction extends AbstractAction
{
    /**
     * Execute Objects Update Action.
     *
     * @param string      $objectId
     * @param null|object $object
     *
     * @return ApiResponse
     */
    public function execute(string $objectId, ?object $object): ApiResponse
    {
        //====================================================================//
        // Build Target Uri
        $uri = $this->visitor->getItemUri($objectId);
        if (!$uri) {
            return new ApiResponse($this->visitor);
        }
        \Splash\Client\Splash::log()->www("Set", $this->extractData($object));
        //====================================================================//
        // Execute Get Request
        $rawResponse = $this->visitor->getConnexion()->patch(
            $uri,
            $this->extractData($object)
        );
        if (null === $rawResponse) {
            return new ApiResponse($this->visitor);
        }

        return new ApiResponse($this->visitor, true, $rawResponse);
    }

    /**
     * Extract Objects Write Data.
     *
     * @param null|object $object
     *
     * @return null|array
     */
    public function extractData(?object $object): ?array
    {
        //====================================================================//
        // Hydrate Results
        return is_null($object) ? null : $this->visitor->getHydrator()->extract($object);
    }
}
