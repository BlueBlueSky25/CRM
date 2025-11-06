<!-- Modal Edit Company -->
<div id="editCompanyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden animate-modal-in">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #3b82f6 100%); padding: 1rem 1.25rem;">
            <div class="flex justify-between items-center">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 2.5rem; height: 2.5rem; background-color: rgba(255, 255, 255, 0.2); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-building" style="color: white; font-size: 1.125rem;"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white">Edit Perusahaan</h3>
                </div>
                <button onclick="closeEditCompanyModal()" 
                    class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="overflow-y-auto max-h-[calc(90vh-120px)]" style="background-color: #f3f4f6; padding: 1rem;">
            <form id="editCompanyForm" method="POST" style="display: flex; flex-direction: column; gap: 0.75rem;">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_company_id" name="company_id">
                
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem;">
                    <!-- Nama Perusahaan -->
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                            Nama Perusahaan <span style="color: #ef4444;">*</span>
                        </label>
                        <div style="position: relative;">
                            <i class="fas fa-building" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.75rem;"></i>
                            <input type="text" 
                                id="edit_company_name"
                                name="company_name" 
                                style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem 0.5rem 2.25rem; font-size: 0.875rem;" 
                                placeholder="Masukkan nama perusahaan"
                                required>
                        </div>
                    </div>
                    
                    <!-- Jenis Perusahaan -->
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                            Jenis Perusahaan <span style="color: #ef4444;">*</span>
                        </label>
                        <div style="position: relative;">
                            <i class="fas fa-tag" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.75rem; z-index: 1; pointer-events: none;"></i>
                            <select id="edit_company_type_id" name="company_type_id" 
                                    style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 2rem 0.5rem 2.25rem; font-size: 0.875rem; appearance: none;" 
                                    required>
                                <option value="">-- Pilih Jenis --</option>
                                @foreach($types as $type)
                                <option value="{{ $type->company_type_id }}">{{ $type->type_name }}</option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.75rem; pointer-events: none;"></i>
                        </div>
                    </div>
                    
                    <!-- Tier -->
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                            Tier
                        </label>
                        <div style="position: relative;">
                            <i class="fas fa-layer-group" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.75rem; z-index: 1; pointer-events: none;"></i>
                            <select id="edit_tier" name="tier" 
                                    style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 2rem 0.5rem 2.25rem; font-size: 0.875rem; appearance: none;">
                                <option value="">-- Pilih Tier --</option>
                                <option value="A">Tier A</option>
                                <option value="B">Tier B</option>
                                <option value="C">Tier C</option>
                                <option value="D">Tier D</option>
                            </select>
                            <i class="fas fa-chevron-down" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.75rem; pointer-events: none;"></i>
                        </div>
                    </div>
                    
                    <!-- Status -->
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                            Status
                        </label>
                        <div style="position: relative;">
                            <i class="fas fa-toggle-on" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.75rem; z-index: 1; pointer-events: none;"></i>
                            <select id="edit_status" name="status" 
                                    style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 2rem 0.5rem 2.25rem; font-size: 0.875rem; appearance: none;">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <i class="fas fa-chevron-down" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.75rem; pointer-events: none;"></i>
                        </div>
                    </div>
                    
                    <!-- Deskripsi -->
                    <div style="grid-column: span 2;">
                        <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                            Deskripsi
                        </label>
                        <div style="position: relative;">
                            <i class="fas fa-align-left" style="position: absolute; left: 0.75rem; top: 0.625rem; color: #9ca3af; font-size: 0.75rem;"></i>
                            <textarea id="edit_description" name="description" 
                                      rows="2" 
                                      style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem 0.5rem 2.25rem; font-size: 0.875rem; resize: none;" 
                                      placeholder="Tambahkan keterangan tentang perusahaan..."></textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Tombol -->
                <div style="display: flex; justify-content: flex-end; gap: 0.5rem; padding-top: 0.75rem; border-top: 1px solid #e5e7eb; margin-top: 0.5rem;">
                    <button type="button" 
                            onclick="closeEditCompanyModal()" 
                            style="background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 1.25rem; font-weight: 500; font-size: 0.75rem; cursor: pointer; transition: all 0.2s;">
                        <i class="fas fa-times" style="margin-right: 0.375rem;"></i>
                        Batal
                    </button>
                    <button type="submit" 
                            style="background-color: #3b82f6; color: white; border: none; border-radius: 0.5rem; padding: 0.5rem 1.25rem; font-weight: 500; font-size: 0.75rem; cursor: pointer; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3); transition: all 0.2s;">
                        <i class="fas fa-save" style="margin-right: 0.375rem;"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
@keyframes modal-in {
    from {
        opacity: 0;
        transform: scale(0.95) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.animate-modal-in {
    animation: modal-in 0.3s ease-out;
}

/* Custom select dropdown arrow hide default */
select::-ms-expand {
    display: none;
}

/* Smooth transitions for all inputs */
input:focus, select:focus, textarea:focus {
    outline: none;
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
}

/* Custom Scrollbar */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Responsive */
@media (max-width: 768px) {
    .bg-white {
        max-width: calc(100% - 2rem) !important;
    }
    
    div[style*="grid-template-columns: repeat(2, 1fr)"] {
        grid-template-columns: 1fr !important;
    }
}
</style>

<script>
function openEditCompanyModal(companyId, companyName, companyTypeId, tier, description, status) {
    console.log('=== DEBUG OPEN EDIT MODAL ===');
    console.log({ companyId, companyName, companyTypeId, tier, description, status });

    // Set form action dinamis
    const form = document.getElementById('editCompanyForm');
    form.action = `/company/${companyId}`;

    document.getElementById('edit_company_id').value = companyId || '';
    document.getElementById('edit_company_name').value = companyName || '';
    document.getElementById('edit_description').value = description || '';
    document.getElementById('edit_status').value = status || 'active';

    // Jenis
    const typeSelect = document.getElementById('edit_company_type_id');
    for (let opt of typeSelect.options) {
        opt.selected = (opt.value == companyTypeId);
    }

    // Tier
    const tierSelect = document.getElementById('edit_tier');
    for (let opt of tierSelect.options) {
        opt.selected = (opt.value.toLowerCase() === String(tier).toLowerCase());
    }

    // Show modal
    document.getElementById('editCompanyModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditCompanyModal() {
    document.getElementById('editCompanyModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('editCompanyForm').reset();
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('editCompanyModal');
    if (e.target === modal) {
        closeEditCompanyModal();
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('editCompanyModal');
        if (modal && !modal.classList.contains('hidden')) {
            closeEditCompanyModal();
        }
    }
});
</script>