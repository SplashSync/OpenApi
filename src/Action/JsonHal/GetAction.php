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

namespace Splash\OpenApi\Action\JsonHal;

use Splash\OpenApi\Models\Action\AbstractLoadAction;

/**
 * Read Objects Data form Remote Server
 */
class GetAction extends AbstractLoadAction
{
    use JsonHalOptionsTrait;

    /**
     * Json Hal Options
     *
     * @var array<string, array|string>
     */
    protected $options;

    /**
     * {@inheritDoc}
     */
    public function extractData(array $rawResponse): object
    {
        //====================================================================//
        // Merge Embedded Results
        if (isset($rawResponse[$this->getEmbeddedIndex()])) {
            $rawResponse = array_replace_recursive(
                $rawResponse,
                $rawResponse[$this->getEmbeddedIndex()]
            );
        }
        //====================================================================//
        // Hydrate Results
        return parent::extractData($rawResponse);
    }
}
