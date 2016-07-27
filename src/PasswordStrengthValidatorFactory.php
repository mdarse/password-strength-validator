<?php

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

namespace Darse\PasswordStrengthValidator;

use Darse\PasswordStrengthValidator\Constraint\PasswordStrength;
use Darse\PasswordStrengthValidator\Constraint\PasswordStrengthValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use ZxcvbnPhp\Zxcvbn;

class PasswordStrengthValidatorFactory extends ConstraintValidatorFactory
{
    private $zxcvbn;

    public function __construct(Zxcvbn $zxcvbn)
    {
        $this->zxcvbn = $zxcvbn;
    }

    public function getInstance(Constraint $constraint)
    {
        return $constraint instanceof PasswordStrength
            ? new PasswordStrengthValidator($this->zxcvbn)
            : parent::getInstance($constraint);
    }
}
