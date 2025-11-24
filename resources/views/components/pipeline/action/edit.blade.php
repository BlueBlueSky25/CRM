<!-- Modal Edit Pipeline -->
<div id="editPipelineModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl max-h-[95vh] overflow-hidden animate-fadeIn">
        
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #3b82f6 100%);">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-edit text-white text-lg"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white">Edit Item Pipeline</h3>
                </div>
                <button onclick="closePipelineEditModal()" class="text-white hover:text-gray-200 transition-colors p-2">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <form id="pipelineEditForm" method="POST" class="overflow-y-auto max-h-[calc(95vh-140px)]">
            @csrf
            @method('PUT')
            
            <div class="px-4 py-4 space-y-4">
                <!-- Basic Information -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        Informasi Dasar
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <!-- Stage -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Stage <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-layer-group text-gray-400 text-xs"></i>
                                </div>
                                <select id="editStageId" name="stage_id" 
                                    class="w-full pl-9 pr-8 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none bg-white"
                                    required>
                                    <option value="">-- Pilih Stage --</option>
                                    @foreach($stages as $stage)
                                        <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Nama Customer -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Nama Customer <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400 text-xs"></i>
                                </div>
                                <input type="text" id="editCustomerName" name="customer_name"
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    placeholder="Masukkan nama customer" required>
                            </div>
                        </div>

                        <!-- Telepon -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                No. Telepon
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-phone text-gray-400 text-xs"></i>
                                </div>
                                <input type="text" id="editPhone" name="phone"
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    placeholder="08xxxxxxxxxx">
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Email
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400 text-xs"></i>
                                </div>
                                <input type="email" id="editEmail" name="email"
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    placeholder="email@example.com">
                            </div>
                        </div>

                        <!-- Nilai Deal -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Nilai Deal (Rp)
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-money-bill-wave text-gray-400 text-xs"></i>
                                </div>
                                <input type="number" id="editValue" name="value" step="0.01"
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    placeholder="0">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description Section with Collapsible -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border border-blue-200 overflow-hidden">
                    <!-- Header - Always Visible -->
                    <div class="p-3 cursor-pointer hover:bg-blue-100 transition-colors" onclick="togglePipelineEditDescSection()">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-file-alt text-indigo-600 mr-2"></i>
                                Detail & Catatan
                            </h4>
                            <div class="flex items-center gap-2">
                                <span id="pipeline-edit-desc-status" class="text-xs text-gray-500">Opsional</span>
                                <i id="pipeline-edit-desc-toggle-icon" class="fas fa-chevron-down text-gray-600 transition-transform duration-300"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Collapsible Content -->
                    <div id="pipeline-edit-desc-content" class="hidden">
                        <div class="px-3 pb-3 space-y-3">
                            <!-- Deskripsi -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                    Deskripsi
                                </label>
                                <div class="relative">
                                    <div class="absolute top-2 left-3 pointer-events-none">
                                        <i class="fas fa-align-left text-gray-400 text-xs"></i>
                                    </div>
                                    <textarea id="editDescription" name="description" rows="3"
                                        class="w-full pl-9 pr-3 py-2 text-sm bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none"
                                        placeholder="Deskripsi tentang deal ini..."></textarea>
                                </div>
                            </div>

                            <!-- Catatan -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                    Catatan
                                </label>
                                <div class="relative">
                                    <div class="absolute top-2 left-3 pointer-events-none">
                                        <i class="fas fa-sticky-note text-gray-400 text-xs"></i>
                                    </div>
                                    <textarea id="editNotes" name="notes" rows="2"
                                        class="w-full pl-9 pr-3 py-2 text-sm bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none"
                                        placeholder="Catatan tambahan..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <button type="button" onclick="closePipelineEditModal()" 
                    class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-100 transition-colors flex items-center gap-2">
                    <i class="fas fa-times"></i>
                    Batal
                </button>
                <button type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg text-xs font-medium hover:bg-blue-700 transition-colors flex items-center gap-2 shadow-lg shadow-blue-500/30">
                    <i class="fas fa-save"></i>
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95) translateY(-20px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-fadeIn { animation: fadeIn 0.3s ease-out; }

select::-ms-expand { display: none; }
input:focus, select:focus, textarea:focus { outline: none; }
select:disabled {
    background-color: #f3f4f6;
    cursor: not-allowed;
    opacity: 0.6;
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

/* Loading state */
.btn-loading {
    position: relative;
    pointer-events: none;
}
.btn-loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 16px;
    height: 16px;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}
@keyframes spin {
    to { transform: translate(-50%, -50%) rotate(360deg); }
}

/* Form validation styling */
.error {
    border-color: #ef4444 !important;
}
.error:focus {
    border-color: #ef4444 !important;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
}
.success {
    border-color: #10b981;
}
.error-message {
    color: #ef4444;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}
</style>

<script>
// ========== DESCRIPTION SECTION TOGGLE ==========
function togglePipelineEditDescSection() {
    const content = document.getElementById('pipeline-edit-desc-content');
    const icon = document.getElementById('pipeline-edit-desc-toggle-icon');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

// ========== MODAL FUNCTIONS ==========
function openPipelineEditModal(pipelineId) {
    fetch(`/pipeline/${pipelineId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editStageId').value = data.stage_id;
            document.getElementById('editCustomerName').value = data.customer_name;
            document.getElementById('editPhone').value = data.phone || '';
            document.getElementById('editEmail').value = data.email || '';
            document.getElementById('editValue').value = data.value || '';
            document.getElementById('editDescription').value = data.description || '';
            document.getElementById('editNotes').value = data.notes || '';
            
            document.getElementById('pipelineEditForm').action = `/pipeline/${pipelineId}`;
            document.getElementById('editPipelineModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(error => console.error('Error:', error));
}

function closePipelineEditModal() {
    document.getElementById('editPipelineModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('pipelineEditForm').reset();
    clearEditValidation();
    
    // Reset description collapse state
    const content = document.getElementById('pipeline-edit-desc-content');
    const icon = document.getElementById('pipeline-edit-desc-toggle-icon');
    
    content.classList.add('hidden');
    icon.style.transform = 'rotate(0deg)';
}

// ========== FORM VALIDATION ==========
function validateEditForm() {
    const form = document.getElementById('pipelineEditForm');
    const inputs = form.querySelectorAll('input[required], select[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            showEditFieldError(input, 'Field ini wajib diisi');
            isValid = false;
        } else {
            clearEditFieldError(input);
        }
    });
    
    // Email validation
    const email = document.querySelector('#editPipelineModal input[name="email"]');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email && email.value && !emailRegex.test(email.value)) {
        showEditFieldError(email, 'Format email tidak valid');
        isValid = false;
    }
    
    return isValid;
}

function showEditFieldError(field, message) {
    field.classList.add('error');
    field.classList.remove('success');
    
    const existingError = field.parentNode.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    field.parentNode.appendChild(errorDiv);
}

function clearEditFieldError(field) {
    field.classList.remove('error');
    field.classList.add('success');
    
    const errorMessage = field.parentNode.querySelector('.error-message');
    if (errorMessage) {
        errorMessage.remove();
    }
}

function clearEditValidation() {
    const fields = document.querySelectorAll('#editPipelineModal input, #editPipelineModal textarea, #editPipelineModal select');
    fields.forEach(field => {
        field.classList.remove('error', 'success');
    });
    
    const errorMessages = document.querySelectorAll('#editPipelineModal .error-message');
    errorMessages.forEach(msg => msg.remove());
}

// ========== FORM SUBMISSION ==========
document.getElementById('pipelineEditForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!validateEditForm()) {
        return;
    }
    
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.classList.add('btn-loading');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Memperbarui...';
    submitBtn.disabled = true;
    
    this.submit();
});

// ========== EVENT LISTENERS ==========
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('editPipelineModal').classList.contains('hidden')) {
        closePipelineEditModal();
    }
});

document.getElementById('editPipelineModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePipelineEditModal();
    }
});

document.addEventListener('input', function(e) {
    if (e.target.matches('#editPipelineModal input, #editPipelineModal textarea, #editPipelineModal select')) {
        clearEditFieldError(e.target);
    }
});
</script>