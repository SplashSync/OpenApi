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

namespace Splash\OpenApi\Bundle\Models\Api;

use JMS\Serializer\Annotation as JMS;
use Splash\OpenApi\Validator as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Item Object Model with minimal Fields.
 */
class ListItem extends Item
{
    /**
     * Item Price
     *
     * @SPL\Type("price")
     */
    #[
        Assert\Type("array"),
        JMS\SerializedName("price"),
        JMS\Type("array")
    ]
    public ?array $price = null;
}
