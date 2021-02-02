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

namespace Splash\OpenApi\Fields;

use Exception;
use Splash\OpenApi\Models\OpenApiAwareInterface;

/**
 * Set Data to Generic Open API Class
 */
class Setter
{
    /**
     * @var OpenApiAwareInterface
     */
    protected $api;

    /**
     * @param OpenApiAwareInterface $apiAware
     * @param string                $fieldId
     *
     * @throws Exception
     *
     * @return bool
     */
    public static function has(OpenApiAwareInterface $apiAware, string $fieldId): bool
    {
        return Descriptor::hasField($apiAware->getModel(), $fieldId)
            && !Descriptor::isReadOnlyField($apiAware->getModel(), $fieldId);
    }

    /**
     * Set an Object Filed Data
     *
     * @param OpenApiAwareInterface $apiAware
     * @param object                $object    Object to Update
     * @param string                $fieldId   Field Identifier
     * @param mixed                 $fieldData Field Data
     *
     * @throws Exception
     *
     * @return null|bool Null if Fail, True if Updated
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public static function set(OpenApiAwareInterface $apiAware, object &$object, string $fieldId, $fieldData): ?bool
    {
        switch (Descriptor::getFieldType($apiAware->getModel(), $fieldId)) {
            case SPL_T_VARCHAR:
            case SPL_T_URL:
            case SPL_T_EMAIL:
            case SPL_T_COUNTRY:
            case SPL_T_CURRENCY:
            case SPL_T_INLINE:
                return self::setRawData($apiAware, $object, $fieldId, (string) $fieldData);
            case SPL_T_BOOL:
                return self::setRawData($apiAware, $object, $fieldId, (bool) $fieldData);
            case SPL_T_DOUBLE:
                return self::setRawData($apiAware, $object, $fieldId, (float) $fieldData);
            case SPL_T_INT:
                return self::setRawData($apiAware, $object, $fieldId, (int) $fieldData);
            case SPL_T_DATE:
            case SPL_T_DATETIME:
                return self::setRawData($apiAware, $object, $fieldId, $fieldData);
        }

        return null;
    }

    /**
     * @param OpenApiAwareInterface $apiAware
     * @param object                $object    Object to Update
     * @param string                $fieldId   Field Identifier
     * @param mixed                 $fieldData Field Data
     *
     * @throws Exception
     *
     * @return null|bool Null if Fail, True if Updated
     */
    private static function setRawData(
        OpenApiAwareInterface $apiAware,
        object &$object,
        string $fieldId,
        $fieldData
    ): ?bool {
        //====================================================================//
        // Compare with Previous Value
        if (Getter::get($apiAware, $object, $fieldId) == $fieldData) {
            return false;
        }
        //====================================================================//
        // Write with Setter Method Detection
        foreach (array('set') as $prefix) {
            $method = $prefix.ucfirst($fieldId);
            if (method_exists($object, $method)) {
                $object->{$method}($fieldData);

                return true;
            }
        }
        //====================================================================//
        // Write to Property
        if (property_exists($object, $fieldId)) {
            $object->{$fieldId} = $fieldData;

            return true;
        }

        return null;
    }
}
