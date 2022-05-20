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
use Splash\Core\SplashCore      as Splash;
use Splash\Models\Fields\FieldsManagerTrait;
use Splash\Models\Helpers;
use Splash\OpenApi\Visitor\AbstractVisitor;

/**
 * Get Data from Generic Open API Class
 */
class Getter
{
    use FieldsManagerTrait;

    /**
     * Check if Field is Defined and Readable
     *
     * @param class-string $model   Target Model
     * @param string       $fieldId Field Identifier / Name
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
            && !Descriptor::isWriteOnlyField((string) $model, (string) $fieldId);
    }

    /**
     * Check if Field Data Exists and is Not Empty
     *
     * @param object $object  Object to Update
     * @param string $fieldId Field Identifier / Name
     *
     * @throws Exception
     *
     * @return bool
     */
    public static function exists(object $object, string $fieldId): bool
    {
        return !is_null(self::getRawData($object, $fieldId));
    }

    /**
     * Get an Object Field Data
     *
     * @param class-string $model   Target Model
     * @param object       $object  Object to Update
     * @param string       $fieldId Field Identifier / Name
     *
     * @throws Exception
     *
     * @return null|array<string, null|array<string, null|array|scalar>|object|scalar>|object|scalar
     */
    public static function get(string $model, object $object, string $fieldId)
    {
        //====================================================================//
        // Detect SubResource Fields Types
        $prefix = Descriptor::getSubResourcePrefix($fieldId);
        if (!$prefix) {
            //====================================================================//
            // Read Simple Fields Types
            return self::getSimpleData($model, $object, $fieldId);
        }
        //====================================================================//
        // Load SubResource Object
        $subResourceModel = Descriptor::getSubResourceModel($model, $prefix);
        $subResource = self::getRawData($object, $prefix);
        if (!$subResourceModel || !$subResource || !($subResource instanceof $subResourceModel)) {
            return null;
        }

        return self::getSimpleData(
            $subResourceModel,
            $subResource,
            (string) Descriptor::getSubResourceField($fieldId)
        );
    }

    /**
     * Collect Required Fields
     *
     * @param AbstractVisitor $visitor
     * @param object          $inputs
     *
     *@throws Exception
     *
     * @return null|object
     */
    public static function getRequiredFields(AbstractVisitor $visitor, object $inputs): ?object
    {
        $newObject = array();
        //====================================================================//
        // Walk on required Field Ids
        foreach (Descriptor::getRequiredFields($visitor->getModel()) as $fieldId) {
            //====================================================================//
            // Ensure Field is Available
            if (!self::exists($inputs, $fieldId)) {
                Splash::log()->err("ErrLocalFieldMissing", __CLASS__, __FUNCTION__, $fieldId);

                return null;
            }
            $newObject[$fieldId] = self::get($visitor->getModel(), $inputs, $fieldId);
        }

        return $visitor->getHydrator()->hydrate($newObject, $visitor->getModel());
    }

    /**
     * Get an Object List Field Data
     *
     * @param class-string $model   Target Model
     * @param object       $object
     * @param string       $listId
     * @param string       $fieldId
     *
     * @throws Exception
     *
     * @return array
     */
    public static function getListData(string $model, object $object, string $listId, string $fieldId): array
    {
        $results = array();
        //====================================================================//
        // Load Raw List Data
        $rawData = self::getRawData($object, $listId);
        if (!is_iterable($rawData)) {
            return $results;
        }
        //====================================================================//
        // Walk on Raw List Data
        foreach ($rawData as $index => $item) {
            if (!is_object($item) || !self::exists($item, $fieldId)) {
                $results[$index] = null;

                continue;
            }
            $results[$index] = self::getSimpleData($model, $item, $fieldId);
        }

        return $results;
    }

    /**
     * Extract Raw Data from An Object
     *
     * @param object $object
     * @param string $fieldId
     *
     * @return null|array<string, null|array<string, null|array|scalar>|object|scalar>|scalar
     */
    public static function getRawData(object $object, string $fieldId)
    {
        foreach (array('get', 'is', 'has') as $prefix) {
            $method = $prefix.ucfirst($fieldId);
            if (method_exists($object, $method)) {
                return $object->{$method}();
            }
        }

        return $object->{$fieldId} ?? null;
    }

    /**
     * Extract Raw Data from An Object
     *
     * @param object $object
     * @param string $fieldId
     *
     * @return null|scalar>
     */
    public static function getRawScalarData(object $object, string $fieldId)
    {
        return is_scalar($rawData = self::getRawData($object, $fieldId)) ? $rawData : null;
    }

    /**
     * Get a Simple Object Field Data
     *
     * @param class-string $model   Target Model
     * @param object       $object  Object to Update
     * @param string       $fieldId Field Identifier / Name
     *
     * @throws Exception
     *
     * @return null|array<string, null|array<string, null|array|scalar>|object|scalar>|scalar
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private static function getSimpleData(string $model, object $object, string $fieldId)
    {
        $fieldType = Descriptor::getFieldType($model, $fieldId);
        //====================================================================//
        // Read Simple Fields Types
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
                return (string) self::getRawScalarData($object, $fieldId);
            case SPL_T_BOOL:
                return (bool) self::getRawScalarData($object, $fieldId);
            case SPL_T_DOUBLE:
                return (float) self::getRawScalarData($object, $fieldId);
            case SPL_T_INT:
                return (int) self::getRawScalarData($object, $fieldId);
            case SPL_T_DATE:
                return self::toDate(SPL_T_DATECAST, self::getRawData($object, $fieldId));
            case SPL_T_DATETIME:
                return self::toDate(SPL_T_DATETIMECAST, self::getRawData($object, $fieldId));
            case SPL_T_PRICE:
                $price = self::getRawData($object, $fieldId);

                return Helpers\PricesHelper::isValid($price) ? $price : null;
            case SPL_T_FILE:
            case SPL_T_STREAM:
            case SPL_T_IMG:
                return self::getRawData($object, $fieldId);
        }
        //====================================================================//
        // Read Simple Object ID Fields Types
        if (self::isIdField($fieldType)) {
            return self::getRawData($object, $fieldId);
        }

        return null;
    }

    /**
     * Get a Simple Object Date or DateTime
     *
     * @param string $format
     * @param mixed  $rawData
     *
     * @throws Exception
     *
     * @return null|string
     */
    private static function toDate(string $format, $rawData): ?string
    {
        if ($rawData instanceof DateTime) {
            return $rawData->format($format);
        }
        if (!is_scalar($rawData)) {
            return null;
        }

        try {
            return (new DateTime((string) $rawData))->format($format);
        } catch (\Exception $ex) {
            return null;
        }
    }
}
