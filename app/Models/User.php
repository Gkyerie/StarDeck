<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    public function memberProfile(): HasOne
    {
        return $this->hasOne(MemberProfile::class);
    }

    /**
     * Loans where this user is the borrower (member).
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class, 'member_id');
    }

    /**
     * Loans processed by this user (admin).
     */
    public function processedLoans(): HasMany
    {
        return $this->hasMany(Loan::class, 'processed_by');
    }

    /**
     * Count of active (borrowed) loans for this member.
     */
    public function activeLoanCount(): int
    {
        return $this->loans()->where('status', 'borrowed')->count();
    }
}
