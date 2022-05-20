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

namespace Splash\OpenApi\Action\Rejected;

use Splash\Client\Splash;
use Splash\OpenApi\ApiResponse;
use Splash\OpenApi\Models\Action\AbstractDeleteAction;

/**
 * Delete Objects Data form Remote Server
 */
class DeleteAction extends AbstractDeleteAction
{
    /**
     * {@inheritDoc}
     */
    public function execute($objectOrId): ApiResponse
    {
        Splash::log()->errTrace("API Delete action is not allowed for ".$this->visitor->getModel());

        return new ApiResponse($this->visitor, false, null);
    }
}
