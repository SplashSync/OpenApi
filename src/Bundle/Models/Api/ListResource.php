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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Basic Object Model with List-Resource Fields.
 */
class ListResource
{
    /**
     * Unique Identifier.
     */
    #[
        Assert\NotNull(),
        Assert\Type("string"),
        JMS\SerializedName("id"),
        JMS\Groups(array("Read", "Write", "List")),
        JMS\Type("string"),
    ]
    public string $id;

    /**
     * Object Name.
     */
    #[
        Assert\NotNull(),
        Assert\Type("string"),
        JMS\SerializedName("name"),
        JMS\Type("string"),
        JMS\Groups(array("Read", "Write", "List", "Required")),
    ]
    public string $name;

    /**
     * Just a List of Item Objects.
     */
    #[
        Assert\Type("array<".ListItem::class.">"),
        JMS\SerializedName("items"),
        JMS\Type("iterable<".ListItem::class.">"),
    ]
    public array $items = array();
}
