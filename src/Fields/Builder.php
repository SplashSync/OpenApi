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

use Exception;
use JMS\Serializer\Metadata\PropertyMetadata as Metadata;
use Splash\Components\FieldsFactory;
use Splash\OpenApi\Validator as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Splash Api Fields Builder.
 */
class Builder extends Descriptor
{
    /**
     * Build Fields Definitions for a Model.
     *
     * @param FieldsFactory $factory
     * @param string        $model
     *
     * @throws Exception
     *
     * @return void
     */
    public static function buildModelFields(FieldsFactory $factory, string $model): void
    {
        //====================================================================//
        // Walk on Available Properties
        /** @var Metadata $serializerMetadata */
        foreach (self::getModelMetadata($model)->propertyMetadata as $serializerMetadata) {
            self::addField($factory, $serializerMetadata);
        }
    }

    /**
     * Add a Field to Factory with Metadata Detections.
     *
     * @param FieldsFactory $factory
     * @param Metadata      $metadata
     * @param null|string   $prefix
     * @param bool          $list
     *
     * @throws Exception
     */
    private static function addField(
        FieldsFactory $factory,
        Metadata $metadata,
        string $prefix = null,
        bool $list = false
    ): void {
        //====================================================================//
        // Filter ID Fields
        if (self::isExcluded($metadata->class, $metadata->name)) {
            return;
        }
        //====================================================================//
        // Sub Resource Fields Types
        if (self::setupSubResourceMetadata($factory, $metadata)) {
            return;
        }
        //====================================================================//
        // List Resource Fields Types
        if (self::setupListResourceMetadata($factory, $metadata)) {
            return;
        }
        //====================================================================//
        // Detect Field Type
        $fieldType = self::getFieldType($metadata->class, $metadata->name, $metadata);
        if (!$fieldType) {
            return;
        }
        //====================================================================//
        // Declare new Field
        $factory->create($fieldType)
            ->Identifier(self::getFieldId($metadata->name, $prefix, $list))
            ->Name(ucfirst($metadata->name))
            ->isRequired(self::isRequiredField("", "", $metadata))
            ->isReadOnly(self::isReadOnlyField("", "", $metadata))
            ->isWriteOnly(self::isWriteOnlyField("", "", $metadata))
            ->isListed(is_null($prefix) && self::isListedField("", "", $metadata))
            ->isLogged(self::isLoggedField("", "", $metadata))
        ;
        if ($prefix) {
            $list ? $factory->inList($prefix) : $factory->group(ucwords($prefix));
        }
        if (self::isNoTestField("", "", $metadata)) {
            $factory->isNotTested();
        }
        //====================================================================//
        // Detect Field Metadata Using Symfony Validator Annotations.
        self::setupValidatorMetadata($factory, $metadata);
    }

    /**
     * Build  Field Metadata Using Symfony Validator Annotations.
     *
     * @param string      $name
     * @param null|string $prefix
     * @param bool        $list
     *
     * @return string
     */
    private static function getFieldId(string $name, ?string $prefix, bool $list): string
    {
        if (empty($prefix) || $list) {
            return $name;
        }

        return $prefix."__".$name;
    }

    /**
     * Detect Field Metadata Using Symfony Validator Annotations.
     *
     * @param FieldsFactory $factory
     * @param Metadata      $metadata
     *
     * @throws Exception
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private static function setupValidatorMetadata(FieldsFactory $factory, Metadata $metadata): void
    {
        $constraints = self::getModelConstraints($metadata->class, $metadata->name);
        //====================================================================//
        // Walk on Defined Constraints
        foreach ($constraints as $constraint) {
            //====================================================================//
            // Detect Choices
            if ($constraint instanceof Assert\Choice) {
                $factory->addChoices($constraint->choices);
            }
            //====================================================================//
            // Detect Choices
            if (($constraint instanceof SPL\Microdata) && $constraint->isValid()) {
                $factory->microData($constraint->getItemType(), $constraint->getItemProp());
            }
            //====================================================================//
            // Detect Not Tested
            if (($constraint instanceof SPL\NoTested)) {
                $factory->isNotTested();
            }
            //====================================================================//
            // Detect Sync Mode Prefer
            if (($constraint instanceof SPL\Prefer)) {
                $constraint->apply($factory);
            }
            //====================================================================//
            // Detect To Log Fields
            if (($constraint instanceof SPL\Logged)) {
                $factory->isLogged();
            }
            //====================================================================//
            // Detect Field Group
            if (($constraint instanceof SPL\Group) && !empty($constraint->getValue())) {
                $factory->group((string) $constraint->getValue());
            }
        }
    }

    /**
     * Detect SubResource Field Metadata & Setup Fields.
     *
     * @param FieldsFactory $factory
     * @param Metadata      $metadata
     *
     * @throws Exception
     *
     * @return bool
     */
    private static function setupSubResourceMetadata(FieldsFactory $factory, Metadata $metadata): bool
    {
        $prefix = self::isSubResource("", "", $metadata);
        if (!$prefix) {
            return false;
        }
        $model = (string) self::getSubResourceModel("", "", $metadata);
        /** @var Metadata $serializerMetadata */
        foreach (self::getModelMetadata($model)->propertyMetadata as $serializerMetadata) {
            //====================================================================//
            // Override Sub-Ressource via Parent
            if(self::isReadOnlyField("", "", $metadata)) {
                $overrideMetadata = clone $serializerMetadata;
                $overrideMetadata->readOnly = true;
                self::addField($factory, $overrideMetadata, $prefix);

                continue;
            }

            self::addField($factory, $serializerMetadata, $prefix);
        }

        return true;
    }

    /**
     * Detect SubResource Field Metadata & Setup Fields.
     *
     * @param FieldsFactory $factory
     * @param Metadata      $metadata
     *
     * @throws Exception
     *
     * @return bool
     */
    private static function setupListResourceMetadata(FieldsFactory $factory, Metadata $metadata): bool
    {
        $listResourcePrefix = self::isListResource("", "", $metadata);
        if (!$listResourcePrefix) {
            return false;
        }
        $model = (string) self::getListResourceModel("", "", $metadata);
        /** @var Metadata $serializerMetadata */
        foreach (self::getModelMetadata($model)->propertyMetadata as $serializerMetadata) {
            self::addField($factory, $serializerMetadata, $listResourcePrefix, true);
        }

        return true;
    }
}
