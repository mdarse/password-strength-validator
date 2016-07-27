<?php

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

namespace Darse\PasswordStrengthValidator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PasswordStrength extends Constraint
{
    public $min_score;
    public $message = 'Password is too weak.';

    public function getDefaultOption()
    {
        return 'min_score';
    }

    public function getRequiredOptions()
    {
        return ['min_score'];
    }
}
