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
    public function product()
    {
        return $this->belongsTo(Product::class);
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