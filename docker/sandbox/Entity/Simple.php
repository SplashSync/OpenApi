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

namespace App\Entity;

use ApiPlatform\Core\Annotation as API;
use Datetime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Splash\Client\Splash;
use Splash\Models\Helpers\PricesHelper;
use Splash\Templates\Local\Local;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Simple Object Model with basic Fields.
 */
#[
    API\ApiResource,
    ORM\Entity()
]
class Simple
{
    /**
     * Unique Identifier.
     */
    #[
        Assert\Type("integer"),
        ORM\Id,
        ORM\GeneratedValue,
        ORM\Column(type: Types::INTEGER),
    ]
    public int $id;

    /**
     * Client's firstname.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column(),
    ]
    public string $firstname;

    /**
     * Client's lastname.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column(),
    ]
    public string $lastname;

    /**
     * Client's email.
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
    ]
    public ?string $email = null;

    /**
     * Client's phone.
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
    ]
    public ?string $phone = null;

    #[
        Assert\Type("bool"),
        ORM\Column(type: Types::BOOLEAN, nullable: true),
    ]
    public ?bool $bool;

    #[
        Assert\Type("int"),
        ORM\Column(type: Types::INTEGER, nullable: true),
    ]
    public ?int $int;

    /**
     * Website Url
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
    ]
    public ?string $website = null;

    /**
     * ISO Language
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
    ]
    public ?string $language = null;

    /**
     * ISO Currency
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
    ]
    public ?string $currency;

    /**
     * Address country.
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
    ]
    public ?string $countryId;

    /**
     * Date Field
     */
    #[
        Assert\Type("datetime"),
        ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true),
    ]
    public ?Datetime $date = null;

    /**
     * Datetime Field
     */
    #[
        Assert\Type("datetime"),
        ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true),
    ]
    public ?Datetime $datetime = null;

    /**
     * Price Field
     */
    #[
        Assert\Type("array"),
        ORM\Column(type: Types::JSON, nullable: true),
    ]
    protected ?array $price = null;

    /**
     * Image Field
     */
    #[
        Assert\Type("array"),
        ORM\Column(type: Types::JSON, nullable: true),
    ]
    protected ?array $image = null;

    /**
     * File Field
     */
    #[
        Assert\Type("array"),
        ORM\Column(type: Types::JSON, nullable: true),
    ]
    protected ?array $file = null;

    /**
     * @param null|array $price
     *
     * @return self
     */
    public function setPrice(?array $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return array
     */
    public function getPrice(): array
    {
        if (empty($this->price)) {
            //====================================================================//
            // Init Splash Framework
            Splash::setLocalClass(new Local());
            //====================================================================//
            // Encode Splash Price Array
            $this->price = PricesHelper::encode((float) rand(10, 100), 20.0, null, "EUR");
        }

        return $this->price;
    }

    /**
     * @return array
     */
    public function getImage(): array
    {
        if (!isset($this->image)) {
            $helperClass = "App\\Helpers\\Images";
            if (class_exists($helperClass)) {
                $this->image = $helperClass::fake();
            }
        }

        return $this->image;
    }

    /**
     * @return array
     */
    public function getFile(): array
    {
        if (!isset($this->file)) {
            $helperClass = "App\\Helpers\\Files";
            if (class_exists($helperClass)) {
                $this->file = $helperClass::fake();
            }
        }

        return $this->file;
    }
}
