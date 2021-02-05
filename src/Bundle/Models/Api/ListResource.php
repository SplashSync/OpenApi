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

namespace Splash\OpenApi\Bundle\Models\Api;

use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Basic Object Model with List-Resource Fields.
 */
class ListResource
{
    /**
     * Unique identifier .
     *
     * @var string
     *
     * @SerializedName("id")
     * @Assert\NotNull()
     * @Assert\Type("string")
     * @Type("string")
     * @Groups ({"Read", "Write", "List"})
     */
    public $id;

    /**
     * Object Name.
     *
     * @var string
     * @SerializedName("name")
     * @Assert\NotNull()
     * @Assert\Type("string")
     * @Type("string")
     * @Groups ({"Read", "Write", "List", "Required"})
     */
    public $name;

    /**
     * Just a Item Object.
     *
     * @var null|ListItem[]
     * @SerializedName("items")
     * @Assert\Type("array<Splash\OpenApi\Bundle\Models\Api\ListItem>")
     * @Type("iterable<Splash\OpenApi\Bundle\Models\Api\ListItem>")
     */
    public $items;
}
