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

namespace Splash\OpenApi\Fields;

use DateTime;
use Exception;
use Splash\Models\Fields\FieldsManagerTrait;
use Splash\Models\Helpers;

/**
 * Set Data to Generic Open API Class
 */
class Setter
{
    use FieldsManagerTrait;

    /**
     * @param class-string $model   Target Model
     * @param string       $fieldId
     *
     *@throws Exception
     *
     * @return bool
     */
    public static function has(string $model, string $fieldId): bool
    {
        //====================================================================//
        // Detect SubResource Fields Types
        $prefix = Descriptor::getSubResourcePrefix($fieldId);
        //====================================================================//
        // Override Model
        $model = $prefix
            ? Descriptor::getSubResourceModel($model, $prefix)
            : $model;
        //====================================================================//
        // Override Field Id
        $fieldId = $prefix
            ? Descriptor::getSubResourceField($fieldId)
            : $fieldId;

        return Descriptor::hasField((string) $model, (string) $fieldId)
            && !Descriptor::isReadOnlyField((string) $model, (string) $fieldId);
    }

    /**
     * Set an Object Filed Data
     *
     * @param class-string $model     Target Model
     * @param object       $object    Object to Update
     * @param string       $fieldId   Field Identifier
     * @param mixed        $fieldData Field Data
     *
     * @throws Exception
     *
     * @return null|bool Null if Fail, True if Updated
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public static function set(string $model, object &$object, string $fieldId, $fieldData): ?bool
    {
        //====================================================================//
        // Detect SubResource Fields Types
        $prefix = Descriptor::getSubResourcePrefix($fieldId);
        if (!$prefix) {
            //====================================================================//
            // Write Simple Fields Types
            return self::setSimpleData($model, $object, $fieldId, $fieldData);
        }
        //====================================================================//
        // Load SubResource Object
        $subResourceClass = Descriptor::getSubResourceModel($model, $prefix);
        if (!$subResourceClass) {
            throw new Exception("Unable to identify SubResource Class");
        }
        $subResource = Getter::getRawData($object, $prefix);
        //====================================================================//
        // Create SubResource Object
        if (!$subResource) {
            $subResource = new $subResourceClass();
        }
        //====================================================================//
        // Write Data to SubResource Object
        $result = self::setSimpleData(
            $subResourceClass,
            $subResource,
            (string) Descriptor::getSubResourceField($fieldId),
            $fieldData
        );
        //====================================================================//
        // Update SubResource on Object
        if ($result) {
            self::setRawData($model, $object, $prefix, $subResource);
        }

        return $result;
    }

    /**
     * Set Multiple Fields of an Object
     *
     * @param class-string            $model      Target Model
     * @param object                  $object     Object to Update
     * @param iterable<string, mixed> $fieldsData Fields Data
     *
     * @throws Exception
     *
     * @return null|bool Null if Fail, True if Updated
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public static function setMulti(string $model, object &$object, iterable $fieldsData): ?bool
    {
        $updated = false;
        //====================================================================//
        // Update Multiple Data
        foreach ($fieldsData as $fieldId => $fieldData) {
            $itemUpdated = self::set($model, $object, $fieldId, $fieldData);
            $updated = is_null($itemUpdated) ? null : ($updated || $itemUpdated);
        }

        return $updated;
    }

    /**
     * @param class-string $model     Target Model
     * @param object       $object    Object to Update
     * @param string       $fieldId   Field Identifier
     * @param mixed        $fieldData Field Data
     * @param bool         $compare   Compare Field Data
     *
     * @throws Exception
     *
     * @return null|bool Null if Fail, True if Updated
     */
    public static function setRawData(
        string $model,
        object &$object,
        string $fieldId,
        $fieldData,
        bool $compare = true
    ): ?bool {
        //====================================================================//
        // Compare with Previous Value
        if ($compare && self::isSameData($model, $object, $fieldId, $fieldData)) {
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

    /**
     * Set an Object Field Data
     *
     * @param class-string $model     Target Model
     * @param object       $object    Object to Update
     * @param string       $fieldId   Field Identifier
     * @param mixed        $fieldData Field Data
     *
     * @throws Exception
     *
     * @return null|bool Null if Fail, True if Updated
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private static function setSimpleData(string $model, object &$object, string $fieldId, $fieldData): ?bool
    {
        $fieldType = Descriptor::getFieldType($model, $fieldId);
        //====================================================================//
        // Write Simple Fields Types
        switch ($fieldType) {
            case SPL_T_VARCHAR:
            case SPL_T_URL:
            case SPL_T_EMAIL:
            case SPL_T_PHONE:
            case SPL_T_LANG:
            case SPL_T_COUNTRY:
            case SPL_T_STATE:
            case SPL_T_CURRENCY:
            case SPL_T_INLINE:
                return self::setRawData($model, $object, $fieldId, (string) $fieldData);
            case SPL_T_BOOL:
                return self::setRawData($model, $object, $fieldId, (bool) $fieldData);
            case SPL_T_DOUBLE:
                return self::setRawData($model, $object, $fieldId, (float) $fieldData);
            case SPL_T_INT:
                return self::setRawData($model, $object, $fieldId, (int) $fieldData);
            case SPL_T_DATE:
            case SPL_T_DATETIME:
                try {
                    $datetime = new DateTime($fieldData);
                } catch (Exception $ex) {
                    $datetime = null;
                }

                return self::setRawData($model, $object, $fieldId, $datetime);
            case SPL_T_PRICE:
                $fieldData = Helpers\PricesHelper::isValid($fieldData) ? $fieldData : null;

                return self::setRawData($model, $object, $fieldId, $fieldData);
        }
        //====================================================================//
        // Write Simple Object ID Fields Types
        if (self::isIdField($fieldType)) {
            return self::setRawData($model, $object, $fieldId, (string) $fieldData);
        }

        return null;
    }

    /**
     * Compare New Object Field Data with Existing
     *
     * @param class-string $model     Target Model
     * @param object       $object    Object to Update
     * @param string       $fieldId   Field Identifier / Name
     * @param mixed        $fieldData Field Data
     *
     * @throws Exception
     *
     * @return bool
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private static function isSameData(string $model, object $object, string $fieldId, $fieldData): ?bool
    {
        //====================================================================//
        // Load Original Field Data
        $original = Getter::get($model, $object, $fieldId);
        //====================================================================//
        // Compare with New Data
        switch (Descriptor::getFieldType($model, $fieldId)) {
            case SPL_T_VARCHAR:
            case SPL_T_URL:
            case SPL_T_EMAIL:
            case SPL_T_PHONE:
            case SPL_T_LANG:
            case SPL_T_COUNTRY:
            case SPL_T_STATE:
            case SPL_T_CURRENCY:
            case SPL_T_INLINE:
            case SPL_T_BOOL:
            case SPL_T_DOUBLE:
            case SPL_T_INT:
                return $original == $fieldData;
            case SPL_T_DATE:
                if (($original instanceof DateTime) && ($fieldData instanceof DateTime)) {
                    return $original->format(SPL_T_DATECAST) == $fieldData->format(SPL_T_DATECAST);
                }

                return false;
            case SPL_T_DATETIME:
                if ($fieldData instanceof DateTime) {
                    return $original == $fieldData->format(SPL_T_DATETIMECAST);
                }

                return false;
            case SPL_T_PRICE:
                return Helpers\PricesHelper::compare($original, $fieldData);
            case SPL_T_FILE:
            case SPL_T_IMG:
                if (is_array($original) && is_array($fieldData)) {
                    return $original["md5"] == $fieldData["md5"];
                }

                return $original == $fieldData;
        }

        return null;
    }
}
