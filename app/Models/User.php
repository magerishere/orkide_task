<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\Bank\Repository\BankAccountRepository;
use Modules\Bank\Repository\Contracts\BankAccountCardRepositoryInterface;
use Modules\Bank\Repository\Contracts\BankAccountRepositoryInterface;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'mobile',
        'first_name',
        'last_name',
        'email',
        'password',
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

    public function getRouteKeyName()
    {
        return 'mobile';
    }

    public function bankAccounts(): HasMany
    {

        return $this->hasMany(app(BankAccountRepositoryInterface::class)->getModel(), 'user_mobile');
    }

    public function bankAccountCards(): HasManyThrough
    {
        return $this->hasManyThrough(
            app(BankAccountCardRepositoryInterface::class)->getModel(),
            app(BankAccountRepositoryInterface::class)->getModel(),
            'user_mobile',
            'bank_account_number',
            'mobile',
            'number'
        );
    }
}
