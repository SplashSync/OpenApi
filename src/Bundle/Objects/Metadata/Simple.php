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

namespace Splash\OpenApi\Bundle\Objects\Metadata;

use Exception;
use Splash\Client\Splash;
use Splash\OpenApi\Bundle\Models\Metadata as ApiModels;
use Splash\OpenApi\Bundle\Services\OpenApiConnector;
use Splash\OpenApi\Models\Metadata\AbstractApiMetadataObject;

/**
 * OpenApi Implementation for Simple Object with Attributes Metadata Parsing
 */
class Simple extends AbstractApiMetadataObject
{
    //====================================================================//
    // General Class Variables
    //====================================================================//

    /**
     * @var ApiModels\Simple
     */
    protected object $object;

    /**
     * Class Constructor
     *
     * @param OpenApiConnector $connector
     *
     * @throws Exception
     */
    public function __construct(OpenApiConnector $connector)
    {
        parent::__construct(
            $connector->getMetadataAdapter(),
            $connector->getConnexion(),
            $connector->getHydrator(),
            ApiModels\Simple::class
        );
        $this->visitor->setTimezone("UTC");
        //====================================================================//
        //  Load Translation File
        Splash::translator()->load('local');
    }
}
