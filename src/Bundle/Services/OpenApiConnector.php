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

namespace Splash\OpenApi\Bundle\Services;

use ArrayObject;
use Exception;
use Splash\Bundle\Interfaces\Connectors\TrackingInterface;
use Splash\Bundle\Models\AbstractConnector;
use Splash\Bundle\Models\Connectors\GenericObjectMapperTrait;
use Splash\Bundle\Models\Connectors\GenericWidgetMapperTrait;
use Splash\Core\SplashCore as Splash;
use Splash\OpenApi\Action;
use Splash\OpenApi\Bundle\Form\EditFormType;
use Splash\OpenApi\Bundle\Objects;
use Splash\OpenApi\Connexion\JsonConnexion;
use Splash\OpenApi\Hydrator\Hydrator;
use Splash\OpenApi\Models\Connexion\ConnexionInterface;

/**
 * ReCommerce REST API Connector for Splash
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class OpenApiConnector extends AbstractConnector implements TrackingInterface
{
    use GenericObjectMapperTrait;
    use GenericWidgetMapperTrait;

    /**
     * Objects Type Class Map
     *
     * @var array
     */
    protected static array $objectsMap = array(
        "Simple" => Objects\Simple::class,
        "SubResource" => Objects\SubResource::class,
        "ListResource" => Objects\ListResource::class,
    );

    /**
     * Widgets Type Class Map
     *
     * @var array
     */
    protected static array $widgetsMap = array(
        "SelfTest" => "Splash\\OpenApi\\Bundle\\Widgets\\SelfTest",
    );

    /**
     * @var null|ConnexionInterface
     */
    private ?ConnexionInterface $connexion;

    /**
     * Object Hydrator
     *
     * @var Hydrator
     */
    private Hydrator $hydrator;

    /**
     * @var string
     */
    private string $metaDir;

    /**
     * Setup Cache Dir for Metadata
     */
    public function setMetaDir(string $metaDir) : void
    {
        $this->metaDir = $metaDir."/metadata/openapi";
    }

    /**
     * {@inheritdoc}
     */
    public function ping() : bool
    {
        //====================================================================//
        // Safety Check => Verify Self-test Pass
        if (!$this->selfTest()) {
            return false;
        }
        //====================================================================//
        // Perform Ping Test
        return Action\Ping::execute($this->getConnexion(), "");
    }

    /**
     * {@inheritdoc}
     */
    public function connect() : bool
    {
        //====================================================================//
        // Safety Check => Verify Self-test Pass
        if (!$this->selfTest()) {
            return false;
        }
        //====================================================================//
        // Perform Connect Test
        return Action\Connect::execute($this->getConnexion(), "/simples");
    }

    /**
     * {@inheritdoc}
     */
    public function informations(ArrayObject  $informations) : ArrayObject
    {
        //====================================================================//
        // Server General Description
        $informations->shortdesc = "Open API";
        $informations->longdesc = "Splash Integration for OpenApi Applications";
        //====================================================================//
        // Company Informations
        $informations->company = "Splashsync";
        $informations->address = "xxx";
        $informations->zip = "33000";
        $informations->town = "Bordeaux";
        $informations->country = "France";
        $informations->www = "https://www.splashsync.com/";
        $informations->email = "openapi@exemple.com";
        $informations->phone = " ";
        //====================================================================//
        // Server Logo & Ico
        $informations->icoraw = Splash::file()->readFileContents(
            dirname(dirname(__FILE__))."/Resources/public/img/ico.png"
        );
        $informations->logourl = null;
        $informations->logoraw = Splash::file()->readFileContents(
            dirname(dirname(__FILE__))."/Resources/public/img/ico.png"
        );
        //====================================================================//
        // Server Informations
        $informations->servertype = "Open Api V0.0";
        $informations->serverurl = "www.splashsync.com";
        //====================================================================//
        // Module Informations
        $informations->moduleauthor = "Splash Official <www.splashsync.com>";
        $informations->moduleversion = "master";

        return $informations;
    }

    /**
     * {@inheritdoc}
     */
    public function selfTest() : bool
    {
        $config = $this->getConfiguration();
        //====================================================================//
        // Verify Webservice Url is Set
        //====================================================================//
        if (!isset($config["WsHost"]) || empty($config["WsHost"]) || !is_string($config["WsHost"])) {
            Splash::log()->err("Webservice Host is Invalid");

            return false;
        }
        //====================================================================//
        // Verify Api Key is Set
        //====================================================================//
        if (!isset($config["ApiKey"]) || empty($config["ApiKey"]) || !is_string($config["ApiKey"])) {
            Splash::log()->err("Api Key is Invalid");

            return false;
        }

        return true;
    }

    //====================================================================//
    // Files Interfaces
    //====================================================================//

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function getFile(string $filePath, string $fileMd5): ?array
    {
        //====================================================================//
        // Safety Check => Verify Self-test Pass
        if (!$this->selfTest()) {
            return null;
        }
        //====================================================================//
        // Read File Contents via Raw Get Request
        $rawResponse = $this->getConnexion()->getRaw($filePath);
        if (!$rawResponse || (md5($rawResponse) != $fileMd5)) {
            return null;
        }
        //====================================================================//
        // Build File Array
        $file = array();
        $file["name"] = $file["filename"] = pathinfo($filePath, PATHINFO_BASENAME);
        $file["path"] = $filePath;
        $file["url"] = null;
        $file["raw"] = base64_encode((string) $rawResponse);
        $file["md5"] = md5($rawResponse);
        $file["size"] = strlen($rawResponse);

        return $file;
    }

    //====================================================================//
    // Profile Interfaces
    //====================================================================//

    /**
     * Get Connector Profile Information
     *
     * @return array
     */
    public function getProfile() : array
    {
        return array(
            'enabled' => true,                                      // is Connector Enabled
            'beta' => false,                                        // is this a Beta release
            'type' => self::TYPE_HIDDEN,                            // Connector Type or Mode
            'name' => 'openapi',                                    // Connector code (lowercase, no space allowed)
            'connector' => 'splash.connectors.openapi',             // Connector Symfony Service
            'title' => 'profile.card.title',                        // Public short name
            'label' => 'profile.card.label',                        // Public long name
            'domain' => 'OpenApiBundle',                            // Translation domain for names
            'ico' => '/bundles/splashopenapi/img/ico.png',         // Public Icon path
            'www' => 'https://www.splashsync.com',                  // Website Url
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getConnectedTemplate() : string
    {
        return "@SplashOpenApi/Profile/connected.html.twig";
    }

    /**
     * {@inheritdoc}
     */
    public function getOfflineTemplate() : string
    {
        return "@SplashOpenApi/Profile/offline.html.twig";
    }

    /**
     * {@inheritdoc}
     */
    public function getNewTemplate() : string
    {
        return "@SplashOpenApi/Profile/new.html.twig";
    }

    /**
     * {@inheritdoc}
     */
    public function getFormBuilderName() : string
    {
        $this->selfTest();

        return EditFormType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getMasterAction(): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getPublicActions() : array
    {
        return array(
            "index" => "OpenApiBundle:WebHooks:index",
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getSecuredActions() : array
    {
        return array(
        );
    }

    //====================================================================//
    // Open API Connector Interfaces
    //====================================================================//

    /**
     * Get Connector Api Connexion
     *
     * @throws Exception
     *
     * @return ConnexionInterface
     */
    public function getConnexion() : ConnexionInterface
    {
        //====================================================================//
        // Connexion already created
        if (isset($this->connexion)) {
            return $this->connexion;
        }
        //====================================================================//
        // Safety check
        if (!$this->selfTest()) {
            throw new Exception("Self-test fails... Unable to create API Connexion!");
        }
        $config = $this->getConfiguration();
        //====================================================================//
        // Setup Api Connexion
        $this->connexion = new JsonConnexion(
            $config["WsHost"],
            array('api-key' => $config["ApiKey"])
        );

        return $this->connexion;
    }

    /**
     * @return Hydrator
     */
    public function getHydrator(): Hydrator
    {
        //====================================================================//
        // Configure Object Hydrator
        if (!isset($this->hydrator)) {
            $this->hydrator = new Hydrator($this->metaDir);
        }

        return $this->hydrator;
    }
}
