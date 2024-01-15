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

use DateTime;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Item Object Model with minimal Fields.
 */
class Item
{
    /**
     * Item Name.
     */
    #[
        Assert\NotNull(),
        Assert\Type("string"),
        JMS\SerializedName("name"),
        JMS\Type("string"),
        JMS\Groups(array("Read", "Write", "Required")),
    ]
    public string $name;

    /**
     * Item Bool Field
     */
    #[
        Assert\Type("bool"),
        JMS\SerializedName("bool"),
        JMS\Type("bool"),
    ]
    public ?bool $bool = false;

    /**
     * Item Integer Field
     */
    #[
        Assert\Type("int"),
        JMS\SerializedName("int"),
        JMS\Type("int"),
    ]
    public ?int $int = null;

    /**
     * Item Datetime Field
     */
    #[
        Assert\Type(DateTime::class),
        JMS\SerializedName("datetime"),
        JMS\Type(DateTime::class),
    ]
    public ?DateTime $datetime = null;
}
