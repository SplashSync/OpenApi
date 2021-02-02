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

namespace Splash\OpenApi\Models\Objects;

use Splash\Bundle\Models\AbstractStandaloneObject;
use Splash\Client\Splash;
use Splash\OpenApi\Hydrator\Hydrator;
use Splash\OpenApi\Models\Connexion\ConnexionInterface;
use Splash\OpenApi\Models\Objects as ApiModels;
use Splash\OpenApi\Visitor\AbstractVisitor;
use Splash\OpenApi\Visitor\JsonVisitor;

/**
 * Abstract Class for Working with Splash Api Object
 */
abstract class AbstractApiObject extends AbstractStandaloneObject
{
    use ApiModels\CRUDTrait;
    use ApiModels\SimpleFieldsTrait;
    use ApiModels\ListFieldsTrait;

    //====================================================================//
    // Open Api Variables
    //====================================================================//

    /**
     * API Model Class Name
     *
     * @var string
     */
    protected $model;

    /**
     * Open Api Model Visitor
     *
     * @var AbstractVisitor
     */
    protected $visitor;

    /**
     * Class Constructor
     *
     * @param ConnexionInterface   $connexion
     * @param Hydrator             $hydrator
     * @param string               $model
     * @param null|AbstractVisitor $visitor
     */
    public function __construct(ConnexionInterface $connexion, Hydrator $hydrator, string $model, AbstractVisitor $visitor = null)
    {
        $this->model = $model;
        $this->visitor = $visitor ?: new JsonVisitor($connexion, $hydrator, $model);
    }

    //====================================================================//
    // Generic Splash Objects Methods
    //====================================================================//

    /**
     * {@inheritdoc}
     */
    public function objectsList($filter = null, $params = null)
    {
        return $this->visitor->list($filter, $params)->getResults();
    }
}
