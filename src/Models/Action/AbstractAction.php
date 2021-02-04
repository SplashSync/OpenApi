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

namespace Splash\OpenApi\Models\Action;

use Splash\OpenApi\Visitor\AbstractVisitor;

/**
 * Base Api Action
 */
abstract class AbstractAction
{
    /**
     * @var AbstractVisitor
     */
    protected $visitor;

    /**
     * @var array
     */
    protected $options;

    /**
     * Create New Action.
     *
     * @param AbstractVisitor $visitor
     * @param array           $options
     */
    public function __construct(AbstractVisitor $visitor, array $options)
    {
        $this->visitor = $visitor;
        $this->options = array_replace_recursive($this->getDefaultOptions(), $options);
    }

    /**
     * @return array
     */
    protected function getDefaultOptions(): array
    {
        return array();
    }
}
