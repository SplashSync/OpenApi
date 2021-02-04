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
use Splash\OpenApi\Fields as ApiFields;

trait ListFieldsTrait
{
    /**
     * Read requested Field
     *
     * @param string $key       Input List Key
     * @param string $fieldName Field Identifier / Name
     *
     * @throws Exception
     */
    protected function getApiListFields(string $key, string $fieldName): void
    {
        //====================================================================//
        // Check if Field is a List field
        $listInfos = $this->lists()->isListField($fieldName);
        if (!$listInfos) {
            return;
        }
        //====================================================================//
        // Check if List field & Init List Array
        $listName = (string) $this->lists()->listName($fieldName);
        $fieldId = self::lists()->initOutput($this->out, (string) $this->lists()->listName($fieldName), $fieldName);
        if (!$fieldId) {
            return;
        }
        //====================================================================//
        // Fill List with Data
        foreach (ApiFields\Getter::getListData($this->object, $listName, $fieldId) as $index => $data) {
            //====================================================================//
            // Insert Data in List
            self::lists()->Insert($this->out, $listName, $fieldName, $index, $data);
        }
        unset($this->in[$key]);
    }
}
