<div class="pipeline-card" 
     onclick="viewPipelineDetail({{ $pipeline->id }})"
     style="background-color: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 0.375rem; padding: 1rem; cursor: pointer; transition: all 0.2s; position: relative;">
    
    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 0.5rem;">
        <div style="flex: 1;">
            <h3 style="font-size: 0.95rem; font-weight: 600; color: #111827; margin: 0;">{{ $pipeline->customer_name }}</h3>
            <p style="font-size: 0.8rem; color: #6b7280; margin: 0.25rem 0 0 0;">{{ Str::limit($pipeline->description, 40) }}</p>
        </div>
        @if($pipeline->value)
            <div style="text-align: right;">
                <p style="font-size: 0.95rem; font-weight: 700; color: #059669; margin: 0;">Rp {{ number_format($pipeline->value, 0, ',', '.') }}</p>
            </div>
        @endif
    </div>

    <!-- Contact Info -->
    @if($pipeline->email || $pipeline->phone)
        <div style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid #e5e7eb;">
            @if($pipeline->phone)
                <p style="font-size: 0.75rem; color: #6b7280; margin: 0.25rem 0 0 0;">
                    <i class="fas fa-phone"></i> {{ $pipeline->phone }}
                </p>
            @endif
            @if($pipeline->email)
                <p style="font-size: 0.75rem; color: #6b7280; margin: 0.25rem 0 0 0;">
                    <i class="fas fa-envelope"></i> {{ $pipeline->email }}
                </p>
            @endif
        </div>
    @endif

    <!-- Footer Info -->
    <div style="margin-top: 0.75rem; display: flex; justify-content: space-between; align-items: center; font-size: 0.7rem; color: #9ca3af;">
        <span>{{ $pipeline->creator->name ?? 'Unknown' }}</span>
        <span>{{ $pipeline->created_at->format('d M') }}</span>
    </div>
</div>

<style>
    .pipeline-card:hover {
        background-color: #e5e7eb;
        border-color: #6366f1;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.15);
        transform: translateY(-2px);
    }
</style>