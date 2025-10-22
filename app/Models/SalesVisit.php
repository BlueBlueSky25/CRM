<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesVisit extends Model
{
    use HasFactory;

    protected $table = 'sales_visits';

    protected $fillable = [
    'sales_id',
    'customer_name',
    'company',
    'province_id',
    'regency_id',  // ← PENTING
    'district_id',  // ← PENTING
    'village_id',   // ← PENTING
    'address',      // ← PENTING
    'visit_date',
    'purpose',
    'is_follow_up',
    'user_id'
];
    protected $casts = [
        'visit_date' => 'date',
        'is_follow_up' => 'boolean',
    ];


     public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id', 'user_id');
    }

    
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    /**
     * Scope untuk filter berdasarkan sales
     */
    public function scopeFilterBySales($query, $salesId)
    {
        if ($salesId) {
            return $query->where('sales_id', $salesId);
        }
        return $query;
    }

    /**
     * Scope untuk filter berdasarkan follow up
     */
    public function scopeFilterByFollowUp($query, $followUp)
    {
        if ($followUp !== null && $followUp !== '') {
            return $query->where('is_follow_up', $followUp === 'Ya' || $followUp === '1' ? 1 : 0);
        }
        return $query;
    }

    /**
     * Scope untuk search
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%")
                  ->orWhereHas('sales', function($q) use ($search) {
                      $q->where('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('province', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }
        return $query;
    }
}