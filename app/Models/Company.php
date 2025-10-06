<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'company';
    protected $primaryKey = 'company_id';
    public $timestamps = false;

    protected $fillable = [
        'company_name', 'company_type_id', 'tier', 'description', 'status'
    ];

    public function companyType()
    {
        return $this->belongsTo(CompanyType::class, 'company_type_id');
    }
}