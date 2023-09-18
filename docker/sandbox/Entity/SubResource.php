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

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Basic Object Model with Sub-Resource Fields.
 *
 * @ApiResource()
 *
 * @ORM\Entity
 */
class SubResource
{
    /**
     * Unique identifier .
     *
     * @var int
     *
     * @ORM\Id
     *
     * @ORM\GeneratedValue
     *
     * @ORM\Column(type="integer")
     *
     * @Assert\Type("integer")
     */
    public $id;

    /**
     * Object Name.
     *
     * @var string
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("string")
     *
     * @ORM\Column
     */
    public $name;

    /**
     * Just a Item Object.
     *
     * @var null|Item
     *
     * @Assert\Type("App\Entity\Item")
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Item", cascade={"all"})
     *
     * @ORM\JoinColumn(referencedColumnName="id", unique=true, nullable=true)
     */
    protected $item;

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
    public function setItem(Item $item)
    {
        $item->parent = $this;
        $this->item = $item;
    }
}
