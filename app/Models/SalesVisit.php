<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesVisit extends Model
{
    use HasFactory;

    protected $table = 'sales_visits';
    protected $primaryKey = 'id';

    protected $fillable = [
        'sales_id',
        'user_id',
        'customer_name',
        'company_name',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
        'address',
        'visit_date',
        'visit_purpose',
        'is_follow_up',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'is_follow_up' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke User (Sales)
     */
    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id', 'user_id');
    }

    /**
     * Relasi ke User (Creator/Updater)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Relasi ke Province
     */
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    /**
     * Relasi ke Regency
     */
    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_id');
    }

    /**
     * Relasi ke District
     */
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    /**
     * Relasi ke Village
     */
    public function village()
    {
        return $this->belongsTo(Village::class, 'village_id');
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
            return $query->where('is_follow_up', $followUp === 'Ya' || $followUp === '1' || $followUp === 1);
        }
        return $query;
    }

    /**
     * Scope untuk filter berdasarkan province
     */
    public function scopeFilterByProvince($query, $provinceId)
    {
        if ($provinceId) {
            return $query->where('province_id', $provinceId);
        }
        return $query;
    }

    /**
     * Scope untuk filter berdasarkan regency
     */
    public function scopeFilterByRegency($query, $regencyId)
    {
        if ($regencyId) {
            return $query->where('regency_id', $regencyId);
        }
        return $query;
    }

    /**
     * Scope untuk search
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            $searchLower = strtolower($search);
            
            return $query->where(function($q) use ($searchLower) {
                $q->whereRaw('LOWER(customer_name) LIKE ?', ["%{$searchLower}%"])
                  ->orWhereRaw('LOWER(company_name) LIKE ?', ["%{$searchLower}%"])
                  ->orWhereRaw('LOWER(visit_purpose) LIKE ?', ["%{$searchLower}%"])
                  ->orWhereRaw('LOWER(address) LIKE ?', ["%{$searchLower}%"])
                  ->orWhereHas('sales', function($qt) use ($searchLower) {
                      $qt->whereRaw('LOWER(username) LIKE ?', ["%{$searchLower}%"])
                         ->orWhereRaw('LOWER(email) LIKE ?', ["%{$searchLower}%"]);
                  })
                  ->orWhereHas('province', function($qt) use ($searchLower) {
                      $qt->whereRaw('LOWER(name) LIKE ?', ["%{$searchLower}%"]);
                  })
                  ->orWhereHas('regency', function($qt) use ($searchLower) {
                      $qt->whereRaw('LOWER(name) LIKE ?', ["%{$searchLower}%"]);
                  });
            });
        }
        return $query;
    }
}