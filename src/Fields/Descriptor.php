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

namespace Splash\OpenApi\Fields;

use Exception;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Metadata\PropertyMetadata;
use Splash\OpenApi\Hydrator\Hydrator;
use Splash\OpenApi\Validator as SPL;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Mapping;
use Symfony\Component\Validator\Validation;

/**
 * Splash Api Fields Descriptor.
 */
class Descriptor
{
    /**
     * List of Loaded API Model Jms Serializer Metadata
     *
     * @var ClassMetadata[]
     */
    private static $meta = array();

    /**
     * List of Loaded Model Validator Metadata
     *
     * @var Mapping\ClassMetadata[]
     */
    private static $validators = array();

    /**
     * Object Hydrator
     *
     * @var Hydrator
     */
    private static $hydrator;

    /**
     * Field Ids to Exclude for each Model
     *
     * @var array
     */
    private static $exclude = array();

    /**
     * Class Names to Exclude for Sub-Resources
     *
     * @var array
     */
    private static $protectedClasses = array(
        \DateTime::class,
        \ArrayObject::class
    );

    //====================================================================//
    // MAIN METHODS
    //====================================================================//

    /**
     * Api Fields Descriptor constructor.
     *
     * @param Hydrator   $hydrator
     * @param string     $model
     * @param null|array $exclude
     *
     * @throws Exception
     *
     * @return void
     */
    public static function load(Hydrator $hydrator, string $model, array $exclude = null): void
    {
        //====================================================================//
        // Connect to Current Hydrator
        self::$hydrator = $hydrator;
        //====================================================================//
        // Load Serializer Metadata
        if (!isset(self::$meta[$model])) {
            self::getModelMetadata($model);
        }
        //====================================================================//
        // Load Excluded Fields
        self::$exclude[$model] = is_null($exclude) ? array('id') : $exclude;
    }

    /**
     * Get List of Required Fields.
     *
     * @param string $model
     *
     * @throws Exception
     *
     * @return array
     */
    public static function getRequiredFields(string $model): array
    {
        $requiredFields = array();
        //====================================================================//
        // Load Serializer Metadata
        $modelMetadata = self::getModelMetadata($model);
        //====================================================================//
        // Walk on Available Properties
        /** @var PropertyMetadata $serializerMetadata */
        foreach ($modelMetadata->propertyMetadata as $serializerMetadata) {
            if (self::isRequiredField("", "", $serializerMetadata)) {
                $requiredFields[] = $serializerMetadata->name;
            }
        }

        return $requiredFields;
    }

    /**
     * Check Serializer Metadata Field Exists.
     *
     * @param string $model
     * @param string $fieldId
     *
     * @throws Exception
     *
     * @return bool
     */
    public static function hasField(string $model, string $fieldId): bool
    {
        return class_exists($model)
            && isset(self::getModelMetadata($model)->propertyMetadata[$fieldId]);
    }

    //====================================================================//
    // FIELDS METADATA GETTERS
    //====================================================================//

    /**
     * Detect Field Type.
     *
     * @param string                $model
     * @param string                $fieldId
     * @param null|PropertyMetadata $metadata
     *
     * @throws Exception
     *
     * @return null|string
     */
    public static function getFieldType(string $model, string $fieldId, PropertyMetadata $metadata = null): ?string
    {
        $metadata = $metadata ?: self::getFieldMetadata($model, $fieldId);

        //====================================================================//
        // Types from Validator Constraints
        //====================================================================//
        $fieldType = self::getTypeFromValidator($metadata);
        if ($fieldType) {
            return $fieldType;
        }
        //====================================================================//
        // Types from Serializer Data Type
        //====================================================================//
        $fieldType = self::getTypeFromSerializer($metadata);
        if ($fieldType) {
            return $fieldType;
        }

        return null;
    }

    /**
     * Detect Model Class Short Name.
     *
     * @param class-string $model
     *
     * @throws Exception
     *
     * @return string
     */
    public static function getShortName(string $model): string
    {
        return (new \ReflectionClass($model))->getShortName();
    }

    /**
     * Detect if Field is Required.
     *
     * @param string           $model
     * @param string           $fieldId
     * @param PropertyMetadata $metadata
     *
     * @throws Exception
     *
     * @return bool
     */
    public static function isRequiredField(string $model, string $fieldId, PropertyMetadata $metadata = null): bool
    {
        $metadata = $metadata ?: self::getFieldMetadata($model, $fieldId);

        return is_array($metadata->groups) && in_array("Required", $metadata->groups, true);
    }

    /**
     * Detect if Field is Listed.
     *
     * @param string           $model
     * @param string           $fieldId
     * @param PropertyMetadata $metadata
     *
     * @throws Exception
     *
     * @return bool
     */
    public static function isListedField(string $model, string $fieldId, PropertyMetadata $metadata = null): bool
    {
        $metadata = $metadata ?: self::getFieldMetadata($model, $fieldId);

        return is_array($metadata->groups) && in_array("List", $metadata->groups, true);
    }

