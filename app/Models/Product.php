<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'sku', 'description'];

    // Relasi: Satu Product bisa memiliki banyak ProductionPlan
    public function productionPlans()
    {
        // Tambahkan 'production_item' sebagai argumen kedua
        return $this->belongsToMany(ProductionPlan::class, 'production_item')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}