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

namespace Splash\OpenApi\Models\Connexion;

use Httpful\Exception\ConnectionErrorException;
use Httpful\Mime;
use Httpful\Request;
use Httpful\Response;
use Splash\Core\SplashCore as Splash;

/**
 * Base OpenApi Httpful Connexion Helper
 */
abstract class AbstractConnexion implements ConnexionInterface
{
    use ErrorParserTrait;

    /**
     * @var int
     */
    const TIMEOUT = 30;

    /**
     * @var string
     */
    const MIME_TYPE_SEND = Mime::JSON;

    /**
     * @var string
     */
    const MIME_TYPE_EXPECT = Mime::JSON;

    /**
     * Api Endpoint
     *
     * @var string
     */
    private string $endPoint;

    /**
     * Request Template
     *
     * @var Request
     */
    private Request $template;

    /**
     * Last Api Response
     *
     * @var null|Response
     */
    private ?Response $lastResponse;

    /**
     * Api Endpoint
     *
     * @var string
     */
    private string $patchMime = "application/merge-patch+json";

    //====================================================================//
    // Configuration
    //====================================================================//

    /**
     * Construct Http OpenApi Connexion
     *
     * @param string        $url
     * @param null|array    $headers
     * @param null|callable $callback
     */
    public function __construct(string $url, array $headers = null, callable $callback = null)
    {
        //====================================================================//
        // Configure API Endpoint
        $this->endPoint = $url;
        //====================================================================//
        // Create API Template Request
        $this->template = Request::init()
            ->sends(static::MIME_TYPE_SEND)
            ->expects(static::MIME_TYPE_EXPECT)
            ->autoParse(false)
            ->timeout(self::TIMEOUT)
        ;
        //====================================================================//
        // Configure Static Headers
        if (!empty($headers)) {
            $this->template->addHeaders($headers);
        }
        //====================================================================//
        // Complete Configuration
        if (is_callable($callback)) {
            call_user_func($callback, $this->template);
        }
        //====================================================================//
        // Set it as a template
        Request::ini($this->template);
    }

    /**
     * Clone Connexion also Clone Request Template
     */
    public function __clone(): void
    {
        $this->template = clone $this->template;
    }

    //====================================================================//
    // API Requests
    //====================================================================//

    /**
     * {@inheritDoc}
     */
    public function get(?string $path, array $data = null) : ?array
    {
        //====================================================================//
        // Restore Connexion Template
        Request::ini($this->template);
        //====================================================================//
        // Prepare Uri
        $uri = $this->endPoint.$path;
        if (!empty($data)) {
            $uri .= "?".http_build_query($data);
        }

        //====================================================================//
        // Perform Request
        try {
            $this->lastResponse = Request::get($uri)->send();
        } catch (ConnectionErrorException $ex) {
            Splash::log()->err($ex->getMessage());

            return null;
        }

        //====================================================================//
        // Catch Errors inResponse
        return self::isErrored($this->lastResponse)
            ? null
            : (array) json_decode($this->lastResponse->body, true)
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function getRaw(?string $path, array $data = null, bool $absoluteUrl = false) : ?string
    {
        //====================================================================//
        // Restore Connexion Template
        Request::ini($this->template);
        //====================================================================//
        // Prepare Uri
        $uri = ($absoluteUrl && $path) ? $path : $this->endPoint.$path;
        if (!empty($data)) {
            $uri .= "?".http_build_query($data);
        }

        //====================================================================//
        // Perform Request
        try {
            $this->lastResponse = Request::get($uri)
                ->sendsPlain()
                ->expectsText()
                ->send();
        } catch (ConnectionErrorException $ex) {
            Splash::log()->err($ex->getMessage());

            return null;
        }

        //====================================================================//
        // Catch Errors inResponse
        return self::isErrored($this->lastResponse) ? null : $this->lastResponse->body;
    }

    /**
     * {@inheritDoc}
     */
    public function post(string $path, array $data): ?array
    {
        //====================================================================//
        // Restore Connexion Template
        Request::ini($this->template);

        //====================================================================//
        // Perform Request
        try {
            $this->lastResponse = Request::post($this->endPoint.$path)
                ->body(json_encode($data))
                ->send();
        } catch (ConnectionErrorException $ex) {
            Splash::log()->err($ex->getMessage());

            return null;
        }

        //====================================================================//
        // Catch Errors in Response
        return self::isErrored($this->lastResponse)
            ? null
            : (array) json_decode($this->lastResponse->body, true)
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function put(string $path, array $data): ?array
    {
        //====================================================================//
        // Restore Connexion Template
        Request::ini($this->template);

        //====================================================================//
        // Perform Request
        try {
            $this->lastResponse = Request::put($this->endPoint.$path)
                ->body(json_encode($data))
                ->send();
        } catch (ConnectionErrorException $ex) {
            Splash::log()->err($ex->getMessage());

            return null;
        }

        //====================================================================//
        // Catch Errors in Response
        return self::isErrored($this->lastResponse)
            ? null
            : (array) json_decode($this->lastResponse->body, true)
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function patch(string $path, array $data = null): ?array
    {
        //====================================================================//
        // Restore Connexion Template
        Request::ini($this->template);

        //====================================================================//
        // Perform Request
        try {
            $this->lastResponse = Request::patch($this->endPoint.$path)
                ->body(json_encode($data))
                ->sends($this->patchMime ?: Mime::JSON)
                ->send();
        } catch (ConnectionErrorException $ex) {
            Splash::log()->err($ex->getMessage());

            return null;
        }

        //====================================================================//
        // Catch Errors in Response
        return self::isErrored($this->lastResponse)
            ? null
            : (array) json_decode($this->lastResponse->body, true)
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function delete(string $path): ?array
    {
        //====================================================================//
        // Restore Connexion Template
        Request::ini($this->template);

        //====================================================================//
        // Perform Request
        try {
            $this->lastResponse = Request::delete($this->endPoint.$path)
                ->send();
        } catch (ConnectionErrorException $ex) {
            Splash::log()->err($ex->getMessage());

            return null;
        }

        //====================================================================//
        // Catch Errors inResponse
        return self::isErrored($this->lastResponse) ? null : (array) json_decode($this->lastResponse->body, true);
    }

    //====================================================================//
    // Various Methods
    //====================================================================//

    /**
     * Force Patch Actions Mime Type
     *
     * @param string $mimeType
     *
     * @return $this
     */
    public function setPatchMimeType(string $mimeType): self
    {
        $this->patchMime = $mimeType;

        return $this;
    }

    /**
     * Get Connexion Endpoint Url
     *
     * @return string
     */
    public function getEndPoint(): string
    {
        return $this->endPoint;
    }

    /**
     * Get Httpful Request Template
     *
     * @return Request
     */
    public function getTemplate(): Request
    {
        return $this->template;
    }

    /**
     * @return null|Response
     */
    public function getLastResponse(): ?Response
    {
        return $this->lastResponse ?? null;
    }
}
