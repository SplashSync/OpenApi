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

namespace Splash\OpenApi\Action\JsonHal;

use Splash\OpenApi\Models\Action\AbstractListAction;

/**
 * Read Objects List form Remote Server
 */
class ListAction extends AbstractListAction
{
    use JsonHalOptionsTrait;

    /**
     * Json Hal Options
     *
     * @var array<string, array|string>
     */
    protected $options;

    /**
     * {@inheritDoc}
     */
    public function extractData(array $rawResponse): array
    {
        //====================================================================//
        // Extract First Item from Raw Json Hal Data.
        $firstItem = $this->getFirstItem($rawResponse);
        if (empty($firstItem)) {
            return array();
        }
        //====================================================================//
        // Extract List Results
        return parent::extractData($firstItem);
    }

    /**
     * {@inheritDoc}
     */
    protected function extractTotal(array $rawResponse, array $params = null): int
    {
        //====================================================================//
        // Extract List Total
        if (is_array($this->options["totalKey"])) {
            foreach ($this->options["totalKey"] as $index) {
                if (isset($rawResponse[$index])) {
                    return (int) $rawResponse[$index];
                }
            }
        }
        //====================================================================//
        // Extract List Total
        if (is_string($this->options["totalKey"])) {
            return (int) $rawResponse[$this->options["totalKey"]];
        }

        return 0;
    }
}
