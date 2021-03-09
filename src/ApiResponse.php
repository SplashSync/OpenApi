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

namespace Splash\OpenApi;

use Httpful\Response;
use Splash\OpenApi\Visitor\AbstractVisitor;

/**
 * Open Api Action Response
 */
final class ApiResponse
{
    /**
     * @var bool
     */
    private $isSuccess;

    /**
     * @var mixed
     */
    private $results;

    /**
     * @var array
     */
    private $metadata;

    /**
     * @var null|Response
     */
    private $response;

    /**
     * Create New Action.
     *
     * @param AbstractVisitor $visitor
     * @param bool            $isSuccess
     * @param null|mixed      $results
     * @param null|array      $meta
     */
    public function __construct(AbstractVisitor $visitor, bool $isSuccess = false, $results = null, array $meta = null)
    {
        $this->isSuccess = $isSuccess;
        $this->results = $results;
        $this->metadata = $meta ?: array();
        $this->response = $visitor->getLastResponse();
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    /**
     * @return mixed
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Get All Request Metadata
     *
     * @return array
     */
    public function getMetas(): array
    {
        return $this->metadata;
    }

    /**
     * Get Last Api Response
     *
     * @return null|Response
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }

    /**
     * Get List Request Current Count
     *
     * @return int
     */
    public function getListCount(): int
    {
        return isset($this->metadata["current"]) ? (int) $this->metadata["current"] : 0;
    }

    /**
     * Get List Request Total Count
     *
     * @return int
     */
    public function getListTotal(): int
    {
        return isset($this->metadata["total"]) ? (int) $this->metadata["total"] : 0;
    }
}
