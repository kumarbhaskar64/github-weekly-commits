<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\CarbonImmutable;

class WeeklyCommitsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */    
    public function rules(): array
    {
        return [
            'since' => ['nullable', 'date_format:Y-m-d'],
            'until' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:since'],
            'max_pages' => ['nullable', 'integer', 'min:1', 'max:500'],
        ];
    }

    public function maxRangeDays(): int
    {
        return (int) config('services.github.max_range_days', 180);
    }

    public function maxPages(): int
    {
        return (int) ($this->validated('max_pages') ?? config('services.github.max_pages_default', 20));
    }

    public function sinceDt(): CarbonImmutable
    {
        $maxRangeDays = $this->maxRangeDays();
        $since = $this->validated('since');

        return $since
            ? CarbonImmutable::parse($since)->startOfDay()
            : CarbonImmutable::today()->subDays($maxRangeDays)->startOfDay();
    }

    public function untilDt(): CarbonImmutable
    {
        $until = $this->validated('until');

        return $until ? CarbonImmutable::parse($until)->endOfDay() : CarbonImmutable::today()->endOfDay();
    }

    public function ensureRangeAllowed(): void
    {
        if ($this->sinceDt()->diffInDays($this->untilDt()) > $this->maxRangeDays()) {
            abort(422, "Date range cannot exceed {$this->maxRangeDays()} days.");
        }
    }

    
}