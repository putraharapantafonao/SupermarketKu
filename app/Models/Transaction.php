<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_number',
        'user_id',
        'customer_id',
        'total_price',
        'discount',
        'tax',
        'grand_total',
    ];

    protected function casts(): array
    {
        return [
            'total_price' => 'integer',
            'discount' => 'integer',
            'tax' => 'integer',
            'grand_total' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function scopeSalesByDay($query, int $days = 7)
    {
        $query->whereDate('created_at', '>=', now()->subDays($days));

        if ($query->getConnection()->getDriverName() === 'sqlite') {
            return $query->selectRaw("DATE(created_at) as date, SUM(grand_total) as total")
                ->groupByRaw("DATE(created_at)")
                ->orderBy('date');
        }

        return $query->selectRaw('DATE(created_at) as date, SUM(grand_total) as total')
            ->groupBy('date')
            ->orderBy('date');
    }

    public function scopeSalesByMonth($query, ?int $year = null)
    {
        $year ??= now()->year;

        if ($query->getConnection()->getDriverName() === 'sqlite') {
            return $query->selectRaw("strftime('%m', created_at) as month, SUM(grand_total) as total")
                ->whereRaw("strftime('%Y', created_at) = ?", [(string) $year])
                ->groupBy('month')
                ->orderBy('month');
        }

        return $query->selectRaw('MONTH(created_at) as month, SUM(grand_total) as total')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month');
    }
}
