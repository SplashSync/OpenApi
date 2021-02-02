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

use Splash\OpenApi\Models\OpenApiAwareInterface;

/**
 * Base Api Action
 */
abstract class AbstractAction
{
    /**
     * @var OpenApiAwareInterface
     */
    protected $api;

    /**
     * @var bool
     */
    private $isSuccessful = false;

    /**
     * @var mixed
     */
    private $results;

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->isSuccessful;
    }

    /**
     * @return mixed
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @return self
     */
    protected function setSuccessful(): self
    {
        $this->isSuccessful = true;

        return $this;
    }

    /**
     * @return self
     */
    protected function setErrored(): self
    {
        $this->isSuccessful = false;

        return $this;
    }

    /**
     * @param mixed $results
     *
     * @return self
     */
    protected function setResults($results): self
    {
        $this->results = $results;

        return $this;
    }
}
