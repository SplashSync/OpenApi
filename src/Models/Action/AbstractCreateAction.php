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

namespace Splash\OpenApi\Models\Action;

use Splash\OpenApi\ApiResponse;

/**
 * Base Api Create Action
 */
abstract class AbstractCreateAction extends AbstractAction
{
    /**
     * @var array<string, bool|string>
     */
    protected $options = array(
        "requiredOnly" => true,     // Only Write Required Fields
    );

    /**
     * Execute Objects Create Action.
     *
     * @param object    $object
     * @param null|bool $requiredOnly
     *
     * @return ApiResponse
     */
    public function execute(object $object, bool $requiredOnly = null): ApiResponse
    {
        //====================================================================//
        // Prepare Data
        $objectData = $this->extractData($object, $requiredOnly);
        if (!$objectData) {
            return new ApiResponse($this->visitor);
        }
        //====================================================================//
        // Execute Get Request
        $rawResponse = $this->visitor->getConnexion()->post(
            $this->visitor->getCollectionUri(),
            $objectData
        );
        if (null === $rawResponse) {
            return new ApiResponse($this->visitor);
        }

        return new ApiResponse($this->visitor, true, $this->hydrateData($rawResponse));
    }

    /**
     * Extract Objects Write Data.
     *
     * @param object    $object
     * @param null|bool $requiredOnly
     *
     * @return array
     */
    public function extractData(object $object, bool $requiredOnly = null): array
    {
        //====================================================================//
        // Extract Object Data
        return ($requiredOnly ?: $this->options["requiredOnly"])
            ? $this->visitor->getHydrator()->extractRequired($object)
            : $this->visitor->getHydrator()->extract($object)
        ;
    }

    /**
     * Hydrate Objects Array Data.
     *
     * @param array $rawResponse
     *
     * @return object
     */
    public function hydrateData(array $rawResponse): object
    {
        //====================================================================//
        // Extract Object Data
        return $this->visitor->getHydrator()->hydrate($rawResponse, $this->visitor->getModel());
    }
}