    /**
     * Detect if Field is Logged.
     *
     * @param string           $model
     * @param string           $fieldId
     * @param PropertyMetadata $metadata
     *
     * @throws Exception
     *
     * @return bool
     */
    public static function isNoTestField(string $model, string $fieldId, PropertyMetadata $metadata = null): bool
    {
        $metadata = $metadata ?: self::getFieldMetadata($model, $fieldId);

        return is_array($metadata->groups) && in_array("NoTest", $metadata->groups, true);
    }

    /**
     * Detect if Field is No Test.
     *
     * @param string           $model
     * @param string           $fieldId
     * @param PropertyMetadata $metadata
     *
     * @throws Exception
     *
     * @return bool
     */
    public static function isLoggedField(string $model, string $fieldId, PropertyMetadata $metadata = null): bool
    {
        $metadata = $metadata ?: self::getFieldMetadata($model, $fieldId);

        return is_array($metadata->groups) && in_array("Log", $metadata->groups, true);
    }

    /**
     * Detect if Field is Read.
     *
     * @param string                $model
     * @param string                $fieldId
     * @param null|PropertyMetadata $metadata
     *
     * @throws Exception
     *
     * @return bool
     */
    public static function isReadOnlyField(string $model, string $fieldId, PropertyMetadata $metadata = null): bool
    {
        $metadata = $metadata ?: self::getFieldMetadata($model, $fieldId);

        if ($metadata->readOnly) {
            return true;
        }

        return is_array($metadata->groups)
            && !in_array("Write", $metadata->groups, true)
            && in_array("Read", $metadata->groups, true);
    }

    /**
     * Detect if Field is Write.
     *
     * @param string                $model
     * @param string                $fieldId
     * @param null|PropertyMetadata $metadata
     *
     * @throws Exception
     *
     * @return bool
     */
    public static function isWriteOnlyField(string $model, string $fieldId, PropertyMetadata $metadata = null): bool
    {
        $metadata = $metadata ?: self::getFieldMetadata($model, $fieldId);

        return is_array($metadata->groups)
            && in_array("Write", $metadata->groups, true)
            && !in_array("Read", $metadata->groups, true);
    }

    /**
     * Check if field is Excluded from Api Generic Management
     *
     * @param string $model
     * @param string $fieldId
     *
     * @return bool
     */
    public static function isExcluded(string $model, string $fieldId): bool
    {
        if (!isset(self::$exclude[$model])) {
            return false;
        }

        return in_array($fieldId, self::$exclude[$model], true);
    }

    //====================================================================//
    // SUB RESOURCES FIELDS METADATA GETTERS
    //====================================================================//

    /**
     * Check if field is an API Sub-Resource
     *
     * @param string                $model
     * @param string                $fieldId
     * @param null|PropertyMetadata $metadata
     *
     * @throws Exception
     *
     * @return null|string
     */
    public static function isSubResource(string $model, string $fieldId, PropertyMetadata $metadata = null): ?string
    {
        $metadata = $metadata ?: self::getFieldMetadata($model, $fieldId);
        //====================================================================//
        // Detect Sub-Ressource Model
        $className = $metadata->type['name'] ?? null;
        if (!in_array($className, self::$protectedClasses, true) && class_exists($className)) {
            $modelMetadata = self::getModelMetadata($className);

            return !empty($modelMetadata->propertyMetadata) ? $metadata->name : null;
        }

        return null;
    }

    /**
     * Get SubResource Field Prefix
     *
     * @param string $fieldId
     *
     * @throws Exception
     *
     * @return null|string
     */
    public static function getSubResourcePrefix(string $fieldId): ?string
    {
        $exploded = explode("__", $fieldId);

        return (is_array($exploded) && (2 == count($exploded))) ? $exploded[0] : null;
    }

    /**
     * Check if field is Excluded from Api Generic Management
     *
     * @param string                $model
     * @param string                $fieldId
     * @param null|PropertyMetadata $metadata
     *
     * @throws Exception
     *
     * @return null|class-string
     */
    public static function getSubResourceModel(
        string $model,
        string $fieldId,
        PropertyMetadata $metadata = null
    ): ?string {
        $metadata = $metadata ?: self::getFieldMetadata($model, $fieldId);

        return $metadata->type['name'] ?? null;
    }

    /**
     * Get Sub-Resources Field ID
     *
     * @param string $fieldId
     *
     * @throws Exception
     *
     * @return null|string
     */
    public static function getSubResourceField(string $fieldId): ?string
    {
        $exploded = explode("__", $fieldId);

        return (is_array($exploded) && (2 == count($exploded))) ? $exploded[1] : null;
    }

    //====================================================================//
    // LIST FIELDS METADATA GETTERS
    //====================================================================//

