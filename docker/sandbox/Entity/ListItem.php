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

use Datetime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Splash\Client\Splash;
use Splash\Models\Helpers\PricesHelper;
use Splash\Templates\Local\Local;
use Symfony\Component\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Item Object Model with minimal Fields.
 */
#[
    ORM\Entity()
]
class ListItem
{
    /**
     * Unique Identifier.
     */
    #[
        Assert\Type("integer"),
        ORM\Id,
        ORM\GeneratedValue,
        ORM\Column(type: Types::INTEGER),
        JMS\Groups(array("read"))
    ]
    public int $id;

    /**
     * Parent Object
     */
    #[
        ORM\ManyToOne(targetEntity: ListResource::class, inversedBy: "items"),
    ]
    public ListResource $parent;

    /**
     * Name.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column(),
    ]
    public string $name;

    /**
     * Boolean Flag
     */
    #[
        Assert\Type("bool"),
        ORM\Column(type: Types::BOOLEAN, nullable: true),
    ]
    public ?bool $bool = null;

    /**
     * Integer Value
     */
    #[
        Assert\Type("int"),
        ORM\Column(type: Types::INTEGER, nullable: true),
    ]
    public ?int $int = null;

    /**
     * DateTime Value
     */
    #[
        Assert\Type("datetime"),
        ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true),
    ]
    public ?Datetime $datetime = null;

    /**
     * Item Price
     */
    #[
        Assert\Type("array"),
        ORM\Column(type: Types::JSON, nullable: true),
    ]
    protected ?array $price = null;

    /**
     * Set Item Price
     */
    public function setPrice(?array $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get Item Price
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
}
