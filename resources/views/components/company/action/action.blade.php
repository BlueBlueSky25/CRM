<!-- Modal Add Company with Two Column Layout - INDEPENDENT SCROLLING -->
<div id="addCompanyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full h-[95vh] max-w-6xl flex flex-col animate-modal-in">
        <!-- Header -->
        <div style="background: linear-gradient(to right, #4f46e5, #7c3aed); padding: 1rem 1.5rem; flex-shrink: 0;">
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

        <!-- Body - Two Column Layout with Independent Scrolling -->
        <div class="flex-1 overflow-hidden flex" style="background-color: #f3f4f6;">
            <form id="addCompanyForm" action="/company" method="POST" class="w-full flex overflow-hidden">
                @csrf
                
                <!-- LEFT COLUMN - Company Basic Info & PICs & Address -->
                <div class="w-1/2 overflow-y-auto border-r border-gray-300" style="padding: 1.5rem;">
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        
                        <!-- SECTION 1: Company Info Title -->
                        <div style="padding-bottom: 0.75rem; border-bottom: 2px solid #e5e7eb;">
                            <h4 style="font-size: 0.9375rem; font-weight: 600; color: #1f2937; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-building" style="color: #4f46e5;"></i>
                                Informasi Perusahaan
                            </h4>
                        </div>

                        <!-- Company Basic Info Fields -->
                        <div style="display: grid; grid-template-columns: 1fr; gap: 0.75rem;">
                            <!-- Nama Perusahaan -->
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
                            
                            <!-- Jenis Perusahaan -->
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

                            <!-- Deskripsi -->
                            <div>
                                <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                                    Deskripsi
                                </label>
                                <textarea name="description" 
                                          rows="3" 
                                          style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem; resize: none;" 
                                          placeholder="Tambahkan keterangan tentang perusahaan..."></textarea>
                            </div>
                        </div>

                        <!-- SECTION 2: ADDRESS INFORMATION -->
                        <div style="padding-top: 0.75rem; border-top: 2px solid #e5e7eb; border-bottom: 2px solid #e5e7eb; padding-bottom: 0.75rem;">
                            <h4 style="font-size: 0.9375rem; font-weight: 600; color: #1f2937; margin: 0 0 0.75rem 0; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-map-marker-alt" style="color: #ef4444;"></i>
                                Informasi Lokasi
                            </h4>

                            <!-- Province -->
                            <div style="margin-bottom: 0.75rem;">
                                <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                                    Provinsi
                                </label>
                                <select id="add_province" 
                                        name="province_id"
                                        style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem;">
                                    <option value="">-- Pilih Provinsi --</option>
                                </select>
                            </div>

                            <!-- Regency -->
                            <div style="margin-bottom: 0.75rem;">
                                <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                                    Kabupaten/Kota
                                </label>
                                <select id="add_regency" 
                                        name="regency_id"
                                        style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem;" disabled>
                                    <option value="">-- Pilih Kabupaten/Kota --</option>
                                </select>
                            </div>

                            <!-- District -->
                            <div style="margin-bottom: 0.75rem;">
                                <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                                    Kecamatan
                                </label>
                                <select id="add_district" 
                                        name="district_id"
                                        style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem;" disabled>
                                    <option value="">-- Pilih Kecamatan --</option>
                                </select>
                            </div>

                            <!-- Village -->
                            <div style="margin-bottom: 0.75rem;">
                                <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                                    Kelurahan/Desa
                                </label>
                                <select id="add_village" 
                                        name="village_id"
                                        style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem;" disabled>
                                    <option value="">-- Pilih Kelurahan/Desa --</option>
                                </select>
                            </div>

                            <!-- Full Address -->
                            <div>
                                <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                                    Alamat Lengkap
                                </label>
                                <textarea id="add_full_address"
                                          name="full_address"
                                          rows="2" 
                                          style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem; resize: none;" 
                                          placeholder="Masukkan alamat lengkap perusahaan..."></textarea>
                            </div>
                        </div>

                        <!-- SECTION 3: PIC INFORMATION -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border border-blue-200 overflow-hidden">
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
                            <div id="pic-content" class="hidden" style="padding: 0.75rem;">
                                <!-- Container untuk multiple PICs -->
                                <div id="pic-fields-container" style="display: flex; flex-direction: column; gap: 0.75rem;">
                                    <!-- PIC fields akan ditambahkan di sini -->
                                </div>
                                
                                <!-- Button Tambah PIC -->
                                <button type="button" 
                                        onclick="addPICField()" 
                                        style="margin-top: 0.5rem; background-color: #3b82f6; color: white; border: none; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-weight: 500; font-size: 0.75rem; cursor: pointer; display: flex; align-items: center; gap: 0.375rem;">
                                    <i class="fas fa-plus" style="font-size: 0.625rem;"></i>
                                    Tambah PIC
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN - Company Contact & Media Info -->
                <div class="w-1/2 overflow-y-auto" style="padding: 1.5rem; background-color: #ffffff;">
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        
                        <!-- SECTION 1: CONTACT INFO Title -->
                        <div style="padding-bottom: 0.75rem; border-bottom: 2px solid #e5e7eb;">
                            <h4 style="font-size: 0.9375rem; font-weight: 600; color: #1f2937; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-phone" style="color: #059669;"></i>
                                Informasi Kontak & Media
                            </h4>
                        </div>

                        <!-- Contact Information -->
                        <div style="display: grid; grid-template-columns: 1fr; gap: 0.75rem;">
                            <!-- Phone Company -->
                            <div>
                                <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                                    <i class="fas fa-phone" style="margin-right: 0.375rem; color: #059669;"></i>
                                    Nomor Telepon Perusahaan
                                </label>
                                <input type="tel" 
                                    name="company_phone" 
                                    style="width: 100%; background-color: #f9fafb; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem;" 
                                    placeholder="Contoh: 021-12345678 atau +62212345678">
                            </div>

                            <!-- Email Company -->
                            <div>
                                <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                                    <i class="fas fa-envelope" style="margin-right: 0.375rem; color: #2563eb;"></i>
                                    Email Perusahaan
                                </label>
                                <input type="email" 
                                    name="company_email" 
                                    style="width: 100%; background-color: #f9fafb; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem;" 
                                    placeholder="Contoh: info@company.com">
                            </div>

                            <!-- Website -->
                            <div>
                                <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                                    <i class="fas fa-globe" style="margin-right: 0.375rem; color: #7c3aed;"></i>
                                    Website
                                </label>
                                <input type="url" 
                                    name="company_website" 
                                    style="width: 100%; background-color: #f9fafb; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem;" 
                                    placeholder="Contoh: https://www.company.com">
                            </div>
                        </div>

                        <!-- SECTION 2: SOCIAL MEDIA -->
                        <div style="padding-top: 0.75rem; border-top: 2px solid #e5e7eb; border-bottom: 2px solid #e5e7eb; padding-bottom: 0.75rem;">
                            <h5 style="font-size: 0.8125rem; font-weight: 600; color: #1f2937; margin: 0 0 0.75rem 0; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-share-alt" style="color: #2563eb;"></i>
                                Media Sosial
                            </h5>

                            <!-- LinkedIn -->
                            <div style="margin-bottom: 0.75rem;">
                                <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                                    <i class="fab fa-linkedin" style="margin-right: 0.375rem; color: #0a66c2;"></i>
                                    LinkedIn
                                </label>
                                <input type="url" 
                                    name="company_linkedin" 
                                    style="width: 100%; background-color: #f9fafb; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem;" 
                                    placeholder="https://linkedin.com/company/...">
                            </div>

                            <!-- Instagram -->
                            <div>
                                <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem;">
                                    <i class="fab fa-instagram" style="margin-right: 0.375rem; color: #e1306c;"></i>
                                    Instagram
                                </label>
                                <input type="text" 
                                    name="company_instagram" 
                                    style="width: 100%; background-color: #f9fafb; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem;" 
                                    placeholder="@company_name">
                            </div>
                        </div>

                        <!-- SECTION 3: LOGO UPLOAD -->
                        <div>
                            <h5 style="font-size: 0.8125rem; font-weight: 600; color: #1f2937; margin: 0 0 0.75rem 0; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-image" style="color: #f59e0b;"></i>
                                Logo Perusahaan
                            </h5>

                            <!-- File Upload Area -->
                            <div style="border: 2px dashed #d1d5db; border-radius: 0.5rem; padding: 1.5rem; text-align: center; cursor: pointer; transition: all 0.3s; background-color: #fafafa;" 
                                 id="logoDropZone"
                                 onmouseover="this.style.borderColor='#3b82f6'; this.style.backgroundColor='#eff6ff';"
                                 onmouseout="this.style.borderColor='#d1d5db'; this.style.backgroundColor='#fafafa';">
                                <input type="file" 
                                    id="company_logo" 
                                    name="company_logo" 
                                    accept="image/*" 
                                    style="display: none;"
                                    onchange="previewLogo(event)">
                                
                                <div id="logoPreviewContainer" style="display: none;">
                                    <img id="logoPreview" src="" style="max-width: 100%; max-height: 150px; margin-bottom: 0.75rem; border-radius: 0.375rem;">
                                    <button type="button" onclick="clearLogoPreview()" style="background-color: #ef4444; color: white; border: none; border-radius: 0.375rem; padding: 0.375rem 0.75rem; font-size: 0.6875rem; cursor: pointer;">
                                        <i class="fas fa-trash" style="margin-right: 0.25rem;"></i>Hapus
                                    </button>
                                </div>

                                <div id="logoUploadPrompt">
                                    <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: #9ca3af; margin-bottom: 0.5rem; display: block;"></i>
                                    <p style="font-size: 0.8125rem; color: #6b7280; margin: 0;">Klik atau drag file gambar di sini</p>
                                    <p style="font-size: 0.7rem; color: #9ca3af; margin: 0.25rem 0 0 0;">PNG, JPG, atau GIF (Max 5MB)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer - Fixed at bottom -->
        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; padding: 0.75rem 1.5rem; border-top: 1px solid #e5e7eb; background-color: #f9fafb; flex-shrink: 0;">
            <button type="button" 
                    onclick="closeAddCompanyModal()" 
                    style="background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 1.25rem; font-weight: 500; font-size: 0.75rem; cursor: pointer; transition: all 0.2s;">
                <i class="fas fa-times" style="margin-right: 0.375rem;"></i>Batal
            </button>
            <button type="submit" 
                    form="addCompanyForm"
                    style="background-color: #4f46e5; color: white; border: none; border-radius: 0.5rem; padding: 0.5rem 1.25rem; font-weight: 500; font-size: 0.75rem; cursor: pointer; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); transition: all 0.2s;">
                <i class="fas fa-save" style="margin-right: 0.375rem;"></i>Simpan Data
            </button>
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

