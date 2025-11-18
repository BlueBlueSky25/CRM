<div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb; background-color: #f9fafb;">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <!-- Total Transaksi -->
        <div style="background-color: white; padding: 1rem; border-radius: 0.5rem; border-left: 4px solid #3b82f6;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 3rem; height: 3rem; background-color: #dbeafe; border-radius: 0.375rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-file-invoice" style="font-size: 1.5rem; color: #2563eb;"></i>
                </div>
                <div>
                    <p style="font-size: 0.75rem; color: #6b7280; font-weight: 500; margin: 0; text-transform: uppercase;">Total Transaksi</p>
                    <p style="font-size: 1.875rem; font-weight: 700; color: #111827; margin: 0.25rem 0 0 0;">{{ count($transaksi) }}</p>
                </div>
            </div>
        </div>

        <!-- Deals -->
        <div style="background-color: white; padding: 1rem; border-radius: 0.5rem; border-left: 4px solid #22c55e;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 3rem; height: 3rem; background-color: #dcfce7; border-radius: 0.375rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check-circle" style="font-size: 1.5rem; color: #16a34a;"></i>
                </div>
                <div>
                    <p style="font-size: 0.75rem; color: #6b7280; font-weight: 500; margin: 0; text-transform: uppercase;">Deals</p>
                    <p style="font-size: 1.875rem; font-weight: 700; color: #16a34a; margin: 0.25rem 0 0 0;">{{ count($transaksi->where('status', 'Deals')) }}</p>
                </div>
            </div>
        </div>

        <!-- Fails -->
        <div style="background-color: white; padding: 1rem; border-radius: 0.5rem; border-left: 4px solid #ef4444;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 3rem; height: 3rem; background-color: #fee2e2; border-radius: 0.375rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-times-circle" style="font-size: 1.5rem; color: #dc2626;"></i>
                </div>
                <div>
                    <p style="font-size: 0.75rem; color: #6b7280; font-weight: 500; margin: 0; text-transform: uppercase;">Fails</p>
                    <p style="font-size: 1.875rem; font-weight: 700; color: #dc2626; margin: 0.25rem 0 0 0;">{{ count($transaksi->where('status', 'Fails')) }}</p>
                </div>
            </div>
        </div>

        <!-- Total Nilai -->
        <div style="background-color: white; padding: 1rem; border-radius: 0.5rem; border-left: 4px solid #8b5cf6;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 3rem; height: 3rem; background-color: #ede9fe; border-radius: 0.375rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-money-bill-wave" style="font-size: 1.5rem; color: #7c3aed;"></i>
                </div>
                <div>
                    <p style="font-size: 0.75rem; color: #6b7280; font-weight: 500; margin: 0; text-transform: uppercase;">Total Nilai</p>
                    <p style="font-size: 1.25rem; font-weight: 700; color: #7c3aed; margin: 0.25rem 0 0 0;">Rp{{ number_format($transaksi->sum('nilai_proyek'), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>