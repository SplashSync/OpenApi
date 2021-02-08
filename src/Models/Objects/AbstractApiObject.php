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

namespace Splash\OpenApi\Models\Objects;

use Exception;
use Splash\Bundle\Models\AbstractStandaloneObject;
use Splash\Models\Objects\IntelParserTrait;
use Splash\Models\Objects\ListsTrait;
use Splash\Models\Objects\ObjectsTrait;
use Splash\Models\Objects\SimpleFieldsTrait;
use Splash\OpenApi\Hydrator\Hydrator;
use Splash\OpenApi\Models\Connexion\ConnexionInterface as Connexion;
use Splash\OpenApi\Models\Objects as ApiModels;
use Splash\OpenApi\Visitor\AbstractVisitor as Visitor;
use Splash\OpenApi\Visitor\JsonVisitor;

/**
 * Abstract Class for Working with Splash Api Object
 */
abstract class AbstractApiObject extends AbstractStandaloneObject
{
    use IntelParserTrait;
    use SimpleFieldsTrait;
    use ObjectsTrait;
    use ListsTrait;

    use ApiModels\CRUDTrait;
    use ApiModels\SimpleFieldsTrait;
    use ApiModels\ListFieldsGetTrait;
    use ApiModels\ListFieldsSetTrait;

    //====================================================================//
    // Open Api Variables
    //====================================================================//

    /**
     * Open Api Model Visitor
     *
     * @var Visitor
     */
    protected $visitor;

    /**
     * Class Constructor
     *
     * @param Connexion    $connexion
     * @param Hydrator     $hydrator
     * @param string       $model
     * @param null|Visitor $visitor
     *
     * @throws Exception
     */
    public function __construct(Connexion $connexion, Hydrator $hydrator, string $model, Visitor $visitor = null)
    {
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
