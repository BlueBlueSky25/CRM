<!-- Modal Add Sales -->
<div id="addSalesModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden animate-modal-in">
        <!-- Header -->
        <div style="background: linear-gradient(to right, #4f46e5, #7c3aed); padding: 1rem 1.25rem;">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold text-white">Tambah Marketing Baru</h3>
                    <p class="text-xs text-indigo-100 mt-0.5">Lengkapi formulir berikut untuk menambahkan marketing</p>
                </div>
                <button onclick="closeAddSalesModal()" 
                    class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="overflow-y-auto max-h-[calc(90vh-120px)]" style="background-color: #f3f4f6; padding: 1rem;">
            <form id="addSalesForm" action="{{ route('marketing.sales.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 0.75rem;">
                @csrf
                
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem;">
                    <!-- Nama -->
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                            Nama Lengkap <span style="color: #ef4444;">*</span>
                        </label>
                        <div style="position: relative;">
                            <i class="fas fa-user" style="position: absolute; left: 0.625rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.75rem;"></i>
                            <input type="text" 
                                name="username" 
                                style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem 0.5rem 2rem; font-size: 0.875rem;" 
                                placeholder="Masukkan nama lengkap"
                                required>
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                            Email <span style="color: #ef4444;">*</span>
                        </label>
                        <div style="position: relative;">
                            <i class="fas fa-envelope" style="position: absolute; left: 0.625rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.75rem;"></i>
                            <input type="email" 
                                name="email" 
                                style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem 0.5rem 2rem; font-size: 0.875rem;" 
                                placeholder="email@example.com"
                                required>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                            No. Telepon
                        </label>
                        <div style="position: relative;">
                            <i class="fas fa-phone" style="position: absolute; left: 0.625rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.75rem;"></i>
                            <input type="text" 
                                name="phone" 
                                style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem 0.5rem 2rem; font-size: 0.875rem;"
                                placeholder="08xxxxxxxxxx">
                        </div>
                    </div>

                    <!-- Birth Date -->
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                            Tanggal Lahir
                        </label>
                        <div style="position: relative;">
                            <i class="fas fa-calendar" style="position: absolute; left: 0.625rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.75rem;"></i>
                            <input type="date" 
                                name="birth_date" 
                                style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem 0.5rem 2rem; font-size: 0.875rem;"
                                max="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <!-- Password -->
                    <div style="grid-column: span 2;">
                        <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                            Password <span style="color: #ef4444;">*</span>
                        </label>
                        <div style="position: relative;">
                            <i class="fas fa-lock" style="position: absolute; left: 0.625rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.75rem;"></i>
                            <input type="password" 
                                name="password" 
                                style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem 0.5rem 2rem; font-size: 0.875rem;" 
                                placeholder="Minimal 6 karakter"
                                required>
                        </div>
                    </div>
                </div>

                <!-- Address Section -->
                <div style="background: linear-gradient(to bottom right, #eff6ff, #e0e7ff); border: 1px solid #c7d2fe; border-radius: 0.5rem; padding: 0.75rem;">
                    <h4 style="font-size: 0.875rem; font-weight: 600; color: #1f2937; margin-bottom: 0.75rem; display: flex; align-items: center;">
                        <i class="fas fa-map-marker-alt" style="color: #6366f1; margin-right: 0.5rem; font-size: 0.875rem;"></i>
                        Informasi Alamat
                    </h4>
                    
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem;">
                        <!-- Provinsi -->
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                                Provinsi <span style="color: #ef4444;">*</span>
                            </label>
                            <select id="create-province" name="province_id" 
                                    class="cascade-province" 
                                    style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem;" 
                                    required>
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Kabupaten/Kota -->
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                                Kabupaten/Kota <span style="color: #ef4444;">*</span>
                            </label>
                            <select id="create-regency" name="regency_id" 
                                    class="cascade-regency" 
                                    style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem;" 
                                    required disabled>
                                <option value="">-- Pilih Kabupaten/Kota --</option>
                            </select>
                        </div>

                        <!-- Kecamatan -->
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                                Kecamatan <span style="color: #ef4444;">*</span>
                            </label>
                            <select id="create-district" name="district_id" 
                                    class="cascade-district" 
                                    style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem;" 
                                    required disabled>
                                <option value="">-- Pilih Kecamatan --</option>
                            </select>
                        </div>

                        <!-- Kelurahan/Desa -->
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                                Kelurahan/Desa <span style="color: #ef4444;">*</span>
                            </label>
                            <select id="create-village" name="village_id" 
                                    class="cascade-village" 
                                    style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem;" 
                                    required disabled>
                                <option value="">-- Pilih Kelurahan/Desa --</option>
                            </select>
                        </div>

                        <!-- Detail Alamat (full width) -->
                        <div style="grid-column: span 2;">
                            <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                                Detail Alamat
                            </label>
                            <div style="position: relative;">
                                <i class="fas fa-home" style="position: absolute; left: 0.625rem; top: 0.625rem; color: #9ca3af; font-size: 0.75rem;"></i>
                                <textarea id="address" name="address" rows="2" 
                                        placeholder="Contoh: Jl. Merdeka No. 123, RT 01/RW 02" 
                                        style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem 0.5rem 2rem; font-size: 0.875rem; resize: none;"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tombol -->
                <div style="display: flex; justify-content: flex-end; gap: 0.5rem; padding-top: 0.75rem; border-top: 1px solid #e5e7eb; margin-top: 0.5rem;">
                    <button type="button" 
                            onclick="closeAddSalesModal()" 
                            style="background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 1.25rem; font-weight: 500; font-size: 0.75rem; cursor: pointer; transition: all 0.2s;">
                        Batal
                    </button>
                    <button type="submit" 
                            style="background-color: #4f46e5; color: white; border: none; border-radius: 0.5rem; padding: 0.5rem 1.25rem; font-weight: 500; font-size: 0.75rem; cursor: pointer; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); transition: all 0.2s;">
                        <i class="fas fa-save" style="margin-right: 0.375rem;"></i>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .bg-gradient-to-r {
    background-image: linear-gradient(to right, #4f46e5, #a21caf) !important;
    }
    .bg-white {
        background-color: #fff !important;
    }

    /* Modal Animation */
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

    /* Form Enhancements */
    .form-input:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Custom Scrollbar for Modal */
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

    /* Responsive adjustments */
    @media (max-width: 640px) {
        #addSalesModal .bg-white {
            margin: 1rem;
            max-width: calc(100% - 2rem);
        }
    }

    /* Loading state for submit button */
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
        to {
            transform: translate(-50%, -50%) rotate(360deg);
        }
    }

    /* Enhanced focus states */
    input:focus, textarea:focus, select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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

    select:disabled {
        background-color: #f3f4f6;
        cursor: not-allowed;
        opacity: 0.6;
    }
