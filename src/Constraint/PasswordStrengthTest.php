<?php

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

namespace Darse\PasswordStrengthValidator\Constraint;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Exception\MissingOptionsException;

class PasswordStrengthTest extends TestCase
{
    public function testDefaultOption()
    {
        $constraint = new PasswordStrength(2);
        $this->assertEquals(2, $constraint->min_score);
    }

    public function testMinimumRequiredOptions()
    {
        new PasswordStrength(['min_score' => 2]);
    }

    public function testMissingScore()
    {
        $this->setExpectedException(MissingOptionsException::class);
        $this->expectExceptionMessageRegExp('/min_score/');

        new PasswordStrength();
    }
}
