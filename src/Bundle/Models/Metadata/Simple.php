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

namespace Splash\OpenApi\Bundle\Models\Metadata;

use DateTime;
use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Api Metadata Model for Simple Object: Basic Fields.
 *
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
#[SPL\SplashObject(
    type: "SimpleWithMeta",
    name: "Simple with Metadata",
    description: "Simple Open API Object by Attributes",
    ico: "fa fa-cube"
)]
class Simple
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
     * Client's firstname.
     */
    #[
        Assert\NotNull(),
        Assert\Type("string"),
        JMS\SerializedName("firstname"),
        JMS\Type("string"),
        JMS\Groups(array("Read", "Write", "List", "Required")),
        SPL\Field(desc: "This is First Name"),
        SPL\Flags(listed: true),
        SPL\IsRequired,
    ]
    public string $firstname;

    /**
     * Client's lastname.
     */
    #[
        Assert\NotNull(),
        Assert\Type("string"),
        JMS\SerializedName("lastname"),
        JMS\Type("string"),
        JMS\Groups(array("Read", "Write", "List", "Required")),
        SPL\Field(desc: "This is Last Name"),
        SPL\Flags(listed: true),
        SPL\IsRequired,
    ]
    public string $lastname;

    /**
     * Client's email.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("email"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_EMAIL, desc: "This is User Email"),
    ]
    public ?string $email = null;

    /**
     * Client's phone.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("phone"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_PHONE, desc: "This is User Phone"),
    ]
    public ?string $phone = null;

    /**
     * Just a Bool Flag.
     */
    #[
        Assert\Type("bool"),
        JMS\SerializedName("bool"),
        JMS\Type("bool"),
        SPL\Field(),
    ]
    public ?bool $bool = null;

    /**
     * Just an integer.
     */
    #[
        Assert\Type("int"),
        JMS\SerializedName("int"),
        JMS\Type("int"),
        SPL\Field(),
    ]
    public ?int $int = null;

    /**
     * Client's website Url.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("website"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_URL)
    ]
    public ?string $website = null;

    /**
     * ISO Language
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("language"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_LANG)
    ]
    public ?string $language = null;

    /**
     * ISO Currency
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("currency"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_CURRENCY)
    ]
    public ?string $currency = null;

    /**
     * Address country as ISO_3166-1 alpha-3.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("countryId"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_COUNTRY)
    ]
    public ?string $countryId = null;

    /**
     * Date Field
     */
    #[
        Assert\Type("datetime"),
        JMS\SerializedName("date"),
        JMS\Type("DateTime"),
        SPL\Field(type: SPL_T_DATE)
    ]
    public ?DateTime $date = null;

    /**
     * Datetime Field
     */
    #[
        Assert\Type("datetime"),
        JMS\SerializedName("datetime"),
        JMS\Type("DateTime"),
        SPL\Field(type: SPL_T_DATETIME)
    ]
    public ?DateTime $datetime = null;

    /**
     * Splash Price Field
     */
    #[
        Assert\Type("array"),
        JMS\SerializedName("price"),
        JMS\Type("array"),
        SPL\Field(type: SPL_T_PRICE)
    ]
    public ?array $price = null;

    /**
     * Splash Image Field
     */
    #[
        Assert\Type("array"),
        JMS\SerializedName("image"),
        JMS\Type("array"),
        SPL\Field(type: SPL_T_IMG),
        SPL\IsReadOnly,
    ]
    public ?array $image = null;

    /**
     * Splash File Field
     */
    #[
        Assert\Type("array"),
        JMS\SerializedName("file"),
        JMS\Type("array"),
        SPL\Field(type: SPL_T_FILE),
        SPL\IsReadOnly,
    ]
    public ?array $file = null;
}
