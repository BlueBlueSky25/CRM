<?php

namespace App\Http\Controllers;

use App\Models\Pipeline;
use App\Models\PipelineStage;
use Illuminate\View\View;
use Illuminate\Http\Request;

class PipelineController extends Controller
{
    public function index(): View
    {
        // Dummy data untuk table
        $pipelines = [
            [
                'id' => 1,
                'nama' => 'PT Maju Jaya',
                'email' => 'contact@majujaya.com',
                'phone' => '081234567890',
                'value' => 500000000,
                'date' => '2025-11-20',
                'notes' => 'Leads potensial'
            ],
            [
                'id' => 2,
                'nama' => 'CV Sukses Bersama',
                'email' => 'info@suksesbersama.com',
                'phone' => '082345678901',
                'value' => 750000000,
                'date' => '2025-11-21',
                'notes' => 'Follow up minggu depan'
            ],
            [
                'id' => 3,
                'nama' => 'PT Teknologi Indonesia',
                'email' => 'sales@tekindo.com',
                'phone' => '083456789012',
                'value' => 1000000000,
                'date' => '2025-11-22',
                'notes' => 'Menunggu approval'
            ],
            [
                'id' => 4,
                'nama' => 'Toko Barokah',
                'email' => 'toko@barokah.com',
                'phone' => '084567890123',
                'value' => 250000000,
                'date' => '2025-11-23',
                'notes' => 'Sudah bertemu'
            ],
        ];

        return view('pages.pipeline', compact('pipelines'));
    }

    public function show($id): View
    {
        // Dummy show detail
        $pipeline = [
            'id' => $id,
            'nama' => 'PT Maju Jaya',
            'email' => 'contact@majujaya.com',
            'phone' => '081234567890',
            'value' => 500000000,
            'date' => '2025-11-20',
            'notes' => 'Leads potensial yang sangat menjanjikan'
        ];

        return view('components.pipeline.show', compact('pipeline'));
    }
}