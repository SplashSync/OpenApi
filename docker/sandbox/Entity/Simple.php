<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) 2015-2020 Splash Sync  <www.splashsync.com>
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
     * Unique identifier representing a Shipment.
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
     * @var string
     *
     * @ORM\Column(nullable=true)
     */
    public $language;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     */
    public $currency;

    /**
     * Address country.
     *
     * @var string
     *
     * @ORM\Column(nullable=true)
     */
    public $countryId;

    /**
     * @var Datetime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    public $date;

    /**
     * @var Datetime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $datetime;
}
