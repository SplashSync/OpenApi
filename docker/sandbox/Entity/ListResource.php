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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Basic Object Model with List Resource Fields.
 */
#[
    API\ApiResource,
    ORM\Entity()
]
class ListResource
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
     * Name.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column(),
    ]
    public string $name;

    /**
     * Object Linked Items.
     */
    #[ORM\OneToMany(
        mappedBy: "parent",
        targetEntity: ListItem::class,
        cascade: array("all"),
        orphanRemoval: true
    )]
    protected Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * @return ListItem[]
     */
    public function getItems(): array
    {
        return $this->items->toArray();
    }

    /**
     * @param null|ListItem[] $items
     *
     * @return self
     */
    public function setItems(?array $items): self
    {
        $items ??= array();
        // Remove All Items
        foreach ($this->items as $item) {
            $this->items->removeElement($item);
        }
        // Insert All New Items
        foreach ($items as $item) {
            $item->parent = $this;
            $this->items->add($item);
        }

        return $this;
    }
}