/* Custom scrollbar untuk kedua kolom */
div.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

div.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
}

div.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

div.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Focus styles for inputs */
input:focus, select:focus, textarea:focus {
    outline: none;
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
}
</style>

<script>
let picCounter = 0;

function openAddCompanyModal() {
    document.getElementById('addCompanyModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Initialize address cascade setelah modal terbuka
    setTimeout(() => {
        initAddAddressCascade();
    }, 100);
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

    // Clear logo preview
    clearLogoPreview();

    // Destroy cascade instance
    if (window.addCompanyCascade) {
        window.addCompanyCascade.destroy();
        window.addCompanyCascade = null;
    }
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
    
    // Auto-expand section
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

function previewLogo(event) {
    const file = event.target.files[0];
    if (file) {
        // Validate file size (5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimal 5MB');
            document.getElementById('company_logo').value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('logoPreview').src = e.target.result;
            document.getElementById('logoPreviewContainer').style.display = 'block';
            document.getElementById('logoUploadPrompt').style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
}

function clearLogoPreview() {
    document.getElementById('company_logo').value = '';
    document.getElementById('logoPreviewContainer').style.display = 'none';
    document.getElementById('logoUploadPrompt').style.display = 'block';
}

function initAddAddressCascade() {
    // Initialize address cascade untuk form add
    console.log('Initializing add company cascade...');
    
    if (window.addCompanyCascade) {
        window.addCompanyCascade.destroy();
    }

    window.addCompanyCascade = new AddressCascade({
        provinceId: 'add_province',
        regencyId: 'add_regency',
        districtId: 'add_district',
        villageId: 'add_village',
        baseUrl: '/api/'
    });
}

// Drag and drop untuk logo
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('logoDropZone');
    if (dropZone) {
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = '#3b82f6';
            dropZone.style.backgroundColor = '#eff6ff';
        });

        dropZone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = '#d1d5db';
            dropZone.style.backgroundColor = '#fafafa';
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                document.getElementById('company_logo').files = files;
                previewLogo({ target: { files } });
            }
            dropZone.style.borderColor = '#d1d5db';
            dropZone.style.backgroundColor = '#fafafa';
        });

        dropZone.addEventListener('click', () => {
            document.getElementById('company_logo').click();
        });
    }
});
</script>