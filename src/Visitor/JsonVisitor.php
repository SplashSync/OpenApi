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

use Exception;
use Splash\OpenApi\Action\Json;
use Splash\OpenApi\Hydrator\Hydrator;
use Splash\OpenApi\Models\Connexion\ConnexionInterface;

/**
 * Generic OpenApi Json Object Visitor
 */
class JsonVisitor extends AbstractVisitor
{
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
        parent::__construct($connexion, $hydrator, $model);

        $this
            ->setListAction(Json\ListAction::class)
            ->setCreateAction(Json\PostAction::class)
            ->setLoadAction(Json\GetAction::class)
            ->setUpdateAction(Json\PatchAction::class)
            ->setDeleteAction(Json\DeleteAction::class)
        ;
    }
}
