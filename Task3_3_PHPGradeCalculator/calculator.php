<?php
declare(strict_types=1);

/** Validate raw form values before any arithmetic. */
function validate_marks(array $input): array
{
    $errors = [];
    foreach (['activities', 'project', 'examination'] as $field) {
        $value = $input[$field] ?? null;
        if (!is_scalar($value) || is_bool($value) || !is_numeric($value)
            || !is_finite((float)$value) || (float)$value < 0 || (float)$value > 100) {
            $errors[$field] = ucfirst($field) . ': enter a numeric mark from 0 to 100.';
        }
    }
    return $errors;
}

function calculate_grade(array $input): array
{
    if (validate_marks($input)) throw new InvalidArgumentException('Invalid marks.');
    $total = round((float)$input['activities'] * 0.2 + (float)$input['project'] * 0.4 + (float)$input['examination'] * 0.4, 2, PHP_ROUND_HALF_UP);
    $grade = match (true) {
        $total >= 80 => 'High Distinction',
        $total >= 70 => 'Distinction',
        $total >= 60 => 'Credit',
        $total >= 50 => 'Pass',
        default => 'Fail'
    };
    return ['total' => $total, 'grade' => $grade];
}

function escape_html(mixed $value): string
{
    return htmlspecialchars(is_scalar($value) ? (string)$value : '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
