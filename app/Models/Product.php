<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
    'category_id',
    'supplier_id',
    'barcode',
    'name',
    'purchase_price',
    'selling_price',
    'stock',
    'minimum_stock',
    'expired_date',
    'image',
    ];

    protected function casts(): array
    {
        return [
            'purchase_price' => 'integer',
            'selling_price' => 'integer',
            'stock' => 'integer',
            'minimum_stock' => 'integer',
            'expired_date' => 'date',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'minimum_stock');
    }

    public function scopeExpired($query)
    {
        return $query->whereNotNull('expired_date')
            ->whereDate('expired_date', '<=', now());
    }

    public function scopeAlmostExpired($query, int $days = 30)
    {
        return $query->whereNotNull('expired_date')
            ->whereDate('expired_date', '>', now())
            ->whereDate('expired_date', '<=', now()->addDays($days));
    }
}