</style>

<script>
// Modal Functions untuk Add Sales Modal
function openAddSalesModal() {
    document.getElementById('addSalesModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        const firstInput = document.querySelector('#addSalesModal input[name="username"]');
        if (firstInput) firstInput.focus();
    }, 300);
}

function closeAddSalesModal() {
    document.getElementById('addSalesModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    document.querySelector('#addSalesModal form').reset();
    clearValidation();
}

// Form validation
function validateForm() {
    const form = document.querySelector('#addSalesModal form');
    const inputs = form.querySelectorAll('input[required], select[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            showFieldError(input, 'Field ini wajib diisi');
            isValid = false;
        } else {
            clearFieldError(input);
        }
    });
    
    // Email validation
    const email = document.querySelector('#addSalesModal input[name="email"]');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email && email.value && !emailRegex.test(email.value)) {
        showFieldError(email, 'Format email tidak valid');
        isValid = false;
    }
    
    // Password validation
    const password = document.querySelector('#addSalesModal input[name="password"]');
    if (password && password.value && password.value.length < 6) {
        showFieldError(password, 'Password minimal 6 karakter');
        isValid = false;
    }
    
    return isValid;
}

function showFieldError(field, message) {
    field.classList.add('error');
    field.classList.remove('success');
    
    const existingError = field.parentNode.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.style.color = '#ef4444';
    errorDiv.style.fontSize = '0.75rem';
    errorDiv.style.marginTop = '0.25rem';
    errorDiv.textContent = message;
    field.parentNode.appendChild(errorDiv);
}

function clearFieldError(field) {
    field.classList.remove('error');
    field.classList.add('success');
    
    const errorMessage = field.parentNode.querySelector('.error-message');
    if (errorMessage) {
        errorMessage.remove();
    }
}

function clearValidation() {
    const fields = document.querySelectorAll('#addSalesModal input, #addSalesModal textarea, #addSalesModal select');
    fields.forEach(field => {
        field.classList.remove('error', 'success');
    });
    
    const errorMessages = document.querySelectorAll('#addSalesModal .error-message');
    errorMessages.forEach(msg => msg.remove());
}

// Form submission with loading state
document.querySelector('#addSalesModal form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!validateForm()) {
        return;
    }
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.classList.add('btn-loading');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 0.375rem;"></i>Menyimpan...';
    submitBtn.disabled = true;
    
    this.submit();
});

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('addSalesModal').classList.contains('hidden')) {
        closeAddSalesModal();
    }
});

// Close modal on backdrop click
document.getElementById('addSalesModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddSalesModal();
    }
});

// Real-time validation
document.addEventListener('input', function(e) {
    if (e.target.matches('#addSalesModal input, #addSalesModal textarea, #addSalesModal select')) {
        clearFieldError(e.target);
    }
});
</script>