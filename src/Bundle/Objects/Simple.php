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

namespace Splash\OpenApi\Bundle\Objects;

use Exception;
use Splash\Client\Splash;
use Splash\Models\Objects\IntelParserTrait;
use Splash\Models\Objects\ListsTrait;
use Splash\Models\Objects\ObjectsTrait;
use Splash\Models\Objects\SimpleFieldsTrait;
use Splash\OpenApi\Action\Json\ListAction;
use Splash\OpenApi\Action\Json\PostAction;
use Splash\OpenApi\Bundle\Models\Api;
use Splash\OpenApi\Bundle\Services\OpenApiConnector;
use Splash\OpenApi\Fields as ApiFields;
use Splash\OpenApi\Models\Objects\AbstractApiObject;
use Splash\OpenApi\Models\OpenApiAwareTrait;
use Splash\OpenApi\Visitor\JsonVisitor;

/**
 * OpenApi Implementation for Simple Object
 */
class Simple extends AbstractApiObject
{
//    // Splash Php Core Traits
    use IntelParserTrait;
    use SimpleFieldsTrait;
    use ObjectsTrait;
    use ListsTrait;

    // OpenApi Traits
//    use OpenApiAwareTrait;

    // ReCommerce Order Traits
//    use Simple\CRUDTrait;
//    use Simple\ObjectsListTrait;

    //====================================================================//
    // Object Definition Parameters
    //====================================================================//

    /**
     * {@inheritdoc}
     */
    protected static $NAME = "Simple";

    /**
     * {@inheritdoc}
     */
    protected static $DESCRIPTION = "Simple Open API Object";

    /**
     * {@inheritdoc}
     */
    protected static $ICO = "fa fa-check";

    //====================================================================//
    // General Class Variables
    //====================================================================//

    /**
     * @var
     */
    protected $object;

    /**
     * @var
     */
    protected $visitor;

    /**
     * @var OpenApiConnector
     */
    protected $connector;

    /**
     * Class Constructor
     *
     * @param OpenApiConnector $connector
     *
     * @throws Exception
     */
    public function __construct(OpenApiConnector $connector)
    {
        parent::__construct($connector->getConnexion(), $connector->getHydrator(), Api\Simple::class);

//        $this->visitor->setCreateAction(PostAction::class, array("requiredOnly" => false));

//        $this->connector = $connector;
//        //====================================================================//
//        // Connect Open Api Interfaces
//        $this->model = Api\Simple::class;
//        $this->connexion = $connector->getConnexion();
//        $this->hydrator = $connector->getHydrator();
        //====================================================================//
        //  Load Translation File
        Splash::translator()->load('local');
//        //====================================================================//
//        // Ensure Loading of Object Metadata
//        ApiFields\Descriptor::load($this->connector->getHydrator(), $this->model);
//
//
//
//        $this->visitor = new JsonVisitor($connector->getConnexion(), $connector->getHydrator(), Api\Simple::class);

//        $visitor->setListAction(ListAction::class, array('filterKey' => "filters"));
//        dump($visitor);
//        dump($this->visitor->list());
////        dump($visitor->list("myFilter", array('max' => 10)));
//        exit;
    }
}
