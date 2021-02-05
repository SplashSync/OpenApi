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
 * Api Model for Simple Object: Basic Fields.
 *
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class Simple
{
    /**
     * Unique identifier representing a Shipment.
     *
     * @var string
     * @SerializedName("id")
     * @Assert\NotNull()
     * @Assert\Type("string")
     * @Type("string")
     * @Groups ({"Read", "Write", "List"})
     */
    public $id;

    /**
     * Client's firstname.
     *
     * @var string
     * @SerializedName("firstname")
     * @Assert\NotNull()
     * @Assert\Type("string")
     * @Type("string")
     * @Groups ({"Read", "Write", "List", "Required"})
     * @SPL\Description("This is First Name")
     */
    public $firstname;

    /**
     * Client's lastname.
     *
     * @var string
     * @SerializedName("lastname")
     * @Assert\NotNull()
     * @Assert\Type("string")
     * @Type("string")
     * @Groups ({"Read", "Write", "List", "Required"})
     * @SPL\Description("This is Last Name")
     */
    public $lastname;

    /**
     * Client's email.
     *
     * @var null|string
     * @SerializedName("email")
     * @Assert\Type("string")
     * @Type("string")
     * @SPL\Type("email")
     */
    public $email;

    /**
     * Client's phone.
     *
     * @var null|string
     * @SerializedName("phone")
     * @Assert\Type("string")
     * @Type("string")
     * @SPL\Type("phone")
     */
    public $phone;

    /**
     * Optional second line of the address street.
     *
     * @var null|bool
     * @SerializedName("bool")
     * @Assert\Type("bool")
     * @Type("bool")
     */
    public $bool;

    /**
     * @var null|int
     *
     * @SerializedName("int")
     * @Assert\Type("int")
     * @Type("int")
     */
    public $int;

    /**
     * Client's website.
     *
     * @var null|string
     * @SerializedName("website")
     * @Assert\Type("string")
     * @Type("string")
     * @SPL\Type("url")
     */
    public $website;

    /**
     * First line of the address street.
     *
     * @var string
     * @SerializedName("language")
     * @Assert\NotNull()
     * @Assert\Type("string")
     * @Type("string")
     * @SPL\Type("lang")
     */
    public $language;

    /**
     * Currency.
     *
     * @var string
     * @SerializedName("currency")
     * @Assert\NotNull()
     * @Assert\Type("string")
     * @Type("string")
     * @SPL\Type("currency")
     */
    public $currency;

    /**
     * Address country as ISO_3166-1 alpha-3.
     *
     * @var string
     * @SerializedName("countryId")
     * @Assert\NotNull()
     * @Assert\Type("string")
     * @Type("string")
     * @SPL\Type("country")
     */
    public $countryId;

    /**
     * @var Datetime
     *
     * @SerializedName("date")
     * @Type("DateTime")
     * @SPL\Type("date")
     */
    public $date;

    /**
     * @var Datetime
     *
     * @SerializedName("datetime")
     * @Type("DateTime")
     * @SPL\Type("datetime")
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

    /**
     * @var null|array
     *
     * @SerializedName("image")
     * @Type("array")
     * @SPL\Type("image")
     * @Groups ({"Read"})
     */
    public $image;

    /**
     * @var null|array
     *
     * @SerializedName("file")
     * @Type("array")
     * @SPL\Type("file")
     * @Groups ({"Read"})
     */
    public $file;
}
