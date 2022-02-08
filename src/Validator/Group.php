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

namespace Splash\OpenApi\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Group extends Constraint
{
    /**
     * @var null|string
     */
    public ?string $value;

    /**
     * {@inheritdoc}
     */
    public function getDefaultOption(): ?string
    {
        return null;
    }

    /**
     * Get Field Type
     *
     * @return null|string
     */
    public function getValue(): ?string
    {
        return $this->value;
    }
}
