<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionPlan extends Model
{
    use HasFactory;

    protected $table = 'production_plans';

    protected $fillable = [
        'product_id',
        'created_by',
        'quantity',
        'status',
        'deadline',
        'approved_by',
        'approved_at',
    ];

    // Relasi: Plan ini milik satu Product
    public function products()
    {
        // Tambahkan 'production_item' sebagai argumen kedua
        return $this->belongsToMany(Product::class, 'production_item')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    // Relasi: Plan ini dibuat oleh satu User
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi: Plan ini disetujui oleh satu User
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    
    // Relasi: Satu Plan hanya memiliki satu ProductionOrder
    public function productionOrder()
    {
        return $this->hasOne(ProductionOrder::class);
    }
}