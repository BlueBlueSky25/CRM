<!-- Modal Add Company with Collapsible PIC Section -->
<div id="addCompanyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden animate-modal-in">
        <!-- Header -->
        <div style="background: linear-gradient(to right, #4f46e5, #7c3aed); padding: 1rem 1.25rem;">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold text-white">Tambah Perusahaan Baru</h3>
                    <p class="text-xs text-indigo-100 mt-0.5">Lengkapi formulir berikut untuk menambahkan perusahaan</p>
                </div>
                <button onclick="closeAddCompanyModal()" 
                    class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="overflow-y-auto max-h-[calc(90vh-120px)]" style="background-color: #f3f4f6; padding: 1rem;">
            <form id="addCompanyForm" action="/company" method="POST" style="display: flex; flex-direction: column; gap: 0.75rem;">
                @csrf
                
                <!-- Company Basic Info -->
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem;">
                    <!-- Nama -->
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                            Nama Perusahaan <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" 
                            name="company_name" 
                            style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem;" 
                            placeholder="Masukkan nama perusahaan"
                            required>
                    </div>
                    
                    <!-- Jenis -->
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                            Jenis Perusahaan <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="company_type_id" 
                                style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem;" 
                                required>
                            <option value="">-- Pilih Jenis --</option>
                            @foreach($types as $type)
                            <option value="{{ $type->company_type_id }}">{{ $type->type_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Tier -->
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                            Tier
                        </label>
                        <select name="tier" 
                                style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem;">
                            <option value="">-- Pilih Tier --</option>
                            <option value="A">Tier A</option>
                            <option value="B">Tier B</option>
                            <option value="C">Tier C</option>
                            <option value="D">Tier D</option>
                        </select>
                    </div>
                    
                    <!-- Status -->
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                            Status
                        </label>
                        <select name="status" 
                                style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem;">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <!-- PIC Section - COLLAPSIBLE -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border border-blue-200 overflow-hidden" style="margin-top: 0.5rem;">
                    <!-- Header - Always Visible -->
                    <div class="p-3 cursor-pointer bg-white hover:bg-blue-100 transition-colors" onclick="togglePICSection()" style="padding: 0.75rem;">
                        <div class="flex items-center justify-between">
                            <h4 style="font-size: 0.875rem; font-weight: 600; color: #1f2937; display: flex; align-items: center;">
                                <i class="fas fa-users" style="color: #4f46e5; margin-right: 0.5rem;"></i>
                                Informasi PIC (Person In Charge)
                            </h4>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <span id="pic-status" style="font-size: 0.75rem; color: #6b7280;">Belum diisi</span>
                                <i id="pic-toggle-icon" class="fas fa-chevron-down" style="color: #6b7280; transition: transform 0.3s;"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Collapsible Content -->
                    <div id="pic-content" class="hidden" style="padding: 0.75rem 0.75rem 0.75rem;">
                        <!-- Container untuk multiple PICs -->
                        <div id="pic-fields-container" style="display: flex; flex-direction: column; gap: 0.75rem;">
                            <!-- PIC fields will be added here dynamically -->
                        </div>
                        
                        <!-- Button Tambah PIC -->
                        <button type="button" 
                                onclick="addPICField()" 
                                style="margin-top: 0.5rem; background-color: blue; color: white; border: none; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-weight: 500; font-size: 0.75rem; cursor: pointer; display: flex; align-items: center; gap: 0.375rem;">
                            <i class="fas fa-plus" style="font-size: 0.625rem;"></i>
                            Tambah PIC
                        </button>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                        Deskripsi
                    </label>
                    <textarea name="description" 
                              rows="2" 
                              style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem; resize: none;" 
                              placeholder="Tambahkan keterangan tentang perusahaan..."></textarea>
                </div>
                
                <!-- Tombol -->
                <div style="display: flex; justify-content: flex-end; gap: 0.5rem; padding-top: 0.75rem; border-top: 1px solid #e5e7eb; margin-top: 0.5rem;">
                    <button type="button" 
                            onclick="closeAddCompanyModal()" 
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
</style>

<script>
let picCounter = 0;

function openAddCompanyModal() {
    document.getElementById('addCompanyModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAddCompanyModal() {
    document.getElementById('addCompanyModal').classList.add('hidden');
    document.getElementById('addCompanyForm').reset();
    document.body.style.overflow = 'auto';
    
    // Reset PIC section
    document.getElementById('pic-fields-container').innerHTML = '';
    picCounter = 0;
    
    const content = document.getElementById('pic-content');
    const icon = document.getElementById('pic-toggle-icon');
    const statusText = document.getElementById('pic-status');
    
    content.classList.add('hidden');
    icon.style.transform = 'rotate(0deg)';
    statusText.textContent = 'Belum diisi';
    statusText.style.color = '#6b7280';
}

function togglePICSection() {
    const content = document.getElementById('pic-content');
    const icon = document.getElementById('pic-toggle-icon');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

function addPICField() {
    picCounter++;
    const container = document.getElementById('pic-fields-container');
    
    const picFieldGroup = document.createElement('div');
    picFieldGroup.id = `pic-group-${picCounter}`;
    picFieldGroup.style.cssText = 'background-color: white; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 0.75rem;';
    
    picFieldGroup.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
            <span style="font-size: 0.75rem; font-weight: 600; color: #4f46e5;">PIC #${picCounter}</span>
            <button type="button" 
                    onclick="removePICField(${picCounter})" 
                    style="background-color: #ef4444; color: white; border: none; border-radius: 0.375rem; padding: 0.25rem 0.5rem; font-size: 0.625rem; cursor: pointer;">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem;">
            <!-- Nama PIC -->
            <div style="grid-column: span 2;">
                <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                    Nama PIC <span style="color: #ef4444;">*</span>
                </label>
                <input type="text" 
                       name="pics[${picCounter - 1}][pic_name]" 
                       placeholder="Contoh: John Doe"
                       style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem; font-size: 0.75rem;"
                       oninput="checkPICCompletion()"
                       required>
            </div>
            
            <!-- Position -->
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                    Position
                </label>
                <input type="text" 
                       name="pics[${picCounter - 1}][position]" 
                       placeholder="Contoh: Manager"
                       style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem; font-size: 0.75rem;"
                       oninput="checkPICCompletion()">
            </div>
            
            <!-- Phone -->
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                    Phone
                </label>
                <input type="text" 
                       name="pics[${picCounter - 1}][phone]" 
                       placeholder="Contoh: 08123456789"
                       style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem; font-size: 0.75rem;"
                       oninput="checkPICCompletion()">
            </div>
            
            <!-- Email -->
            <div style="grid-column: span 2;">
                <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                    Email
                </label>
                <input type="email" 
                       name="pics[${picCounter - 1}][email]" 
                       placeholder="Contoh: john@company.com"
                       style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem; font-size: 0.75rem;"
                       oninput="checkPICCompletion()">
            </div>
        </div>
    `;
    
    container.appendChild(picFieldGroup);
    checkPICCompletion();
    
    // Auto-expand section when adding PIC
    const content = document.getElementById('pic-content');
    const icon = document.getElementById('pic-toggle-icon');
    content.classList.remove('hidden');
    icon.style.transform = 'rotate(180deg)';
}

function removePICField(picIndex) {
    const field = document.getElementById(`pic-group-${picIndex}`);
    if (field) {
        field.remove();
        checkPICCompletion();
    }
}

function checkPICCompletion() {
    const container = document.getElementById('pic-fields-container');
    const picGroups = container.querySelectorAll('[id^="pic-group-"]');
    const statusText = document.getElementById('pic-status');
    
    if (picGroups.length === 0) {
        statusText.textContent = 'Belum diisi';
        statusText.style.color = '#6b7280';
        statusText.style.fontWeight = 'normal';
    } else {
        let filledCount = 0;
        picGroups.forEach(group => {
            const nameInput = group.querySelector('input[name*="[pic_name]"]');
            if (nameInput && nameInput.value.trim() !== '') {
                filledCount++;
            }
        });
        
        if (filledCount > 0) {
            statusText.textContent = `${filledCount} PIC ditambahkan`;
            statusText.style.color = '#059669';
            statusText.style.fontWeight = '500';
        } else {
            statusText.textContent = `${picGroups.length} PIC (belum diisi)`;
            statusText.style.color = '#f59e0b';
            statusText.style.fontWeight = '500';
        }
    }
}
</script>