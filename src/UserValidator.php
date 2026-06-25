<?php

declare(strict_types=1);

namespace AcmeLearn\Importer;

/**
 * Validates a single user row read from the import file.
 */
final class UserValidator
{
    /**
     * @param array<string, string> $row
     *
     * @return string[] List of validation errors; an empty array means the row is valid.
     */
    public function validate(array $row): array
    {
        $errors = [];

        $hrId = trim((string) ($row['hr_id'] ?? ''));
        if ($hrId === '') {
            $errors[] = 'missing hr_id';
        } elseif (!preg_match('/^[A-Z0-9-]+$/i', $hrId)) {
            $errors[] = 'invalid hr_id format';
        }

        if (trim((string) ($row['first_name'] ?? '')) === '') {
            $errors[] = 'missing first_name';
        }

        if (trim((string) ($row['last_name'] ?? '')) === '') {
            $errors[] = 'missing last_name';
        }

        $email = trim((string) ($row['email'] ?? ''));
        if ($email === '') {
            $errors[] = 'missing email';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'invalid email';
        }

        return $errors;
    }
}
