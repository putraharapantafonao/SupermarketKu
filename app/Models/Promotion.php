<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;
    protected $fillable = [
    'name',
    'product_id',
    'type',
    'value',
    'start_date',
    'end_date',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
