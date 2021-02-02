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

namespace Splash\OpenApi\Models\Connexion;

use Httpful\Request;
use Httpful\Response;

/**
 * Httpful Connexion Helper
 */
interface ConnexionInterface
{
    //====================================================================//
    // API Requests
    //====================================================================//

    /**
     * Perform a GET Request
     *
     * @param null|string           $path Resource Path
     * @param array<string, string> $data Request Query Data
     *
     * @return null|array
     */
    public function get(?string $path, array $data = null): ?array;

    /**
     * Perform a POST Request
     *
     * @param string                $path Resource Path
     * @param array<string, string> $data Request Query Data
     *
     * @return null|array
     */
    public function post(string $path, array $data): ?array;

    /**
     * Perform a PUT Request
     *
     * @param string                $path Resource Path
     * @param array<string, string> $data Request Query Data
     *
     * @return null|array
     */
    public function put(string $path, array $data): ?array;

    /**
     * Perform a PATCH Request
     *
     * @param string                $path Resource Path
     * @param array<string, string> $data Request Query Data
     *
     * @return null|array
     */
    public function patch(string $path, array $data = null): ?array;

    /**
     * Perform a DELETE Request
     *
     * @param string $path Resource Path
     *
     * @return null|array
     */
    public function delete(string $path): ?array;

    //====================================================================//
    // Various Methods
    //====================================================================//

    /**
     * Get Connexion Endpoint Url
     *
     * @return string
     */
    public function getEndPoint(): string;

    /**
     * Get Httpful Request Template
     *
     * @return Request
     */
    public function getTemplate(): Request;

    /**
     * @return null|Response
     */
    public function getLastResponse(): ?Response;
}
