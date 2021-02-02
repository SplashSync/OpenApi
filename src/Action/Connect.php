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

namespace Splash\OpenApi\Action;

use Splash\Core\SplashCore as Splash;
use Splash\OpenApi\Models\Action\AbstractAction;
use Splash\OpenApi\Models\Connexion\ConnexionInterface;

/**
 * Ensure Remote Server Respond to Secured Requests
 */
class Connect extends AbstractAction
{
    /**
     * Execute Connect Action.
     *
     * @param ConnexionInterface $connexion
     * @param null|string        $path
     */
    public function __construct(ConnexionInterface $connexion, string $path = null)
    {
        //====================================================================//
        // If Test Failed
        if (null === $connexion->get($path)) {
            return;
        }
        //====================================================================//
        // User Log message
        Splash::log()->msg("Connect Succeeded on ".$connexion->getEndPoint());
        $this->setSuccessful();
    }
}
