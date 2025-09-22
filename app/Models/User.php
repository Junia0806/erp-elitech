<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'module',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi: Satu User (PPIC) bisa membuat banyak ProductionPlan
    public function createdPlans()
    {
        return $this->hasMany(ProductionPlan::class, 'created_by');
    }

    // Relasi: Satu User (Manager) bisa menyetujui banyak ProductionPlan
    public function approvedPlans()
    {
        return $this->hasMany(ProductionPlan::class, 'approved_by');
    }
    
    // Relasi: Satu User bisa membuat banyak log
    public function productionLogs()
    {
        return $this->hasMany(ProductionLog::class, 'user_id');
    }
}