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

namespace Splash\OpenApi\Models\Metadata;

use Exception;
use Splash\Core\SplashCore as Splash;
use Splash\OpenApi\Models\Objects\CRUDCoreTrait;

/**
 * Splash Open Api with Metadata Object CRUD Functions
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
        // Collect Required Fields from Inputs
        $model = $this->visitor->getModel();
        $requiredFields = array_replace_recursive(
            $this->metadataAdapter->getRequiredFields($model),
            $this->metadataAdapter->getOnCreateFields($model),
        );
        //====================================================================//
        // Hydrate Object with Required Data Only
        $newObject = $this->visitor->getHydrator()->hydrate(
            array_intersect_key($this->in, $requiredFields),
            $model
        );
        foreach ($requiredFields as $fieldId => $requiredField) {
            if ($value = $this->in[$fieldId] ?? null) {
                $this->metadataAdapter->setData($requiredField, $newObject, $value);
            }
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
        $object = $createResponse->getResults();

        return ($object instanceof $model) ? $object : null;
    }
}
