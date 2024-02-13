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
 * Basic Object Model with List-Resource Fields.
 */
#[SPL\SplashObject(
    type: "ListResourceWithMeta",
    name: "List Resource with Metadata",
    description: "List Resource Open API Object by Attributes",
    ico: "fa fa-list"
)]
class ListResource
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
     * Just a List of Item Objects.
     */
    #[
        Assert\Type("array<".ListItem::class.">"),
        JMS\SerializedName("items"),
        JMS\Type("iterable<".ListItem::class.">"),
        SPL\ListResource(targetClass: ListItem::class),
        SPL\Accessor(factory: "createItem")
    ]
    public array $items = array();

    /**
     * ListItem Factory for Field Accessor
     */
    public function createItem(): ?ListItem
    {
        return new ListItem();
    }
}
