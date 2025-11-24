<!-- Modal Edit Pipeline -->
<div id="pipelineEditModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background-color: white; border-radius: 0.5rem; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);">
        
        <!-- Modal Header -->
        <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #111827; margin: 0;">Edit Item Pipeline</h2>
            <button onclick="closePipelineEditModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #6b7280;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="pipelineEditForm" method="POST" style="padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem;">
            @csrf
            @method('PUT')

            <!-- Stage Selection -->
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">
                    Stage <span style="color: #ef4444;">*</span>
                </label>
                <select name="stage_id" id="editStageId" required 
                    style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; font-family: inherit;">
                    <option value="">-- Pilih Stage --</option>
                    @foreach($stages as $stage)
                        <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Customer Name -->
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">
                    Nama Customer <span style="color: #ef4444;">*</span>
                </label>
                <input type="text" id="editCustomerName" name="customer_name" required 
                    style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; font-family: inherit;">
            </div>

            <!-- Phone & Email -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">Telepon</label>
                    <input type="tel" id="editPhone" name="phone" 
                        style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; font-family: inherit;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">Email</label>
                    <input type="email" id="editEmail" name="email" 
                        style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; font-family: inherit;">
                </div>
            </div>

            <!-- Value -->
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">Nilai Deal (Rp)</label>
                <input type="number" id="editValue" name="value" step="0.01" 
                    style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; font-family: inherit;">
            </div>

            <!-- Description -->
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">Deskripsi</label>
                <textarea id="editDescription" name="description" rows="3" 
                    style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; font-family: inherit;"></textarea>
            </div>

            <!-- Notes -->
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">Catatan</label>
                <textarea id="editNotes" name="notes" rows="2" 
                    style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; font-family: inherit;"></textarea>
            </div>

            <!-- Buttons -->
            <div style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1rem;">
                <button type="button" onclick="closePipelineEditModal()" 
                    style="padding: 0.625rem 1rem; background-color: #e5e7eb; color: #111827; border: none; border-radius: 0.375rem; font-weight: 500; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" 
                    style="padding: 0.625rem 1rem; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; border: none; border-radius: 0.375rem; font-weight: 500; cursor: pointer;">
                    Perbarui
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function closePipelineEditModal() {
    document.getElementById('pipelineEditModal').style.display = 'none';
    document.getElementById('pipelineEditForm').reset();
}

// Close modal when clicking outside
document.getElementById('pipelineEditModal')?.addEventListener('click', function(e) {
    if (e.target === this) closePipelineEditModal();
});
</script>