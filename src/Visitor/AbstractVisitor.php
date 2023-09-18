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

namespace Splash\OpenApi\Visitor;

use DateTimeZone;
use Exception;
use Httpful\Response;
use Splash\OpenApi\ApiResponse;
use Splash\OpenApi\Fields;
use Splash\OpenApi\Hydrator\Hydrator;
use Splash\OpenApi\Models\Action\AbstractCreateAction;
use Splash\OpenApi\Models\Action\AbstractDeleteAction;
use Splash\OpenApi\Models\Action\AbstractListAction;
use Splash\OpenApi\Models\Action\AbstractLoadAction;
use Splash\OpenApi\Models\Action\AbstractUpdateAction;
use Splash\OpenApi\Models\Connexion\ConnexionInterface;

/**
 * Base OpenApi Remote Model Visitor
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AbstractVisitor
{
    //====================================================================//
    // Model Informations
    //====================================================================//

    /**
     * API Model Class
     *
     * @var class-string
     */
    protected $model;

    /**
     * API Model Collection Uri
     *
     * @var string
     */
    protected $collectionUri;

    /**
     * API Model Collection Uri
     *
     * @var string
     */
    protected $itemUri;

    /**
     * @var null|DateTimeZone
     */
    protected static ?DateTimeZone $timezone = null;

    //====================================================================//
    // Actions Storage
    //====================================================================//

    /**
     * Object List Action
     *
     * @var AbstractListAction
     */
    protected $listAction;

    /**
     * Object Create Action
     *
     * @var AbstractCreateAction
     */
    protected $createAction;

    /**
     * Object Load Action
     *
     * @var AbstractLoadAction
     */
    protected $loadAction;

    /**
     * Object Update Action
     *
     * @var AbstractUpdateAction
     */
    protected $updateAction;

    /**
     * Object Delete Action
     *
     * @var AbstractDeleteAction
     */
    protected $deleteAction;

    //====================================================================//
    // API Interfaces
    //====================================================================//

    /**
     * API Connexion
     *
     * @var ConnexionInterface
     */
    protected $connexion;

    /**
     * Object Hydrator
     *
     * @var Hydrator
     */
    protected $hydrator;

    //====================================================================//
    // Basic Setters & Setters
    //====================================================================//

    /**
     * Class Constructor
     *
     * @param ConnexionInterface $connexion
     * @param Hydrator           $hydrator
     * @param string             $model
     *
     * @throws Exception
     */
    public function __construct(ConnexionInterface $connexion, Hydrator $hydrator, string $model)
    {
        //====================================================================//
        // Connect API Interfaces
        $this->connexion = $connexion;
        $this->hydrator = $hydrator;
        //====================================================================//
        // Setup Model Class
        $this->setModel($model);
    }

    //====================================================================//
    // Model Setup
    //====================================================================//

    /**
     * Set Model
     *
     * @param string      $model
     * @param null|string $collectionUri
     * @param null|string $itemUri
     * @param null|array  $exclude
     *
     * @throws Exception
     *
     * @return self
     */
    public function setModel(
        string $model,
        string $collectionUri = null,
        string $itemUri = null,
        array $exclude = null
    ): self {
        //====================================================================//
        // Ensure Model Class Exists
        if (!class_exists($model)) {
            throw new Exception(sprintf("Model Class %s not found", $model));
        }
        $this->model = $model;
        //====================================================================//
        // Ensure Loading of Object Metadata
        Fields\Descriptor::load($this->hydrator, $this->model, $exclude);
        //====================================================================//
        // Setup Model Uri
        $this->collectionUri = $collectionUri ?: "/".self::toSnakeCaseModelName($model)."s";
        $this->itemUri = $itemUri ?: $this->collectionUri."/{id}";

        return $this;
    }

    /**
     * Get Model Collection Uri
     *
     * @return string
     */
    public function getCollectionUri(): string
    {
        return $this->collectionUri;
    }

    /**
     * @param array|object|string $objectOrId
     *
     * @return null|string
     */
    public function getItemUri($objectOrId): ?string
    {
        //====================================================================//
        // Detect Item Id
        $itemId = $this->getItemId($objectOrId);

        //====================================================================//
        // Build Item Uri
        return is_string($itemId)
            ? str_replace("{id}", $itemId, $this->itemUri)
            : null;
    }

    /**
     * @param array|object|string $objectOrId
     *
     * @return null|string
     */
    public function getItemId($objectOrId): ?string
    {
        //====================================================================//
        // String Received
        if (is_string($objectOrId)) {
            return $objectOrId;
        }
        //====================================================================//
        // Array Received
        if (is_array($objectOrId) && isset($objectOrId['id'])) {
            return $objectOrId['id'];
        }
        //====================================================================//
        // Object Received
        if (is_object($objectOrId)) {
            if (method_exists($objectOrId, "getId")) {
                return (string) $objectOrId->getId();
            }
            if (property_exists($objectOrId, "id") && is_scalar($objectOrId->id)) {
                return (string) $objectOrId->id;
            }
        }

        return null;
    }

    /**
     * Set Connector Default Timezone
     *
     * @param string $timezone
     *
     * @return AbstractVisitor
     */
    public function setTimezone(string $timezone = "Europe/Paris"): self
    {
        self::$timezone = new DateTimeZone($timezone);

        return $this;
    }

    //====================================================================//
    // API Actions Execution
    //====================================================================//

    /**
     * Execute List Action
     *
     * @param null|string $filter
     * @param null|array  $params
     *
     * @return ApiResponse
     */
    public function list(string $filter = null, array $params = null): ApiResponse
    {
        return $this->listAction->execute($filter, $params);
    }

    /**
     * Execute Paginated List Action. Read large datasets with a multipart list action.
     *
     * @param null|string $filter
     * @param int         $pageSize
     * @param int         $maxItems
     *
     * @return ApiResponse
     */
    public function listWithPagination(string $filter = null, int $pageSize = 50, int $maxItems = 1000): ApiResponse
    {
        //====================================================================//
        // Init Counters
        $maxLoaded = $listTotal = 0;
        $listData = array();
        //====================================================================//
        // Multi-pages Loading Objects List from API
        do {
            //====================================================================//
            // Load Units List from API
            $listResponse = $this->list($filter, array(
                "max" => $pageSize,
                "offset" => $maxLoaded,
            ));
            //====================================================================//
            // Request Fail => Exit
            if (!$listResponse->isSuccess()) {
                break;
            }
            //====================================================================//
            // Increment Counters
            $maxLoaded += $pageSize;
            $listTotal = $listTotal ?: $listResponse->getListTotal();
            //====================================================================//
            // Push Units to List
            $rawData = $listResponse->getResults();
            if (!is_array($rawData)) {
                continue;
            }
            if (isset($rawData["meta"])) {
                unset($rawData["meta"]);
            }
            //====================================================================//
            // Push Units to List
            $listData = array_merge($listData, $rawData);
        } while (($maxLoaded < $listTotal) && ($maxLoaded < $maxItems));

        return new ApiResponse(
            $this,
            $listResponse->isSuccess(),
            $listData,
            array('current' => count($listData), 'total' => $listTotal)
        );
    }

    /**
     * Execute Load Action
     *
     * @param string $objectId
     *
     * @return ApiResponse
     */
    public function load(string $objectId): ApiResponse
    {
        return $this->loadAction->execute($objectId);
    }

    /**
     * Execute Create Action
     *
     * @param object    $object
     * @param null|bool $requiredOnly
     *
     * @return ApiResponse
     */
    public function create(object $object, bool $requiredOnly = null): ApiResponse
    {
        return $this->createAction->execute($object, $requiredOnly);
    }

    /**
     * Execute Update Action
     *
     * @param string $objectId
     * @param object $object
     *
     * @return ApiResponse
     */
    public function update(string $objectId, object $object): ApiResponse
    {
        return $this->updateAction->execute($objectId, $object);
    }

    /**
     * Execute Delete Action
     *
     * @param object|string $objectOrId
     *
     * @return ApiResponse
     */
    public function delete($objectOrId): ApiResponse
    {
        return $this->deleteAction->execute($objectOrId);
    }

    //====================================================================//
    // API Actions Setup
    //====================================================================//

    /**
     * Setup Model List Action
     *
     * @param string $actionClass
     * @param array  $options
     *
     * @throws Exception
     *
     * @return self
     */
    public function setListAction(string $actionClass, array $options = array()): self
    {
        if (!is_subclass_of($actionClass, AbstractListAction::class, true)) {
            throw new Exception(sprintf("List Action Class is Invalid: %s", $actionClass));
        }
        $this->listAction = new $actionClass($this, $options);

        return $this;
    }

    /**
     * Setup Model Create Action
     *
     * @param string $actionClass
     * @param array  $options
     *
     * @throws Exception
     *
     * @return self
     */
    public function setCreateAction(string $actionClass, array $options = array()): self
    {
        if (!is_subclass_of($actionClass, AbstractCreateAction::class, true)) {
            throw new Exception(sprintf("Create Action Class is Invalid: %s", $actionClass));
        }
        $this->createAction = new $actionClass($this, $options);

        return $this;
    }

    /**
     * Setup Model Load Action
     *
     * @param string $actionClass
     * @param array  $options
     *
     * @throws Exception
     *
     * @return self
     */
    public function setLoadAction(string $actionClass, array $options = array()): self
    {
        if (!is_subclass_of($actionClass, AbstractLoadAction::class, true)) {
            throw new Exception(sprintf("Load Action Class is Invalid: %s", $actionClass));
        }
        $this->loadAction = new $actionClass($this, $options);

        return $this;
    }

    /**
     * Setup Model Update Action
     *
     * @param string $actionClass
     * @param array  $options
     *
     * @throws Exception
     *
     * @return self
     */
    public function setUpdateAction(string $actionClass, array $options = array()): self
    {
        if (!is_subclass_of($actionClass, AbstractUpdateAction::class, true)) {
            throw new Exception(sprintf("Update Action Class is Invalid: %s", $actionClass));
        }
        $this->updateAction = new $actionClass($this, $options);

        return $this;
    }

    /**
     * Setup Model Delete Action
     *
     * @param string $actionClass
     * @param array  $options
     *
     * @throws Exception
     *
     * @return self
     */
    public function setDeleteAction(string $actionClass, array $options = array()): self
    {
        if (!is_subclass_of($actionClass, AbstractDeleteAction::class, true)) {
            throw new Exception(sprintf("Delete Action Class is Invalid: %s", $actionClass));
        }

        $this->deleteAction = new $actionClass($this, $options);

        return $this;
    }

    //====================================================================//
    // Basic Setters & Setters
    //====================================================================//

    /**
     * Api Model Class
     *
     * @return class-string
     */
    public function getModel() : string
    {
        return $this->model;
    }

    /**
     * @return Hydrator
     */
    public function getHydrator(): Hydrator
    {
        return $this->hydrator;
    }

    /**
     * Get Connector Default Timezone
     *
     * @return DateTimeZone
     */
    public static function getTimezone(): DateTimeZone
    {
        return self::$timezone ?? new DateTimeZone("Europe/Paris");
    }

    /**
     * Get Connector Api Connexion
     *
     * @return ConnexionInterface
     */
    public function getConnexion() : ConnexionInterface
    {
        return $this->connexion;
    }

    /**
     * @return null|Response
     */
    public function getLastResponse(): ?Response
    {
        return $this->connexion->getLastResponse();
    }

    /**
     * Convert Model Name to Snake Case
     *
     * @param class-string $model
     *
     * @throws Exception
     *
     * @return string
     */
    private static function toSnakeCaseModelName(string $model): string
    {
        return strtolower((string) preg_replace(
            '/(?<!^)[A-Z]/',
            '_$0',
            Fields\Descriptor::getShortName($model)
        ));
    }
}
