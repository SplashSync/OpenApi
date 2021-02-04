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

namespace Splash\OpenApi\Models\Objects;

use Exception;
use Splash\Core\SplashCore      as Splash;
use Splash\OpenApi\Fields as ApiFields;
use stdClass;

/**
 * ReCommerce Orders CRUD Functions
 */
trait CRUDTrait
{
    /**
     * Load Request Object
     *
     * @param string $objectId Object id
     *
     * @return false|stdClass
     */
    public function load($objectId)
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace();
        //====================================================================//
        // Load Remote Object
        $loadResponse = $this->visitor->load($objectId);
        if (!$loadResponse->isSuccess()) {
            return false;
        }
        //====================================================================//
        // Return Hydrated Object
        return $loadResponse->getResults();
    }

    /**
     * Create Request Object
     *
     * @throws Exception
     *
     * @return false|object New Object
     */
    public function create()
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace();
        //====================================================================//
        // Collect Required Fields
        $newObject = ApiFields\Getter::getRequiredFields($this->visitor, (object) $this->in);
        if (!$newObject) {
            return false;
        }
        //====================================================================//
        // Create Remote Object
        $createResponse = $this->visitor->create($newObject);
        //====================================================================//
        // Create Remote Object
        if (!$createResponse->isSuccess()) {
            return false;
        }
        //====================================================================//
        // Verify Returned Object Type
        $model = $this->visitor->getModel();
        $object = $createResponse->getResults();

        return ($object instanceof $model) ? $object : false;
    }

    /**
     * Update Request Object
     *
     * @param bool $needed Is This Update Needed
     *
     * @return false|string Object Id of False if Failed to Update
     */
    public function update(bool $needed)
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace();
        //====================================================================//
        // No Update Required
        if (!$needed) {
            return $this->getObjectIdentifier();
        }

//        dump($this->object);
//        exit;

        //====================================================================//
        // Update Remote Object
        $updateResponse = $this->visitor->update((string) $this->getObjectIdentifier(), $this->object);
        //====================================================================//
        // Return Object Id or False
        return $updateResponse->isSuccess()
            ? $this->getObjectIdentifier()
            : Splash::log()->errTrace(
                "Unable to Update Object (".$this->getObjectIdentifier().")."
            )
        ;
    }

    /**
     * Delete requested Object
     *
     * @param null|string $objectId Object Id
     *
     * @return bool
     */
    public function delete($objectId = null)
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace();
        if (empty($objectId)) {
            return true;
        }
        //====================================================================//
        // Load Remote Object
        $object = $this->load($objectId);
        if (empty($object)) {
            return Splash::log()->warTrace("Trying to Delete an Unknown Object (".$objectId.").");
        }
        //====================================================================//
        // Delete Remote Object
        $deleteResponse = $this->visitor->delete($object);

        return $deleteResponse->isSuccess();
    }

    /**
     * {@inheritdoc}
     */
    public function getObjectIdentifier()
    {
        $objectId = $this->visitor->getItemId($this->object);

        return $objectId ?: false;
    }
}
