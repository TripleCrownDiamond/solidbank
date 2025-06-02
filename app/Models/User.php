<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method bool isRecoveryCodeValid(string $code)
 * @method void replaceRecoveryCodes(array $codes)
 * @method bool isTwoFactorAuthCodeValid(string $code)
 */
class User extends Authenticatable
{
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            // Delete associated files
            if ($user->identity_document_url) {
                Storage::disk('public')->delete($user->identity_document_url);
            }
            if ($user->address_document_url) {
                Storage::disk('public')->delete($user->address_document_url);
            }

            // Delete related models
            $user->account()->delete();
            $user->wallets()->delete();
            $user->transferSteps()->delete();
        });
    }

    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    public function getLocale()
    {
        return app()->getLocale();
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $locale = app()->getLocale() ?? 'fr';
        $this->notify(new \App\Notifications\ResetPasswordNotification($token, $locale));
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'email_verified_at',
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'profile_photo_path',
        'current_team_id',
        'name',
        'first_name',
        'last_name',
        'gender',
        'birth_date',
        'marital_status',
        'profession',
        'phone_number',
        'country_id',
        'region',
        'city',
        'postal_code',
        'address',
        'identity_document_url',
        'address_document_url',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
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

    public function account()
    {
        return $this->hasOne(Account::class);
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    public function transferSteps()
    {
        return $this->hasMany(TransferStep::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
