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

use Httpful\Response;
use Splash\Core\SplashCore as Splash;
use Symfony\Component\HttpFoundation\Response as SfResponse;

/**
 * Detect API Requests Errors and Push to Splash Logger
 */
trait ErrorParserTrait
{
    /**
     * Analyze Api Response & Push Errors to Splash Log
     *
     * @param Response $response
     *
     * @return bool
     */
    protected static function isErrored(Response $response): bool
    {
        //====================================================================//
        // Check if Response has Errors
        if (!$response->hasErrors()) {
            return false;
        }
        //====================================================================//
        // Detect Http Response Code
        Splash::log()->err((string) $response->code." => ".SfResponse::$statusTexts[(int) $response->code]);
        Splash::log()->err("Url => ".$response->meta_data['url']);
        //====================================================================//
        // Extract Response Body
        self::extractResponseBody($response);
        //====================================================================//
        // Extract Response Headers
        self::extractResponseHeaders($response);

        return true;
    }

    /**
     * Extract Api Response Body & Push Errors to Splash Log
     *
     * @param Response $response
     *
     * @return void
     */
    private static function extractResponseBody(Response $response): void
    {
        //====================================================================//
        // Try to decode response body as Json
        $decoded = json_decode($response->raw_body, true);
        //====================================================================//
        // Unable to decode => Store Raw Response
        if (!is_array($decoded)) {
            Splash::log()->err(html_entity_decode($response->raw_body));

            return;
        }
        //====================================================================//
        // Store Decoded Response
        foreach ($decoded as $key => $value) {
            if (is_scalar($key) && is_scalar($value)) {
                Splash::log()->err((string) $key." -> ".(string) $value);
            }
        }
    }

    /**
     * Extract Api Response Headers & Push Errors to Splash Log
     *
     * @param Response $response
     *
     * @return void
     */
    private static function extractResponseHeaders(Response $response): void
    {
        foreach ($response->headers->toArray() as $key => $value) {
            if (is_scalar($key) && is_scalar($value)) {
                Splash::log()->war((string) $key." -> ".(string) $value);
            }
        }
    }
}
