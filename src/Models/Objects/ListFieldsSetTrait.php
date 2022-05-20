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
use Splash\OpenApi\Fields as ApiFields;

/**
 * Splash Open Api Object Lists Setter Function
 */
trait ListFieldsSetTrait
{
    /**
     * Write Given Fields
     *
     * @param string                                                                  $fieldName Field Identifier / Name
     * @param null|array<string, null|array<string, null|array|scalar>|scalar>|scalar $fieldData Field Data
     *
     * @throws Exception
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function setApiListFields(string $fieldName, $fieldData): void
    {
        //====================================================================//
        // Check if Field is a List field
        if (!ApiFields\Descriptor::isListResource($this->visitor->getModel(), $fieldName)) {
            return;
        }
        $itemClass = ApiFields\Descriptor::getListResourceModel($this->visitor->getModel(), $fieldName);
        //====================================================================//
        // Load Original Data
        $rawData = ApiFields\Getter::getRawData($this->object, $fieldName);
        $originData = $rawData ?: array();
        $finalData = array();
        $updated = false;
        //====================================================================//
        // Check if Field is a List field
        if (is_iterable($fieldData)) {
            foreach ($fieldData as $itemData) {
                //====================================================================//
                // Safety Check => Item data Must be Iterable
                if (!is_iterable($itemData)) {
                    continue;
                }
                //====================================================================//
                // Load / Create Item
                $originData = is_iterable($originData) ? $originData : array();
                $originItem = array_shift($originData);
                $originItem = $originItem ?: new $itemClass();
                //====================================================================//
                // Update Item Data
                $itemUpdated = ApiFields\Setter::setMulti($itemClass, $originItem, $itemData);
                $updated = is_null($itemUpdated) ? null : ($updated || $itemUpdated);
                //====================================================================//
                // Push Item to Final Data
                $finalData[] = $originItem;
            }
            $updated |= !empty($originData);
        }
        //====================================================================//
        // List Data was Updated
        if ($updated) {
            //====================================================================//
            // Update List Data
            ApiFields\Setter::setRawData($itemClass, $this->object, $fieldName, $finalData, false);
            //====================================================================//
            // Mark Object & List as Updated
            $this->needUpdate();
            $this->needUpdate(ucfirst($fieldName));
        }

        unset($this->in[$fieldName]);
    }
}
