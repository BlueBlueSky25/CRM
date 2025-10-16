<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'customer_id',
        'name',
        'type', // Personal/Company
        'email',
        'phone',
        'address',
        'status', // Lead, Prospect, Active, Inactive
        'source', // Website, Referral, Ads, Walk-in, Social Media
        'pic', // Assigned salesperson
        'notes',
        // Contact Person (untuk Company)
        'contact_person_name',
        'contact_person_email',
        'contact_person_phone',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Auto-generate Customer ID
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($customer) {
            if (empty($customer->customer_id)) {
                $lastCustomer = self::orderBy('id', 'desc')->first();
                $lastNumber = $lastCustomer ? intval(substr($lastCustomer->customer_id, 4)) : 0;
                $customer->customer_id = 'CUST' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    // Accessor untuk contact person sebagai object
    public function getContactPersonAttribute()
    {
        if ($this->type === 'Company' && $this->contact_person_name) {
            return [
                'name' => $this->contact_person_name,
                'email' => $this->contact_person_email,
                'phone' => $this->contact_person_phone,
            ];
        }
        return null;
    }

    // Scope untuk filter
    public function scopeByType($query, $type)
    {
        return $type ? $query->where('type', $type) : $query;
    }

    public function scopeByStatus($query, $status)
    {
        return $status ? $query->where('status', $status) : $query;
    }

    public function scopeBySource($query, $source)
    {
        return $source ? $query->where('source', $source) : $query;
    }

    public function scopeSearch($query, $search)
    {
        return $search ? $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('customer_id', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        }) : $query;
    }
}