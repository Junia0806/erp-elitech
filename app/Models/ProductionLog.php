<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionLog extends Model
{
    use HasFactory;

    protected $table = 'production_logs';
    
    protected $fillable = [
        'production_order_id',
        'user_id',
        'description',
    ];
    
    // Relasi: Log ini milik satu ProductionOrder
    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class);
    }
    
    // Relasi: Log ini dibuat oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}