    /**
     * Check if field is an API List-Resource
     *
     * @param string                $model
     * @param string                $fieldId
     * @param null|PropertyMetadata $metadata
     *
     * @throws Exception
     *
     * @return null|string
     */
    public static function isListResource(string $model, string $fieldId, PropertyMetadata $metadata = null): ?string
    {
        //====================================================================//
        // Safety Check - Field Exists on Model
        if ($model && !self::hasField($model, $fieldId)) {
            return null;
        }
        $metadata = $metadata ?: self::getFieldMetadata($model, $fieldId);
        //====================================================================//
        // Detect Sub-Ressource Model List
        if (in_array($metadata->type['name'] ?? null, array("array", "iterable"), true)
            && (!empty($metadata->type['params']))) {
            $className = $metadata->type['params'][0]['name'];
            if (!in_array($className, self::$protectedClasses, true) && class_exists($className)) {
                $modelMetadata = self::getModelMetadata($className);

                return !empty($modelMetadata->propertyMetadata) ? $metadata->name : null;
            }
        }

        return null;
    }

    /**
     * Check if field is Excluded from Api Generic Management
     *
     * @param string                $model
     * @param string                $fieldId
     * @param null|PropertyMetadata $metadata
     *
     * @throws Exception
     *
     * @return class-string
     */
    public static function getListResourceModel(
        string $model,
        string $fieldId,
        PropertyMetadata $metadata = null
    ): string {
        $metadata = $metadata ?: self::getFieldMetadata($model, $fieldId);
        $params = $metadata->type['params'] ?? null;
        if (!isset($params[0]['name'])) {
            throw new Exception("This should never happen");
        }

        return $params[0]['name'];
    }

    //====================================================================//
    // PRIVATE METHODS
    //====================================================================//

    /**
     * Get Serializer Metadata for a Model.
     *
     * @param string $model
     *
     * @throws Exception
     *
     * @return ClassMetadata
     */
    protected static function getModelMetadata(string $model): ClassMetadata
    {
        if (!isset(self::$meta[$model])) {
            $metadata = self::$hydrator->getMetadataForClass($model);
            if ($metadata) {
                self::$meta[$model] = $metadata;
            }
        }

        return self::$meta[$model];
    }

    /**
     * Get Serializer Metadata for a Field.
     *
     * @param string $model
     * @param string $fieldId
     *
     * @throws Exception
     *
     * @return PropertyMetadata
     */
    protected static function getFieldMetadata(string $model, string $fieldId): PropertyMetadata
    {
        $modelMetadata = self::getModelMetadata($model);
        if (!isset($modelMetadata->propertyMetadata[$fieldId])) {
            throw new Exception("Unable to find property on Serializer metadata for ".$fieldId);
        }
        if (!($modelMetadata->propertyMetadata[$fieldId] instanceof PropertyMetadata)) {
            throw new Exception("Wrong Serializer Metadata Class for ".$fieldId);
        }

        return $modelMetadata->propertyMetadata[$fieldId];
    }

    /**
     * Get Symfony Validator for a Model.
     *
     * @param string $model
     * @param string $fieldId
     *
     * @return Constraint[]
     */
    protected static function getModelConstraints(string $model, string $fieldId): array
    {
        if (!isset(self::$validators[$model])) {
            $validator = Validation::createValidatorBuilder()
                ->enableAnnotationMapping()
                ->getValidator()
                ->getMetadataFor($model);
            if ($validator instanceof Mapping\ClassMetadata) {
                self::$validators[$model] = $validator;
            }
        }
        if (!isset(self::$validators[$model]->properties[$fieldId])) {
            return array();
        }

        return self::$validators[$model]->properties[$fieldId]->getConstraints();
    }

    /**
     * Detect Field Type using Validator Information.
     *
     * @param PropertyMetadata $metadata
     *
     * @throws Exception
     *
     * @return null|string
     */
    private static function getTypeFromValidator(PropertyMetadata $metadata): ?string
    {
        $constraints = self::getModelConstraints($metadata->class, $metadata->name);
        foreach ($constraints as $constraint) {
            //====================================================================//
            // Detect Forced Type
            if ($constraint instanceof SPL\Type) {
                return $constraint->getValue();
            }
        }

        return null;
    }

    /**
     * Detect Field Type from Serializer Metadata.
     *
     * @param PropertyMetadata $metadata
     *
     * @throws Exception
     *
     * @return null|string
     */
    private static function getTypeFromSerializer(PropertyMetadata $metadata): ?string
    {
        switch ($metadata->type['name'] ?? null) {
            case 'string':
                return SPL_T_VARCHAR;
            case 'bool':
            case 'boolean':
                return SPL_T_BOOL;
            case 'double':
            case 'float':
                return SPL_T_DOUBLE;
            case 'int':
            case 'integer':
                return SPL_T_INT;
            case 'DateTime':
                return SPL_T_DATETIME;
        }

        return null;
    }
}
