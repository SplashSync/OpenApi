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
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Basic Object Model with Sub-Resource Fields.
 */
#[
    API\ApiResource,
    ORM\Entity()
]
class SubResource
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
     * Object Name.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column(),
    ]
    public string $name;

    /**
     * Just an Item Object.
     */
    #[
        Assert\Type(Item::class),
        ORM\OneToOne(targetEntity: Item::class, cascade: array("all")),
        ORM\JoinColumn(referencedColumnName:"id", unique:true, nullable:true),
    ]
    protected ?Item $item;

    /**
     * @return null|Item
     */
    public function getItem(): ?Item
    {
        // Force Hydratation of Child Object
        isset($this->item) ?? $this->item->name;

        return $this->item;
    }

    /**
     * @param Item $item
     */
    public function setItem(Item $item): void
    {
        $item->parent = $this;
        $this->item = $item;
    }
}
