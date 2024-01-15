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
     *
     * @SPL\Description("This is First Name")
     */
    #[
        Assert\NotNull(),
        Assert\Type("string"),
        JMS\SerializedName("firstname"),
        JMS\Type("string"),
        JMS\Groups(array("Read", "Write", "List", "Required")),
    ]
    public string $firstname;

    /**
     * Client's lastname.
     *
     * @SPL\Description("This is Last Name")
     */
    #[
        Assert\NotNull(),
        Assert\Type("string"),
        JMS\SerializedName("lastname"),
        JMS\Type("string"),
        JMS\Groups(array("Read", "Write", "List", "Required")),
    ]
    public string $lastname;

    /**
     * Client's email.
     *
     * @SPL\Type("email")
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("email"),
        JMS\Type("string"),
    ]
    public ?string $email = null;

    /**
     * Client's phone.
     *
     * @SPL\Type("phone")
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("phone"),
        JMS\Type("string"),
    ]
    public ?string $phone = null;

    /**
     * Just a Bool Flag.
     */
    #[
        Assert\Type("bool"),
        JMS\SerializedName("bool"),
        JMS\Type("bool"),
    ]
    public ?bool $bool = false;

    /**
     * Just an integer.
     */
    #[
        Assert\Type("int"),
        JMS\SerializedName("int"),
        JMS\Type("int"),
    ]
    public ?int $int = null;

    /**
     * Client's website Url.
     *
     * @SPL\Type("url")
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("website"),
        JMS\Type("string"),
    ]
    public ?string $website = null;

    /**
     * ISO Language
     *
     * @SPL\Type("lang")
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("language"),
        JMS\Type("string"),
    ]
    public ?string $language = null;

    /**
     * ISO Currency
     *
     * @SPL\Type("currency")
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("currency"),
        JMS\Type("string"),
    ]
    public ?string $currency = null;

    /**
     * Address country as ISO_3166-1 alpha-3.
     *
     * @SPL\Type("country")
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("countryId"),
        JMS\Type("string"),
    ]
    public ?string $countryId = null;

    /**
     * Date Field
     *
     * @SPL\Type("date")
     */
    #[
        Assert\Type("datetime"),
        JMS\SerializedName("date"),
        JMS\Type("DateTime"),
    ]
    public ?DateTime $date = null;

    /**
     * Datetime Field
     *
     * @SPL\Type("datetime")
     */
    #[
        Assert\Type("datetime"),
        JMS\SerializedName("datetime"),
        JMS\Type("DateTime"),
    ]
    public ?DateTime $datetime = null;

    /**
     * Splash Price Field
     *
     * @SPL\Type("price")
     */
    #[
        Assert\Type("array"),
        JMS\SerializedName("price"),
        JMS\Type("array"),
    ]
    public ?array $price = null;

    /**
     * Splash Image Field
     *
     * @SPL\Type("image")
     */
    #[
        Assert\Type("array"),
        JMS\SerializedName("image"),
        JMS\Type("array"),
        JMS\Groups(array("Read")),
    ]
    public ?array $image = null;

    /**
     * Splash File Field
     *
     * @SPL\Type("file")
     */
    #[
        Assert\Type("array"),
        JMS\SerializedName("file"),
        JMS\Type("array"),
        JMS\Groups(array("Read")),
    ]
    public ?array $file = null;
}
