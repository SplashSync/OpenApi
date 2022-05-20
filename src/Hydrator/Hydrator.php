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

namespace Splash\OpenApi\Hydrator;

use Closure;
use Exception;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Metadata\MetadataFactory;

/**
 * OpenApi Objects Hydrator
 */
class Hydrator
{
    /**
     * @var Serializer
     */
    protected Serializer $serializer;

    /**
     * @var MetadataFactory
     */
    protected MetadataFactory $metadataFactory;

    /**
     * Hydrator constructor.
     *
     * @param string $cacheDir
     */
    public function __construct(string $cacheDir)
    {
        $builder = SerializerBuilder::create()
            ->setDocBlockTypeResolver(true)
            ->addDefaultHandlers()
            ->setCacheDir($cacheDir)
        ;
        $this->serializer = $builder->build();
    }

    /**
     * Extracts data from an object.
     *
     * @param object $object
     *
     * @return array
     */
    public function extract(object $object): array
    {
        return $this->serializer->toArray($object, $this->getWriteContext());
    }

    /**
     * Extracts required data from an object.
     *
     * @param object $object
     *
     * @return array
     */
    public function extractRequired(object $object): array
    {
        return $this->serializer->toArray($object, $this->getRequiredContext());
    }

    /**
     * Extract many data from an object list.
     *
     * @param array $data
     *
     * @return array<int, array>
     */
    public function extractMany(array $data): array
    {
        $objects = array();
        foreach ($data as $item) {
            $objects[] = $this->serializer->toArray($item, $this->getListContext());
        }

        return $objects;
    }

    /**
     * Hydrate object from array.
     *
     * @param array  $data
     * @param string $type
     *
     * @return object
     */
    public function hydrate(array $data, string $type): object
    {
        /** @phpstan-ignore-next-line  */
        return $this->serializer->fromArray($data, $type);
    }

    /**
     * Hydrate many object from array.
     *
     * @param array  $data
     * @param string $type
     *
     * @return object[]
     */
    public function hydrateMany(array $data, string $type): array
    {
        $objects = array();
        foreach ($data as $item) {
            $objects[] = $this->hydrate((array) $item, $type);
        }

        return $objects;
    }

    /**
     * Get Serializer Metadata for a Class
     *
     * @param string $className
     *
     * @throws Exception
     *
     * @return null|ClassMetadata
     */
    public function getMetadataForClass(string $className): ?ClassMetadata
    {
        if (!isset($this->metadataFactory)) {
            //====================================================================//
            // Force access to Serializer Metadata
            $closure = Closure::bind(function (Serializer $serializer) {
                return $serializer->factory;
            }, null, Serializer::class);
            $factory = $closure($this->serializer);
            if (!$factory instanceof MetadataFactory) {
                throw new Exception("Unable to connect to Serializer Metadata Factory");
            }
            $this->metadataFactory = $factory;
        }
        $metadata = $this->metadataFactory->getMetadataForClass($className);

        return ($metadata instanceof ClassMetadata) ? $metadata : null;
    }

    /**
     * Creates a WRITE serializer context
     *
     * @return SerializationContext
     */
    private function getWriteContext(): SerializationContext
    {
        return SerializationContext::create()
            ->setSerializeNull(true)
            ->setGroups(array('Default', 'Write'));
    }

    /**
     * Creates a REQUIRED serializer context
     *
     * @return SerializationContext
     */
    private function getRequiredContext(): SerializationContext
    {
        return SerializationContext::create()
            ->setSerializeNull(true)
            ->setGroups(array('Required'));
    }

    /**
     * Creates a LIST serializer context
     *
     * @return SerializationContext
     */
    private function getListContext(): SerializationContext
    {
        return SerializationContext::create()
            ->setSerializeNull(true)
            ->setGroups(array('List'));
    }
}
