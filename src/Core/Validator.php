<?php
namespace App\Core;

final class Validator
{
    private array $errors = [];

    public function int(string $field, mixed $value, array $options = []): ?int
    {
        $required = $options['required'] ?? true;

        if ($value === null || $value === '') {
            if ($required) {
                $this->addError($field, 'This value is required.');
            }
            return null;
        }

        $filtered = filter_var($value, FILTER_VALIDATE_INT);
        if ($filtered === false) {
            $this->addError($field, 'Must be a whole number.');
            return null;
        }

        if (isset($options['min']) && $filtered < $options['min']) {
            $this->addError($field, 'Value is too low.');
        }

        if (isset($options['max']) && $filtered > $options['max']) {
            $this->addError($field, 'Value is too high.');
        }

        return $this->hasErrorsFor($field) ? null : $filtered;
    }

    public function float(string $field, mixed $value, array $options = []): ?float
    {
        $required = $options['required'] ?? true;

        if ($value === null || $value === '') {
            if ($required) {
                $this->addError($field, 'This value is required.');
            }
            return null;
        }

        $filtered = filter_var($value, FILTER_VALIDATE_FLOAT);
        if ($filtered === false) {
            $this->addError($field, 'Must be a valid number.');
            return null;
        }

        if (isset($options['min']) && $filtered < $options['min']) {
            $this->addError($field, 'Value is too low.');
        }

        if (isset($options['max']) && $filtered > $options['max']) {
            $this->addError($field, 'Value is too high.');
        }

        return $this->hasErrorsFor($field) ? null : $filtered;
    }

    public function string(string $field, mixed $value, array $options = []): ?string
    {
        $required = $options['required'] ?? true;
        $trimmed = is_string($value) ? trim($value) : trim((string)($value ?? ''));

        if ($trimmed === '') {
            if ($required) {
                $this->addError($field, 'This value is required.');
            }
            return null;
        }

        $length = strlen($trimmed);

        if (isset($options['min']) && $length < $options['min']) {
            $this->addError($field, 'Too short.');
        }

        if (isset($options['max']) && $length > $options['max']) {
            $this->addError($field, 'Too long.');
        }

        return $this->hasErrorsFor($field) ? null : $trimmed;
    }

    public function email(string $field, mixed $value, array $options = []): ?string
    {
        $required = $options['required'] ?? true;

        if ($value === null || $value === '') {
            if ($required) {
                $this->addError($field, 'Email is required.');
            }
            return null;
        }

        $email = filter_var($value, FILTER_VALIDATE_EMAIL);
        if ($email === false) {
            $this->addError($field, 'Must be a valid email address.');
            return null;
        }

        return $email;
    }

    public function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    private function hasErrorsFor(string $field): bool
    {
        return !empty($this->errors[$field]);
    }
}
