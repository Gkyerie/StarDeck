<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'member_id',
        'unit_id',
        'processed_by',
        'borrowed_at',
        'due_date',
        'returned_at',
        'status',
        'late_days',
        'fine_amount',
    ];

    protected function casts(): array
    {
        return [
            'borrowed_at' => 'date',
            'due_date' => 'date',
            'returned_at' => 'date',
            'late_days' => 'integer',
            'fine_amount' => 'decimal:2',
        ];
    }

    /**
     * The member who borrowed this unit.
     * Uses withTrashed so history is visible even after soft delete.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id')->withTrashed();
    }

    /**
     * The unit that was borrowed.
     * Uses withTrashed so history is visible even after soft delete.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class)->withTrashed();
    }

    /**
     * The admin who processed the borrowing.
     * Uses withTrashed so history is visible even after soft delete.
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by')->withTrashed();
    }

    public function isBorrowed(): bool
    {
        return $this->status === 'borrowed';
    }

    public function isReturned(): bool
    {
        return $this->status === 'returned';
    }

    /**
     * Check if the loan is overdue (active and past due date).
     */
    public function isOverdue(): bool
    {
        return $this->isBorrowed() && today()->greaterThan($this->due_date);
    }

    /**
     * Get estimated late days for an active loan (not final until returned).
     */
    public function estimatedLateDays(): int
    {
        if ($this->isReturned()) {
            return $this->late_days;
        }

        return max(0, (int) today()->diffInDays($this->due_date, false) * -1);
    }

    /**
     * Get estimated fine for an active loan (not final until returned).
     */
    public function estimatedFine(): int
    {
        return $this->estimatedLateDays() * 5000;
    }
}
