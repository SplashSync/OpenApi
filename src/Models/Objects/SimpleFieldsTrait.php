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

trait SimpleFieldsTrait
{
    /**
     * Build Objects Fields from OpenApi Model.
     *
     * @throws Exception
     *
     * @return void
     */
    protected function buildApiSimpleFields(): void
    {
        ApiFields\Builder::buildModelFields($this->fieldsFactory(), $this->model);
    }

    /**
     * Read API Simple Field
     *
     * @param string $key       Input List Key
     * @param string $fieldName Field Identifier / Name
     *
     * @throws Exception
     */
    protected function getApiSimpleFields(string $key, string $fieldName): void
    {
        //====================================================================//
        // Check if Field Exists for Reading
        if (!ApiFields\Getter::has($this->visitor->getModel(), $fieldName)) {
            return;
        }
        //====================================================================//
        // Read Data
        $this->out[$fieldName] = ApiFields\Getter::get($this->visitor->getModel(), $this->object, $fieldName);
        unset($this->in[$key]);
    }

    /**
     * Write Given Fields
     *
     * @param string $fieldName Field Identifier / Name
     * @param mixed  $fieldData Field Data
     *
     * @throws Exception
     *
     * @return void
     */
    protected function setApiSimpleFields(string $fieldName, $fieldData): void
    {
        //====================================================================//
        // Check if Field Exists for Writing
        if (!ApiFields\Setter::has($this->visitor->getModel(), $fieldName)) {
            return;
        }
        //====================================================================//
        // Write Data
        $updated = ApiFields\Setter::set($this->visitor->getModel(), $this->object, $fieldName, $fieldData);
        //====================================================================//
        // Write Fail
        if (is_null($updated)) {
            return;
        }
        unset($this->in[$fieldName]);
        //====================================================================//
        // Data was Updated
        if ($updated) {
            $this->needUpdate();
        }
    }
}
