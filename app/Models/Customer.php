<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Customer extends Model
{
    protected $fillable = [
        'customer_id',
        'name',
        'type',
        'email',
        'phone',
        'address',
        'status',
        'source',
        'pic',
        'notes',
        'contact_person_name',
        'contact_person_email',
        'contact_person_phone',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Boot untuk generate customer_id otomatis
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->customer_id)) {
                $model->customer_id = 'CUST-' . strtoupper(Str::random(8));
            }
        });
    }

    // Accessor untuk contact_person
    public function getContactPersonAttribute()
    {
        return [
            'name' => $this->contact_person_name,
            'email' => $this->contact_person_email,
            'phone' => $this->contact_person_phone,
        ];
    }

    // Scope untuk filter
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeBySource($query, $source)
    {
        return $query->where('source', $source);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
                     ->orWhere('email', 'like', "%{$search}%")
                     ->orWhere('phone', 'like', "%{$search}%")
                     ->orWhere('customer_id', 'like', "%{$search}%");
    }
}