<?php

namespace App\Models;

use App\Enums\BillStatus;
use Carbon\Carbon;
use Database\Factories\BillFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'student_id',
    'academic_year_id',
    'period_month',
    'period_year',
    'amount',
    'paid_amount',
    'due_date',
    'status',
    'notes',
])]
class Bill extends Model
{
    /** @use HasFactory<BillFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'period_month' => 'integer',
            'period_year' => 'integer',
            'amount' => 'integer',
            'paid_amount' => 'integer',
            'due_date' => 'date',
            'status' => BillStatus::class,
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PaymentItem::class);
    }

    public function remainingAmount(): int
    {
        return max(0, $this->amount - $this->paid_amount);
    }

    public function isOutstanding(): bool
    {
        return $this->status !== BillStatus::Paid;
    }

    public function periodLabel(): string
    {
        return static::periodName($this->period_month).' '.$this->period_year;
    }

    public static function periodName(int $month): string
    {
        return Carbon::createFromDate(2000, $month, 1)
            ->locale('id')
            ->translatedFormat('F');
    }

    public static function monthOptions(): array
    {
        $options = [];

        for ($month = 1; $month <= 12; $month++) {
            $options[$month] = static::periodName($month);
        }

        return $options;
    }
}
