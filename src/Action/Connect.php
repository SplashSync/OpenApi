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

namespace Splash\OpenApi\Action;

use Splash\Core\SplashCore as Splash;
use Splash\OpenApi\Models\Connexion\ConnexionInterface;

/**
 * Ensure Remote Server Respond to Secured Requests
 */
class Connect
{
    /**
     * Execute Connect Action.
     *
     * @param ConnexionInterface $connexion
     * @param null|string        $path
     *
     * @return bool
     */
    public static function execute(ConnexionInterface $connexion, string $path = null): bool
    {
        //====================================================================//
        // If Test Failed
        if (null === $connexion->get($path)) {
            return false;
        }
        //====================================================================//
        // User Log message
        Splash::log()->msg("Connect Succeeded on ".$connexion->getEndPoint().$path);

        return true;
    }
}
