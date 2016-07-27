<?php

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

namespace Darse\PasswordStrengthValidator\Constraint;

use Darse\PasswordStrengthValidator\Constraint as Assert;
use Darse\PasswordStrengthValidator\PasswordStrengthValidatorFactory;
use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use ZxcvbnPhp\Zxcvbn;

class PasswordStrengthValidatorTest extends TestCase
{
    public function provideValidationFixtures()
    {
        return [
            [0, 0, true],
            [0, 4, true],
            [2, 1, false],
            [2, 2, true],
            [2, 3, true],
            [4, 3, false],
            [4, 4, true],
        ];
    }

    /**
     * @dataProvider provideValidationFixtures
     */
    public function testValidation($min_score, $score, $valid)
    {
        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(
                $this->createMockedValidatorFactory('foo', $score))
            ->getValidator();

        $constraint = new PasswordStrength(['min_score' => $min_score]);
        $errors = $validator->validate('foo', $constraint);
        $this->assertEquals($valid, count($errors) === 0);
    }

    public function testAnnotation()
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping(new AnnotationReader())
            ->setConstraintValidatorFactory($this->createMockedValidatorFactory('foo', 1))
            ->getValidator();

        $object = new PasswordStrengthValidatorTest_AnnotatedClass();
        $object->password = 'foo';
        $errors = $validator->validate($object);
        $this->assertCount(1, $errors);
    }

    public function testCustomErrorMessage()
    {
        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(
                $this->createMockedValidatorFactory('foo', 1))
            ->getValidator();

        $constraint = new PasswordStrength([
            'min_score' => 4,
            'message' => 'foo bar',
        ]);
        $errors = $validator->validate('foo', $constraint);
        $this->assertCount(1, $errors);
        $this->assertEquals('foo bar', $errors[0]->getMessage());
    }

    private function createMockedValidatorFactory($expected_password, $score)
    {
        $zxcvbn = $this->prophesize(Zxcvbn::class);
        $zxcvbn
            ->passwordStrength($expected_password)
            ->willReturn(['score' => $score])
        ;
        return new PasswordStrengthValidatorFactory($zxcvbn->reveal());
    }
}

class PasswordStrengthValidatorTest_AnnotatedClass
{
    /**
     * @Assert\PasswordStrength(min_score=4, message="foo bar")
     */
    public $password;
}
