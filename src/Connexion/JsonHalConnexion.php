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

namespace Splash\OpenApi\Connexion;

use Splash\OpenApi\Models\Connexion\AbstractConnexion;

/**
 * Httpful Pure Json API Connexion
 */
class JsonHalConnexion extends AbstractConnexion
{
    /**
     * @var string
     */
    const MIME_TYPE_EXPECT = "application/hal+json";
}
