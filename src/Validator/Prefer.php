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

use Exception;
use Splash\Components\FieldsFactory;
use Symfony\Component\Validator\Constraint;

/**
 * Setup Splash Field Preferred Sync Mode
 *
 * @Annotation
 */
#[\Attribute]
class Prefer extends Constraint
{
    /**
     * List of allowed sync modes
     */
    const SYNC_MODES = array(
        FieldsFactory::MODE_NONE,
        FieldsFactory::MODE_READ,
        FieldsFactory::MODE_WRITE,
    );

    /**
     * @var string
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
     * Get Field Type
     *
     * @throws Exception
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Verify Inputs are Valid
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return in_array($this->value, self::SYNC_MODES, true);
    }

    /**
     * Verify Inputs are Valid
     *
     * @param FieldsFactory $factory
     *
     * @return void
     */
    public function apply(FieldsFactory $factory): void
    {
        if (!$this->isValid()) {
            return;
        }
        switch ($this->value) {
            case FieldsFactory::MODE_NONE:
                $factory->setPreferNone();

                break;
            case FieldsFactory::MODE_READ:
                $factory->setPreferRead();

                break;
            case FieldsFactory::MODE_WRITE:
                $factory->setPreferWrite();

                break;
        }
    }
}
