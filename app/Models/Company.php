<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'company';
    protected $primaryKey = 'company_id';
    public $timestamps = false;

    protected $fillable = [
        'company_name', 
        'company_type_id', 
        'tier', 
        'description', 
        'status',
        'user_id',
        // Address fields
        'address',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
        // Contact & Media fields
        'phone',
        'email',
        'website',
        'linkedin',
        'instagram',
        'logo',
        // Timestamps (jika ada di DB)
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ============= RELATIONSHIPS =============

    public function companyType()
    {
        return $this->belongsTo(CompanyType::class, 'company_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function pics()
    {
        return $this->hasMany(CompanyPic::class, 'company_id', 'company_id');
    }

    // Address relations
    public function province()
    {
        return $this->belongsTo(\App\Models\Province::class, 'province_id', 'id');
    }

    public function regency()
    {
        return $this->belongsTo(\App\Models\Regency::class, 'regency_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo(\App\Models\District::class, 'district_id', 'id');
    }

    public function village()
    {
        return $this->belongsTo(\App\Models\Village::class, 'village_id', 'id');
    }

    // ============= ACCESSORS =============

    /**
     * Get company nama (fallback untuk compatibility)
     */
    public function getNamaAttribute()
    {
        return $this->company_name;
    }

    /**
     * Get full address dengan region
     */
    public function getFullAddressAttribute()
    {
        $parts = [];
        
        if ($this->address) {
            $parts[] = $this->address;
        }
        
        if ($this->village && $this->village->nama) {
            $parts[] = $this->village->nama;
        }
        
        if ($this->district && $this->district->nama) {
            $parts[] = $this->district->nama;
        }
        
        if ($this->regency && $this->regency->nama) {
            $parts[] = $this->regency->nama;
        }
        
        if ($this->province && $this->province->nama) {
            $parts[] = $this->province->nama;
        }
        
        return implode(', ', $parts) ?: 'N/A';
    }

    /**
     * Get company display name
     */
    public function getDisplayNameAttribute()
    {
        return $this->company_name ?? 'Perusahaan Tidak Diketahui';
    }
}