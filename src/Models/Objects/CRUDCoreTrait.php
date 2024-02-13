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

use Splash\Core\SplashCore      as Splash;

/**
 * Splash Open Api Object Core CRUD Functions
 */
trait CRUDCoreTrait
{
    /**
     * Load Request Object
     *
     * @param string $objectId Object id
     *
     * @return null|object
     */
    public function load(string $objectId): ?object
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace();
        //====================================================================//
        // Load Remote Object
        $loadResponse = $this->visitor->load($objectId);
        if (!$loadResponse->isSuccess()) {
            return null;
        }
        //====================================================================//
        // Return Hydrated Object
        $object = $loadResponse->getResults();

        return is_object($object) ? $object : null;
    }

    /**
     * Update Request Object
     *
     * @param bool $needed Is This Update Needed
     *
     * @return null|string Object ID of False if Failed to Update
     */
    public function update(bool $needed): ?string
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace();
        //====================================================================//
        // No Update Required
        if (!$needed) {
            return $this->getObjectIdentifier();
        }
        //====================================================================//
        // Update Remote Object
        $updateResponse = $this->visitor->update((string) $this->getObjectIdentifier(), $this->object);

        //====================================================================//
        // Return Object Id or False
        return $updateResponse->isSuccess()
            ? $this->getObjectIdentifier()
            : Splash::log()->errNull(
                "Unable to Update Object (".$this->getObjectIdentifier().")."
            )
        ;
    }

    /**
     * Delete requested Object
     *
     * @param null|string $objectId Object ID
     *
     * @return bool
     */
    public function delete(string $objectId = null): bool
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
    public function getObjectIdentifier(): ?string
    {
        $objectId = $this->visitor->getItemId($this->object);

        return $objectId ?: null;
    }
}
