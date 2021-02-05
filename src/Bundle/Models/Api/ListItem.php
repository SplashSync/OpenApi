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

use DateTime;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use Splash\OpenApi\Validator as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Item Object Model with minimal Fields.
 */
class ListItem
{
    /**
     * Name.
     *
     * @var string
     * @SerializedName("name")
     * @Assert\NotNull()
     * @Assert\Type("string")
     * @Type("string")
     * @Groups ({"Read", "Write", "Required"})
     */
    public $name;

    /**
     * @var null|bool
     * @SerializedName("bool")
     * @Assert\Type("bool")
     * @Type("bool")
     */
    public $bool = false;

    /**
     * @var null|int
     * @SerializedName("int")
     * @Assert\Type("int")
     * @Type("int")
     */
    public $int;

    /**
     * @var null|Datetime
     *
     * @SerializedName("datetime")
     * @Assert\Type("DateTime")
     * @Type("DateTime")
     */
    public $datetime;

    /**
     * @var null|array
     *
     * @SerializedName("price")
     * @Type("array")
     * @SPL\Type("price")
     */
    public $price;
}
