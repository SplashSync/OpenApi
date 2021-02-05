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

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Basic Object Model with List Resource Fields.
 *
 * @ApiResource()
 * @ORM\Entity
 */
class ListResource
{
    /**
     * Unique identifier .
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Assert\Type("integer")
     */
    public $id;

    /**
     * Object Name.
     *
     * @var string
     * @Assert\NotNull()
     * @Assert\Type("string")
     *
     * @ORM\Column
     */
    public $name;

    /**
     * Just a Item Object.
     *
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ListItem", mappedBy="parent", cascade="all", orphanRemoval=true)
     */
    protected $items;

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
        foreach ($this->items as $item) {
            $this->items->removeElement($item);
        }

        if (is_array($items)) {
            foreach ($items as &$item) {
                $item->parent = $this;
            }
        }
        $this->items = $items;

        return $this;
    }
}
