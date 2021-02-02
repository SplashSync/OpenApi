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
 * Delete Objects Data form Remote Server
 */
class Delete extends AbstractAction
{
    /**
     * Execute Objects Delete Action.
     *
     * @param OpenApiAwareInterface $apiAware
     * @param string                $path
     */
    public function __construct(OpenApiAwareInterface $apiAware, string $path)
    {
        $this->api = $apiAware;
        //====================================================================//
        // Init Empty Results
        $this->setResults(null);
        //====================================================================//
        // Execute Get Request
        $rawResponse = $this->api->getConnexion()->delete($path);
        if (null === $rawResponse) {
            return;
        }
        //====================================================================//
        // Store Results
        $this->setResults($rawResponse);
        $this->setSuccessful();
    }
}
