<?php

namespace App\Http\Controllers;

use App\Models\Pipeline;
use App\Models\PipelineStage;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PipelineController extends Controller
{
    public function index(): View
    {
        $stages = PipelineStage::orderBy('order')->get();
        $pipelines = Pipeline::with('stage', 'creator')->get();
        
        $currentMenuId = 17;
        
        // ✅ Ubah path view sesuai lokasi file Anda
        return view('pages.pipeline', compact('stages', 'pipelines', 'currentMenuId'));
    }

    public function show(Pipeline $pipeline): View
    {
        $pipeline->load('stage', 'creator');
        return view('pipeline.show', compact('pipeline'));
    }

    public function create(): View
    {
        $stages = PipelineStage::orderBy('order')->get();
        return view('pipeline.create', compact('stages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'stage_id' => 'required|exists:pipeline_stages,id',
            'customer_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'description' => 'nullable|string',
            'value' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        Pipeline::create($validated);

        return redirect()->route('pipeline.index')
            ->with('success', 'Pipeline item created successfully!');
    }

    public function edit(Pipeline $pipeline): View
    {
        $stages = PipelineStage::orderBy('order')->get();
        return view('pipeline.edit', compact('pipeline', 'stages'));
    }

    public function update(Request $request, Pipeline $pipeline): RedirectResponse
    {
        $validated = $request->validate([
            'stage_id' => 'required|exists:pipeline_stages,id',
            'customer_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'description' => 'nullable|string',
            'value' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $pipeline->update($validated);

        return redirect()->route('pipeline.index')
            ->with('success', 'Pipeline item updated successfully!');
    }

    public function destroy(Pipeline $pipeline): RedirectResponse
    {
        $pipeline->delete();

        return redirect()->route('pipeline.index')
            ->with('success', 'Pipeline item deleted successfully!');
    }

    public function getByStage(PipelineStage $stage)
    {
        $pipelines = Pipeline::where('stage_id', $stage->id)
            ->with('creator')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($pipelines);
    }
}