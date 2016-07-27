<?php

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

namespace Darse\PasswordStrengthValidator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use ZxcvbnPhp\Zxcvbn;

class PasswordStrengthValidator extends ConstraintValidator
{
    private $zxcvbn;

    public function __construct(Zxcvbn $zxcvbn = null)
    {
        $this->zxcvbn = $zxcvbn ?: new Zxcvbn();
    }

    public function validate($value, Constraint $constraint)
    {
        // TODO Implement another annotation to mark user data to use these
        // properties in dictionary match

        $strength = $this->zxcvbn->passwordStrength($value);
        if (!isset($strength['score'])) {
            throw new \LogicException('Invalid strength data from zxcvbn.');
        }

        if ($strength['score'] < $constraint->min_score) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
