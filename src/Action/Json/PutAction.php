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

namespace Splash\OpenApi\Action\Json;

use Splash\OpenApi\ApiResponse;
use Splash\OpenApi\Models\Action\AbstractUpdateAction;

/**
 * Update Objects Data form Remote Server with PUT Method
 */
class PutAction extends AbstractUpdateAction
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
        //====================================================================//
        // Execute Get Request
        $rawResponse = $this->visitor->getConnexion()->put(
            $uri,
            $this->extractData($object) ?: array()
        );
        if (null === $rawResponse) {
            return new ApiResponse($this->visitor);
        }

        return new ApiResponse($this->visitor, true, $rawResponse);
    }
}
