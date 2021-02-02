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

namespace Splash\OpenApi\Action\JsonHal;

use Splash\Core\SplashCore as Splash;
use Splash\OpenApi\Models\Action\AbstractAction;

/**
 * Read Objects List form Remote Server
 */
abstract class AbstractJsonHalAction extends AbstractAction
{
    /**
     * Index of Response Data
     *
     * @var string
     */
    public static $embeddedIndex = "_embedded";

    /**
     * Possible indexes for Collection Totals
     *
     * @var string[]
     */
    public static $totalIndexes = array(
        "total", "totalItems", "total_items"
    );

    /**
     * Extract First Item from Raw Json Hal Data.
     *
     * @param array $rawResponse
     *
     * @return null|array
     */
    protected function getFirstItem(array $rawResponse): ?array
    {
        //====================================================================//
        // Safety Check => Data is at Expected Index
        if (!isset($rawResponse[self::$embeddedIndex])) {
            Splash::log()->errTrace("Malformed or Empty Json Hal Response");

            return array();
        }
        //====================================================================//
        // Extract Data at Expected Index
        $embeddedData = $rawResponse[self::$embeddedIndex];
        if (empty($embeddedData) || !is_array($firstItem = array_shift($embeddedData))) {
            Splash::log()->errTrace("Json Hal Response has no Contents");

            return null;
        }

        return $firstItem;
    }
}
