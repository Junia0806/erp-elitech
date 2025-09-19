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
        return $this->hasMany(ProductionPlan::class);
    }
}