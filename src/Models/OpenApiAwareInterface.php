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

namespace Splash\OpenApi\Models;

use Splash\OpenApi\Hydrator\Hydrator;
use Splash\OpenApi\Models\Connexion\ConnexionInterface;

interface OpenApiAwareInterface
{
    /**
     * Api Model Class
     *
     * @return string
     */
    public function getModel() : string;

    /**
     * Get Connector Api Connexion
     *
     * @return ConnexionInterface
     */
    public function getConnexion() : ConnexionInterface;

    /**
     * @return Hydrator
     */
    public function getHydrator(): Hydrator;
}
