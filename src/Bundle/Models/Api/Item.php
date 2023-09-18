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
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Item Object Model with minimal Fields.
 */
class Item
{
    /**
     * Name.
     *
     * @var string
     *
     * @SerializedName("name")
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("string")
     *
     * @Type("string")
     *
     * @Groups ({"Read", "Write", "Required"})
     */
    public $name;

    /**
     * @var null|bool
     *
     * @SerializedName("bool")
     *
     * @Assert\Type("bool")
     *
     * @Type("bool")
     */
    public $bool;

    /**
     * @var null|int
     *
     * @SerializedName("int")
     *
     * @Assert\Type("int")
     *
     * @Type("int")
     */
    public $int;

    /**
     * @var null|Datetime
     *
     * @SerializedName("datetime")
     *
     * @Assert\Type("DateTime")
     *
     * @Type("DateTime")
     */
    public $datetime;

    //    /**
    //     * @var null|array
    //     *
    //     * @ORM\Column(type="array", nullable=true)
    //     */
    //    protected $price;
    //
    //    /**
    //     * @param array|null $price
    //     *
    //     * @return self
    //     */
    //    public function setPrice(?array $price): self
    //    {
    //        $this->price = $price;
    //
    //        return $this;
    //    }
    //
    //    /**
    //     * @return array
    //     */
    //    public function getPrice(): array
    //    {
    //        if (empty($this->price)) {
    //            //====================================================================//
    //            // Init Splash Framework
    //            Splash::setLocalClass(new Local());
    //            //====================================================================//
    //            // Encode Splash Price Array
    //            $this->price = PricesHelper::encode((float) rand(10, 100), 20.0, null, "EUR");
    //        }
    //
    //        return $this->price;
    //    }
}
