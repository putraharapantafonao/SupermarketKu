<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// 💡 PENTING: Memastikan properti mass assignment mengizinkan role_id, name, email, dan password
#[Fillable(['name', 'email', 'password', 'role_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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

    /**
     * Relasi ke model Role (Banyak User memiliki satu Role)
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relasi ke model Transaction (Kasir/Owner mencatat banyak transaksi)
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Relasi ke model Purchase (Staf logistik/Owner mencatat banyak pembelian stok)
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
