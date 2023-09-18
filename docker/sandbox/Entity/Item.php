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
use Doctrine\ORM\Mapping as ORM;
use Splash\Client\Splash;
use Splash\Models\Helpers\PricesHelper;
use Splash\Templates\Local\Local;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Item Object Model with minimal Fields.
 *
 * @ORM\Entity
 */
class Item
{
    /**
     * Unique identifier.
     *
     * @var int
     *
     * @ORM\Id
     *
     * @ORM\GeneratedValue
     *
     * @ORM\Column(type="integer")
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("integer")
     *
     * @Groups ({"read"})
     */
    public $id;

    /**
     * Parent
     *
     * @var SubResource
     *
     * @ORM\OneToOne(targetEntity="App\Entity\SubResource", inversedBy="item")
     */
    public $parent;

    /**
     * Name.
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
     * @var null|bool
     *
     * @Assert\Type("bool")
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    public $bool;

    /**
     * @var null|int
     *
     * @Assert\Type("int")
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    public $int;

    /**
     * @var null|Datetime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $datetime;

    //    /**
    //     * @var null|array
    //     *
    //     * @ORM\Column(type="array", nullable=true)
    //     */
    //    protected $price;
    //
    //    /**
    //     * @param array|null $price
    //     *
    //     * @return self
    //     */
    //    public function setPrice(?array $price): self
    //    {
    //        $this->price = $price;
    //
    //        return $this;
    //    }
    //
    //    /**
    //     * @return array
    //     */
    //    public function getPrice(): array
    //    {
    //        if (empty($this->price)) {
    //            //====================================================================//
    //            // Init Splash Framework
    //            Splash::setLocalClass(new Local());
    //            //====================================================================//
    //            // Encode Splash Price Array
    //            $this->price = PricesHelper::encode((float) rand(10, 100), 20.0, null, "EUR");
    //        }
    //
    //        return $this->price;
    //    }
}
