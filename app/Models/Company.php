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
        'user_id'
    ];

    public function companyType()
    {
        return $this->belongsTo(CompanyType::class, 'company_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relasi ke CompanyPic
    public function pics()
    {
        return $this->hasMany(CompanyPic::class, 'company_id', 'company_id');
    }
}