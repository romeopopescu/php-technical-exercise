<?php

declare(strict_types=1);

namespace AcmeLearn\Importer\Tests;

use AcmeLearn\Importer\UserValidator;
use PHPUnit\Framework\TestCase;

final class UserValidatorTest extends TestCase
{
    private UserValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new UserValidator();
    }

    public function testValidRowHasNoErrors(): void
    {
        $errors = $this->validator->validate([
            'hr_id'      => 'E1001',
            'first_name' => 'Alice',
            'last_name'  => 'Adams',
            'email'      => 'alice.adams@example.com',
        ]);

        self::assertSame([], $errors);
    }

    public function testMissingLastNameIsReported(): void
    {
        $errors = $this->validator->validate([
            'hr_id'      => 'E1004',
            'first_name' => 'David',
            'last_name'  => '',
            'email'      => 'david@example.com',
        ]);

        self::assertContains('missing last_name', $errors);
    }

    public function testInvalidEmailIsReported(): void
    {
        $errors = $this->validator->validate([
            'hr_id'      => 'E1005',
            'first_name' => 'Eve',
            'last_name'  => 'Evans',
            'email'      => 'not-an-email',
        ]);

        self::assertContains('invalid email', $errors);
    }

    public function testInvalidHrIdFormatIsReported(): void
    {
        $errors = $this->validator->validate([
            'hr_id'      => 'not a valid code!',
            'first_name' => 'Mallory',
            'last_name'  => 'Moore',
            'email'      => 'mallory@example.com',
        ]);

        self::assertContains('invalid hr_id format', $errors);
    }
}
