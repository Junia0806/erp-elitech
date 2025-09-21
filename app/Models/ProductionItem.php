<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionItem extends Model
{
   use HasFactory;

    protected $fillable = [
        'production_order_id',
        'product_id',
        'quantity_target',
        'quantity_actual',
        'quantity_reject',
    ];

    // Opsional: definisikan relasi balik jika perlu
    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
