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

namespace Splash\OpenApi\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
#[\Attribute]
class Microdata extends Constraint
{
    /**
     * @var array
     */
    public $value;

    /**
     * {@inheritdoc}
     */
    public function getDefaultOption()
    {
        return 'value';
    }

    /**
     * Verify Inputs are Valid
     *
     * @return bool
     */
    public function isValid(): bool
    {
        if (!is_array($this->value) || (2 != count($this->value))) {
            return false;
        }
        if (!isset($this->value[0]) || !is_string($this->value[0])) {
            return false;
        }
        if (!isset($this->value[1]) || !is_string($this->value[1])) {
            return false;
        }

        return true;
    }

    /**
     * Get Item Type
     *
     * @return string
     */
    public function getItemType(): string
    {
        return $this->value[0];
    }

    /**
     * Get Item Type
     *
     * @return string
     */
    public function getItemProp(): string
    {
        return $this->value[1];
    }
}
