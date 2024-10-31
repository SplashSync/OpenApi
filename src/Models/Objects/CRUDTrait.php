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

namespace Splash\OpenApi\Models\Objects;

use Exception;
use Splash\Core\SplashCore as Splash;
use Splash\OpenApi\Fields as ApiFields;

/**
 * Splash Open Api Object CRUD Functions
 */
trait CRUDTrait
{
    use CRUDCoreTrait;

    /**
     * Create Request Object
     *
     * @throws Exception
     *
     * @return null|object New Object
     */
    public function create(): ?object
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace();
        //====================================================================//
        // Collect Required Fields
        $newObject = ApiFields\Getter::getRequiredFields($this->visitor, (object) $this->in);
        if (!$newObject) {
            return null;
        }
        //====================================================================//
        // Create Remote Object
        $createResponse = $this->visitor->create($newObject);
        //====================================================================//
        // Create Remote Object
        if (!$createResponse->isSuccess()) {
            return null;
        }
        //====================================================================//
        // Verify Returned Object Type
        $model = $this->visitor->getModel();
        $object = $createResponse->getResults();

        return ($object instanceof $model) ? $object : null;
    }
}
