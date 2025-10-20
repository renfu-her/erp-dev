<?php

namespace App\Support;

use App\Models\InsuranceBracket;
use Illuminate\Support\Str;

class InsuranceSchedule
{
    /**
     * @var array<int, array<string, mixed>>
     */
    protected array $brackets = [];

    public static function resolve(?string $path = null): self
    {
        if ($schedule = self::fromDatabase()) {
            return $schedule;
        }

        return self::fromStorage($path);
    }

    public static function fromDatabase(): ?self
    {
        $records = InsuranceBracket::orderBy('grade')->get();

        if ($records->isEmpty()) {
            return null;
        }

        $brackets = $records->map(function (InsuranceBracket $bracket) {
            return [
                'label' => $bracket->label,
                'grade' => $bracket->grade,
                'labor_employee_local' => $bracket->labor_employee_local,
                'labor_employer_local' => $bracket->labor_employer_local,
                'labor_employee_foreign' => $bracket->labor_employee_foreign,
                'labor_employer_foreign' => $bracket->labor_employer_foreign,
                'health_employee' => $bracket->health_employee,
                'health_employer' => $bracket->health_employer,
                'pension_employer' => $bracket->pension_employer,
            ];
        })->values()->all();

        return new self($brackets);
    }

    public static function fromStorage(?string $path = null): self
    {
        $path = $path ?? storage_path('salary_table.json');

        if (! file_exists($path)) {
            throw new \RuntimeException("Insurance schedule file not found at {$path}");
        }

        $raw = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);

        return new self(self::parseRawTable($raw));
    }

    /**
     * @param  array<int, array<int, mixed>>  $rows
     * @return array<int, array<string, mixed>>
     */
    protected static function parseRawTable(array $rows): array
    {
        $parsed = [];

        foreach ($rows as $row) {
            if (! isset($row[0]) || ! is_string($row[0])) {
                continue;
            }

            $label = trim(str_replace("\n", '', $row[0]));

            if ($label === '' || ! preg_match('/\d/', $label)) {
                continue;
            }

            if (! isset($row[1]) || ! preg_match('/\d/', (string) $row[1])) {
                continue;
            }

            $grade = self::extractGrade($label);

            $parsed[] = [
                'label' => $label,
                'grade' => $grade,
                'labor_employee_local' => self::toInt($row[1] ?? null),
                'labor_employer_local' => self::toInt($row[2] ?? null),
                'labor_employee_foreign' => self::toInt($row[3] ?? null),
                'labor_employer_foreign' => self::toInt($row[4] ?? null),
                'health_employee' => self::toInt($row[5] ?? null),
                'health_employer' => self::toInt($row[6] ?? null),
                'pension_employer' => self::toInt($row[7] ?? null),
            ];
        }

        usort($parsed, fn ($a, $b) => $a['grade'] <=> $b['grade']);

        return $parsed;
    }

    protected static function extractGrade(string $label): int
    {
        $normalized = Str::of($label)->replace(['以上', '以下'], '');

        if (Str::contains($normalized, '-')) {
            [$min, $max] = explode('-', $normalized);

            return (int) filter_var($max, FILTER_SANITIZE_NUMBER_INT);
        }

        return (int) filter_var($normalized, FILTER_SANITIZE_NUMBER_INT);
    }

    protected static function toInt($value): ?int
    {
        if (is_null($value)) {
            return null;
        }

        $numeric = filter_var((string) $value, FILTER_SANITIZE_NUMBER_INT);

        return $numeric === '' ? null : (int) $numeric;
    }

    public function __construct(array $brackets)
    {
        $this->brackets = $brackets;
    }

    public function brackets(): array
    {
        return $this->brackets;
    }

    public function findBracketForSalary(float $salary): ?array
    {
        if (empty($this->brackets)) {
            return null;
        }

        foreach ($this->brackets as $bracket) {
            if ($salary <= $bracket['grade']) {
                return $bracket;
            }
        }

        return $this->brackets[array_key_last($this->brackets)];
    }

    public function findBracketByGrade(int $grade): ?array
    {
        if (empty($this->brackets)) {
            return null;
        }

        foreach ($this->brackets as $bracket) {
            if (($bracket['grade'] ?? null) === $grade) {
                return $bracket;
            }
        }

        return null;
    }
}
