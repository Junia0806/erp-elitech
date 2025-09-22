<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionOrder extends Model
{
    use HasFactory;

    protected $table = 'production_orders';

    protected $fillable = [
        'production_plan_id',
        'status',
        'quantity_actual',
        'quantity_reject',
        'completed_at',
    ];

    // Relasi: Order ini milik satu ProductionPlan
    public function productionPlan()
    {
        return $this->belongsTo(ProductionPlan::class);
    }

    public function items()
    {
        // hasMany(RelatedModel, foreign_key_di_tabel_related, local_key_di_tabel_ini)
        return $this->hasMany(ProductionItem::class, 'production_plan_id', 'production_plan_id');
    }

    // Relasi: Satu Order bisa memiliki banyak Log
    public function logs()
    {
        return $this->hasMany(ProductionLog::class);
    }
}