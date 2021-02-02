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
 * Update Objects Data form Remote Server with PATCH Method
 */
class Patch extends AbstractAction
{
    /**
     * Execute Objects List Action.
     *
     * @param OpenApiAwareInterface $apiAware
     * @param string                $path
     * @param object                $object
     */
    public function __construct(OpenApiAwareInterface $apiAware, string $path, object $object)
    {
        $this->api = $apiAware;
        //====================================================================//
        // Init Empty Results
        $this->setResults(null);
        //====================================================================//
        // Execute Get Request
        $rawResponse = $this->api->getConnexion()->patch($path, self::extractData($object));
        if (null === $rawResponse) {
            return;
        }
        //====================================================================//
        // Store Results
        $this->setResults($object);
        $this->setSuccessful();
    }

    /**
     * Extract Objects Write Data.
     *
     * @param object $object
     *
     * @return array
     */
    public function extractData(object $object): array
    {
        //====================================================================//
        // Hydrate Results
        return $this->api->getHydrator()->extract($object);
    }
}
