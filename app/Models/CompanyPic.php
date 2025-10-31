<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyPic extends Model
{
    use HasFactory;

    protected $table = 'company_pics';
    protected $primaryKey = 'pic_id';
    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'name',
        'position',
        'phone',
        'email',
    ];

    // Relationship dengan Company
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }
}
