<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
    'transaction_id',
    'method',
    'paid_amount',
    'change_amount',
    'card_bank',
    'trace_number',
    ];

    protected function casts(): array
    {
        return [
            'paid_amount' => 'integer',
            'change_amount' => 'integer',
        ];
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
