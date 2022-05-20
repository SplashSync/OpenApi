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

namespace Splash\OpenApi\Bundle\Objects;

use Exception;
use Splash\Client\Splash;
use Splash\OpenApi\Bundle\Models\Api;
use Splash\OpenApi\Bundle\Services\OpenApiConnector;
use Splash\OpenApi\Models\Objects\AbstractApiObject;

/**
 * OpenApi Implementation for ListResource Object
 */
class ListResource extends AbstractApiObject
{
    //====================================================================//
    // Object Definition Parameters
    //====================================================================//

    /**
     * {@inheritdoc}
     */
    protected static string $name = "ListResource";

    /**
     * {@inheritdoc}
     */
    protected static string $description = "ListResource Open API Object";

    /**
     * {@inheritdoc}
     */
    protected static string $ico = "fa fa-list";

    //====================================================================//
    // General Class Variables
    //====================================================================//

    /**
     * @var Api\ListResource
     */
    protected object $object;

    /**
     * @var OpenApiConnector
     */
    protected OpenApiConnector $connector;

    /**
     * Class Constructor
     *
     * @param OpenApiConnector $connector
     *
     * @throws Exception
     */
    public function __construct(OpenApiConnector $connector)
    {
        parent::__construct($connector->getConnexion(), $connector->getHydrator(), Api\ListResource::class);
        //====================================================================//
        //  Load Translation File
        Splash::translator()->load('local');
    }
}
