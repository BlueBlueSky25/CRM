<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    use SoftDeletes;

    protected $table = 'transaksi';

    protected $fillable = [
        'sales_visit_id',
        'sales_id',
        'company_id',
        'nama_sales',
        'nama_perusahaan',
        'nilai_proyek',
        'status',
        'bukti_spk',
        'bukti_dp',
        'tanggal_mulai_kerja',
        'tanggal_selesai_kerja',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_mulai_kerja' => 'date',
        'tanggal_selesai_kerja' => 'date',
        'nilai_proyek' => 'decimal:2',
    ];

    public function salesVisit()
    {
        return $this->belongsTo(SalesVisit::class, 'sales_visit_id');
    }

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id', 'user_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }
}