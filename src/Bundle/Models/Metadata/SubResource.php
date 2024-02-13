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

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Basic Object Api Metadata Model with Sub-Resource Fields.
 */
#[SPL\SplashObject(
    type: "SubResourceWithMeta",
    name: "SubResource with Metadata",
    description: "SubResource Open API Object by Attributes",
    ico: "fa fa-list"
)]
class SubResource
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
     * Object Name.
     */
    #[
        Assert\NotNull(),
        Assert\Type("string"),
        JMS\SerializedName("name"),
        JMS\Type("string"),
        JMS\Groups(array("Read", "Write", "List", "Required")),
        SPL\Flags(listed: true),
        SPL\IsRequired,
    ]
    public string $name;

    /**
     * Just an Item Object.
     */
    #[
        Assert\Type(Item::class),
        JMS\SerializedName("item"),
        JMS\Type(Item::class),
        SPL\SubResource(),
        SPL\Accessor(factory: "createItem"),
    ]
    public ?Item $item = null;

    /**
     * Item Factory for Field Accessor
     */
    public function createItem(): ?Item
    {
        $this->item = new Item();

        return $this->item;
    }
}
