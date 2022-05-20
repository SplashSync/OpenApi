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
use Datetime;
use Doctrine\ORM\Mapping as ORM;
use Splash\Client\Splash;
use Splash\Models\Helpers\PricesHelper;
use Splash\Templates\Local\Local;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Simple Object Model with basic Fields.
 *
 * @ApiResource()
 * @ORM\Entity
 */
class Simple
{
    /**
     * Unique identifier.
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
     * Client's firstname.
     *
     * @var string
     * @Assert\NotNull()
     * @Assert\Type("string")
     *
     * @ORM\Column
     */
    public $firstname;

    /**
     * Client's lastname.
     *
     * @var string
     * @Assert\NotNull()
     * @Assert\Type("string")
     *
     * @ORM\Column
     */
    public $lastname;

    /**
     * Client's email.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @ORM\Column(nullable=true)
     */
    public $email;

    /**
     * Client's phone.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @ORM\Column(nullable=true)
     */
    public $phone;

    /**
     * @var null|bool
     * @Assert\Type("bool")
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    public $bool;

    /**
     * @var null|int
     * @Assert\Type("int")
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    public $int;

    /**
     * Client's website.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @ORM\Column(nullable=true)
     */
    public $website;

    /**
     * @var null|string
     *
     * @ORM\Column(nullable=true)
     */
    public $language;

    /**
     * @var null|string
     *
     * @ORM\Column(nullable=true)
     */
    public $currency;

    /**
     * Address country.
     *
     * @var null|string
     *
     * @ORM\Column(nullable=true)
     */
    public $countryId;

    /**
     * @var null|Datetime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    public $date;

    /**
     * @var null|Datetime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $datetime;

    /**
     * @var null|array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    protected $price;

    /**
     * @var null|array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    protected $image;

    /**
     * @var null|array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    protected $file;

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
