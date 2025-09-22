<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionItem extends Model
{
    use HasFactory;

    protected $table = 'production_item';

    protected $fillable = [
        'production_plan_id',
        'product_id',
        'quantity',
        'quantity_actual',
        'quantity_reject',
    ];

    /**
     * Relasi balik ke ProductionOrder.
     * Argumen kedua ('production_plan_id') secara eksplisit memberitahu Laravel
     * untuk menggunakan kolom ini sebagai foreign key.
     * * Ganti 'production_plan_id' dengan nama kolom yang benar di tabel Anda
     * jika Anda menamakannya berbeda.
     */
    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class, 'production_plan_id');
    }

    /**
     * Relasi balik ke Product.
     * Argumen kedua ('product_id') secara eksplisit memberitahu Laravel
     * untuk menggunakan kolom ini sebagai foreign key.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
