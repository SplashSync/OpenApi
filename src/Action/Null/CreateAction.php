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

namespace Splash\OpenApi\Action\Null;

use Splash\OpenApi\ApiResponse;
use Splash\OpenApi\Models\Action\AbstractCreateAction;

/**
 * Create Objects Data form Remote Server with POST Method
 */
class CreateAction extends AbstractCreateAction
{
    /**
     * {@inheritDoc}
     */
    public function execute(object $object, bool $requiredOnly = null): ApiResponse
    {
        return new ApiResponse($this->visitor, true, null);
    }
}
