@props(['stage', 'pipelines', 'currentMenuId'])

<div style="background-color: #f9fafb; border: 2px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem; min-height: 200px;">
    
    <!-- Stage Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 2px solid #d1d5db;">
        <div>
            <h4 style="font-size: 1rem; font-weight: 700; color: #111827; margin: 0;">
                {{ $stage->name }}
            </h4>
            @if($stage->description)
                <p style="font-size: 0.75rem; color: #6b7280; margin: 0.25rem 0 0 0;">
                    {{ $stage->description }}
                </p>
            @endif
        </div>
        <span style="background-color: #6366f1; color: white; font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.625rem; border-radius: 9999px;">
            {{ $pipelines->where('stage_id', $stage->id)->count() }}
        </span>
    </div>

    <!-- Pipeline Items -->
    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
        @forelse($pipelines->where('stage_id', $stage->id) as $pipeline)
            @include('components.pipeline.pipeline-card', ['pipeline' => $pipeline, 'currentMenuId' => $currentMenuId])
        @empty
            <div style="text-align: center; padding: 2rem; color: #9ca3af; font-size: 0.875rem;">
                <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.3;"></i>
                <p style="margin: 0;">Belum ada item</p>
            </div>
        @endforelse
    </div>
</div>
