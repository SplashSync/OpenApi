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
 * Base Api Delete Action
 */
abstract class AbstractDeleteAction extends AbstractAction
{
    /**
     * Execute Objects Update Action.
     *
     * @param object|string $objectOrId
     *
     * @return ApiResponse
     */
    public function execute($objectOrId): ApiResponse
    {
        //====================================================================//
        // Build Target Uri
        $uri = $this->visitor->getItemUri($objectOrId);
        if (!$uri) {
            return new ApiResponse($this->visitor);
        }
        //====================================================================//
        // Execute Delete Request
        $rawResponse = $this->visitor->getConnexion()->delete($uri);
        if (null === $rawResponse) {
            return new ApiResponse($this->visitor);
        }

        return new ApiResponse($this->visitor, true, $rawResponse);
    }
}
