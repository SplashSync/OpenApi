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

use DateTime;
use Exception;
use Splash\Core\SplashCore      as Splash;
use Splash\OpenApi\Visitor\AbstractVisitor;

/**
 * Get Data from Generic Open API Class
 */
class Getter
{
    /**
     * @var AbstractVisitor
     */
    protected $visitor;

    /**
     * Check if Field is Defined and Readable
     *
     * @param AbstractVisitor $visitor
     * @param string          $fieldId Field Identifier / Name
     *
     *@throws Exception
     *
     * @return bool
     */
    public static function has(AbstractVisitor $visitor, string $fieldId): bool
    {
        //====================================================================//
        // Detect SubResource Fields Types
        $prefix = Descriptor::getSubResourcePrefix($fieldId);
        //====================================================================//
        // Override Model
        $model = $prefix
            ? Descriptor::getSubResourceModel($visitor->getModel(), $prefix)
            : $visitor->getModel();
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
        return !empty(self::getRawData($object, $fieldId));
    }

    /**
     * Get an Object Field Data
     *
     * @param AbstractVisitor $visitor
     * @param object          $object  Object to Update
     * @param string          $fieldId Field Identifier / Name
     * @param null|string     $model   Override Model
     *
     * @throws Exception
     *
     * @return mixed
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public static function get(AbstractVisitor $visitor, object $object, string $fieldId, string $model = null)
    {
        $model = $model ?: $visitor->getModel();
        //====================================================================//
        // Detect SubResource Fields Types
        $prefix = Descriptor::getSubResourcePrefix($fieldId);
        if ($prefix) {
            $subResource = self::getRawData($object, $prefix);

            return self::get(
                $visitor,
                $subResource,
                (string) Descriptor::getSubResourceField($fieldId),
                Descriptor::getSubResourceModel($visitor->getModel(), $prefix),
            );
        }
        //====================================================================//
        // Read Simple Fields Types
        switch (Descriptor::getFieldType($model, $fieldId)) {
            case SPL_T_VARCHAR:
            case SPL_T_URL:
            case SPL_T_EMAIL:
            case SPL_T_COUNTRY:
            case SPL_T_CURRENCY:
            case SPL_T_INLINE:
                return (string) self::getRawData($object, $fieldId);
            case SPL_T_BOOL:
                return (bool) self::getRawData($object, $fieldId);
            case SPL_T_DOUBLE:
                return (float) self::getRawData($object, $fieldId);
            case SPL_T_INT:
                return (int) self::getRawData($object, $fieldId);
            case SPL_T_DATE:
                $dateTime = self::getRawData($object, $fieldId);

                return ($dateTime instanceof DateTime)
                    ? $dateTime->format(SPL_T_DATECAST)
                    : null;
            case SPL_T_DATETIME:
                $dateTime = self::getRawData($object, $fieldId);

                return ($dateTime instanceof DateTime)
                    ? $dateTime->format(SPL_T_DATETIMECAST)
                    : null;
        }

        return null;
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
            $newObject[$fieldId] = self::get($visitor, $inputs, $fieldId);
        }

        return $visitor->getHydrator()->hydrate($newObject, $visitor->getModel());
    }

    /**
     * Get an Object List Field Data
     *
     * @param object $object
     * @param string $listId
     * @param string $fieldId
     *
     * @throws Exception
     *
     * @return array
     */
    public static function getListData(object $object, string $listId, string $fieldId): array
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
            if (is_object($item) && self::exists($item, $fieldId)) {
                $results[$index] = self::getRawData($item, $fieldId);
            }
        }

        return $results;
    }

    /**
     * Extract Raw Data from An Object
     *
     * @param object $object
     * @param string $fieldId
     *
     * @return mixed
     */
    private static function getRawData(object $object, string $fieldId)
    {
        foreach (array('get', 'is', 'has') as $prefix) {
            $method = $prefix.ucfirst($fieldId);
            if (method_exists($object, $method)) {
                return $object->{$method}();
            }
        }

        return isset($object->{$fieldId}) ? $object->{$fieldId} : null;
    }
}
