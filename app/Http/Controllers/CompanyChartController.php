<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\CompanyType;

class CompanyChartController extends Controller
{
    public function index()
    {
        $companies = Company::with('type')->get();
        $companyTypes = CompanyType::all();

        // Convert Collections to arrays untuk JavaScript
        $tierGrouped = $companies->groupBy('tier');
        $statusGrouped = $companies->groupBy('status');

        // Build chart data dengan explicit array conversion
        $chartCompanyData = [
            'type' => [
                'labels' => $companyTypes->pluck('type_name')->toArray(),
                'data' => $companyTypes->map(function($type) use ($companies) {
                    return $companies->where('company_type_id', $type->company_type_id)->count();
                })->values()->toArray(), // tambah values() untuk re-index
                'details' => $companyTypes->mapWithKeys(function($type) use ($companies) {
                    return [
                        $type->type_name => $companies->where('company_type_id', $type->company_type_id)
                            ->pluck('company_name')->toArray()
                    ];
                })->toArray()
            ],
            'tier' => [
                'labels' => $tierGrouped->keys()->toArray(),
                'data' => $tierGrouped->map(function($group) {
                    return $group->count();
                })->values()->toArray(),
                'details' => $tierGrouped->map(function($group) {
                    return $group->pluck('company_name')->toArray();
                })->toArray()
            ],
            'status' => [
                'labels' => $statusGrouped->keys()->toArray(),
                'data' => $statusGrouped->map(function($group) {
                    return $group->count();
                })->values()->toArray(),
                'details' => $statusGrouped->map(function($group) {
                    return $group->pluck('company_name')->toArray();
                })->toArray()
            ]
        ];

        // Legacy chartData untuk backward compatibility
        $chartData = [
            'tier' => $tierGrouped->map(function($group) {
                return $group->count();
            }),
            'status' => $statusGrouped->map(function($group) {
                return $group->count();
            }),
        ];

        // Debug: uncomment ini jika masih ada masalah
        // dd($chartCompanyData);

        return view('layout.dashboard', compact('companies', 'companyTypes', 'chartData', 'chartCompanyData'));
    }
}