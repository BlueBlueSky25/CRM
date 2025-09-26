<!-- Improved User Modal -->
<div id="userModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden animate-modal-in">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-semibold text-white">Add New User</h3>
                    <p class="text-indigo-100 text-sm mt-1">Create a new user account with role assignment</p>
                </div>
                <button onclick="closeUserModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body - Scrollable -->
        <div class="overflow-y-auto max-h-[calc(90vh-140px)]">
            <form action="{{ route('users.store') }}" method="POST" class="p-6">
                @csrf
                
                <!-- Personal Information Section -->
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-user-circle text-indigo-500 mr-2"></i>
                        Personal Information
                    </h4>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- Username -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Username <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="username" 
                                    class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
                                    placeholder="Enter username" required>
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <div class="relative">
                                <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="email" name="email" 
                                    class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
                                    placeholder="user@example.com">
                            </div>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <div class="relative">
                                <i class="fas fa-phone absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="phone" 
                                    class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
                                    placeholder="+62 812-3456-7890">
                            </div>
                        </div>

                        <!-- Birth Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Birth Date</label>
                            <div class="relative">
                                <i class="fas fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="date" name="birth_date" 
                                    class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
                                    max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Section -->
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-indigo-500 mr-2"></i>
                        Address Information
                    </h4>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi <span class="text-red-500">*</span></label>
                        <select id="create-province" name="province_id" class="cascade-province w-full border border-gray-300 rounded-lg py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province->id }}">{{ $province->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kabupaten/Kota -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten/Kota <span class="text-red-500">*</span></label>
                        <select id="create-regency" name="regency_id" class="cascade-regency w-full border border-gray-300 rounded-lg py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                            <option value="">-- Pilih Kabupaten/Kota --</option>
                        </select>
                    </div>

                    <!-- Kecamatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan <span class="text-red-500">*</span></label>
                        <select id="create-district" name="district_id" class="cascade-district w-full border border-gray-300 rounded-lg py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                            <option value="">-- Pilih Kecamatan --</option>
                        </select>
                    </div>

                    <!-- Kelurahan/Desa -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kelurahan/Desa <span class="text-red-500">*</span></label>
                        <select id="create-village" name="village_id" class="cascade-village w-full border border-gray-300 rounded-lg py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                            <option value="">-- Pilih Kelurahan/Desa --</option>
                        </select>
                    </div>
                    </div>

                    <!-- Detail Alamat (full width) -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Detail Alamat</label>
                        <div class="relative">
                            <i class="fas fa-home absolute left-3 top-3 text-gray-400"></i>
                            <textarea id="address" name="address" rows="3" placeholder="Contoh: Jl. Merdeka No. 123, RT 01/RW 02" 
                                    class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none"></textarea>
                        </div>
                        <small class="text-gray-500">Isi dengan detail alamat seperti nama jalan, nomor rumah, RT/RW</small>
                    </div>
                </div>

                <!-- Account Settings Section -->
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-cog text-indigo-500 mr-2"></i>
                        Account Settings
                    </h4>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fas fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="password" name="password" id="userPassword"
                                    class="w-full border border-gray-300 rounded-lg pl-10 pr-10 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
                                    placeholder="Minimum 6 characters" required>
                                <button type="button" onclick="toggleUserPassword()" 
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye" id="userPasswordToggle"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Password must be at least 6 characters long</p>
                        </div>

                        <!-- Role -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Role <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fas fa-user-tag absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 z-10"></i>
                                <select name="role_id" 
                                    class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none bg-white" 
                                    required>
                                    <option value="">Select role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->role_id }}">{{ $role->role_name }}</option>
                                    @endforeach
                                </select>
                                <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Role Permission Preview (Optional Enhancement) -->
                <div id="rolePreview" class="mb-6 p-4 bg-gray-50 rounded-lg hidden">
                    <h5 class="font-medium text-gray-700 mb-2">Role Permissions Preview</h5>
                    <div id="rolePermissions" class="text-sm text-gray-600"></div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeUserModal()" 
                        class="px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 transform hover:scale-105 font-medium shadow-md hover:shadow-lg">
                        <i class="fas fa-user-plus mr-2"></i>
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Modal Animation */
@keyframes modal-in {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.animate-modal-in {
    animation: modal-in 0.3s ease-out;
}

/* Enhanced Form Styling */
input:focus, textarea:focus, select:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

/* Custom Select Styling */
select {
    background-image: none;
}

/* Section Dividers */
.section-divider {
    border-left: 4px solid #6366f1;
    padding-left: 1rem;
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

/* Form validation states */
.error {
    border-color: #ef4444;
}

.error:focus {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.success {
    border-color: #10b981;
}

.success:focus {
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

/* Loading button state */
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

/* Role preview styling */
#rolePreview {
    border-left: 4px solid #6366f1;
    transition: all 0.3s ease;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    #userModal .bg-white {
        margin: 1rem;
        max-width: calc(100% - 2rem);
    }
    
    .grid.md\\:grid-cols-2 {
        grid-template-columns: 1fr;
    }
}

/* Icon sizing */
.fa-user, .fa-envelope, .fa-phone, .fa-calendar-alt, 
.fa-map-marker-alt, .fa-lock, .fa-user-tag {
    font-size: 14px;
}
</style>

<script>
// Debug function untuk cek elemen dengan unique IDs
function checkElements() {
    const elements = {
        province: document.getElementById('create-province'),
        regency: document.getElementById('create-regency'), 
        district: document.getElementById('create-district'),
        village: document.getElementById('create-village')
    };
    
    console.log('Elements check:', elements);
    
    Object.keys(elements).forEach(key => {
        if (!elements[key]) {
            console.error(`Element ${key} not found!`);
        } else {
            console.log(`Element ${key} found:`, elements[key]);
        }
    });
    
    return elements;
}

// Reset dropdown function dengan unique IDs
function resetAddressDropdowns() {
    const regencySelect = document.getElementById('create-regency');
    const districtSelect = document.getElementById('create-district');
    const villageSelect = document.getElementById('create-village');
    
    if (regencySelect) regencySelect.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
    if (districtSelect) districtSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
    if (villageSelect) villageSelect.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';
}

// Modal Functions
function openUserModal() {
    document.getElementById('userModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    console.log('Modal opened - Initializing cascade');
    setTimeout(() => {
        initializeAddressCascade();
        document.querySelector('#userModal input[name="username"]').focus();
    }, 300);
}

function closeUserModal() {
    document.getElementById('userModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    // Reset form
    document.querySelector('#userModal form').reset();
    clearUserValidation();
    resetAddressDropdowns();
    hideRolePreview();
}

// Password visibility toggle
function toggleUserPassword() {
    const field = document.getElementById('userPassword');
    const toggle = document.getElementById('userPasswordToggle');
    
    if (field.type === 'password') {
        field.type = 'text';
        toggle.classList.remove('fa-eye');
        toggle.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        toggle.classList.remove('fa-eye-slash');
        toggle.classList.add('fa-eye');
    }
}

// Form validation
function validateUserForm() {
    const form = document.querySelector('#userModal form');
    const requiredInputs = form.querySelectorAll('input[required], select[required]');
    let isValid = true;
    
    requiredInputs.forEach(input => {
        if (!input.value.trim()) {
            showUserFieldError(input, 'This field is required');
            isValid = false;
        } else {
            clearUserFieldError(input);
        }
    });
    
    // Email validation
    const email = form.querySelector('input[name="email"]');
    if (email.value && !isValidEmail(email.value)) {
        showUserFieldError(email, 'Please enter a valid email address');
        isValid = false;
    }
    
    // Password validation
    const password = form.querySelector('input[name="password"]');
    if (password.value && password.value.length < 6) {
        showUserFieldError(password, 'Password must be at least 6 characters');
        isValid = false;
    }
    
    return isValid;
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function showUserFieldError(field, message) {
    field.classList.add('error');
    field.classList.remove('success');
    
    // Remove existing error message
    const existingError = field.parentNode.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
    
    // Add error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message text-red-500 text-xs mt-1';
    errorDiv.textContent = message;
    field.parentNode.appendChild(errorDiv);
}

function clearUserFieldError(field) {
    field.classList.remove('error');
    field.classList.add('success');
    
    const errorMessage = field.parentNode.querySelector('.error-message');
    if (errorMessage) {
        errorMessage.remove();
    }
}

function clearUserValidation() {
    const fields = document.querySelectorAll('#userModal input, #userModal textarea, #userModal select');
    fields.forEach(field => {
        field.classList.remove('error', 'success');
    });
    
    const errorMessages = document.querySelectorAll('#userModal .error-message');
    errorMessages.forEach(msg => msg.remove());
}

// Role preview functionality (optional enhancement)
function showRolePreview(roleId) {
    const rolePreview = document.getElementById('rolePreview');
    const rolePermissions = document.getElementById('rolePermissions');
    
    if (roleId) {
        // This would typically fetch role permissions from server
        const mockPermissions = {
            1: ['View Dashboard', 'Manage Users', 'System Settings'],
            2: ['View Dashboard', 'Manage Content'],
            3: ['View Dashboard', 'Basic Operations']
        };
        
        if (mockPermissions[roleId]) {
            rolePermissions.innerHTML = mockPermissions[roleId].map(perm => 
                `<span class="inline-block bg-indigo-100 text-indigo-800 px-2 py-1 rounded text-xs mr-2 mb-1">${perm}</span>`
            ).join('');
            rolePreview.classList.remove('hidden');
        }
    } else {
        hideRolePreview();
    }
}

function hideRolePreview() {
    document.getElementById('rolePreview').classList.add('hidden');
}

// Form submission with loading state
function setupFormSubmission() {
    const form = document.querySelector('#userModal form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            // Jalankan loading state SEBELUM validasi
            submitBtn.classList.add('btn-loading');
            submitBtn.innerHTML = 'Creating User...';
            submitBtn.disabled = true;

            if (!validateUserForm()) {
                e.preventDefault(); // Hanya block submit jika validasi gagal
                // Reset tombol
                submitBtn.classList.remove('btn-loading');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                return;
            }
        });
    }
}

// Notification system
function showUserNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-[60] p-4 rounded-lg shadow-lg text-white transform transition-all duration-300 translate-x-full`;
    
    const bgColor = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500'
    };
    
    notification.classList.add(bgColor[type]);
    notification.innerHTML = `
        <div class="flex items-center gap-2">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'info'}-circle"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// CASCADE DROPDOWN - FIXED VERSION dengan unique IDs
function initializeAddressCascade() {
    console.log('Initializing address cascade...');
    
    // Cek apakah elemen ada dengan unique IDs
    const elements = checkElements();
    if (!elements.province || !elements.regency || !elements.district || !elements.village) {
        console.error('One or more dropdown elements not found');
        return;
    }

    console.log('All dropdown elements found');

    // Get fresh references setiap kali
    const getElements = () => ({
        province: document.getElementById('create-province'),
        regency: document.getElementById('create-regency'),
        district: document.getElementById('create-district'),
        village: document.getElementById('create-village')
    });

    // Remove existing listeners dengan clone
    const currentElements = getElements();
    
    // Clone elements untuk remove existing listeners
    const newProvinceSelect = currentElements.province.cloneNode(true);
    const newRegencySelect = currentElements.regency.cloneNode(true);
    const newDistrictSelect = currentElements.district.cloneNode(true);
    const newVillageSelect = currentElements.village.cloneNode(true);

    currentElements.province.parentNode.replaceChild(newProvinceSelect, currentElements.province);
    currentElements.regency.parentNode.replaceChild(newRegencySelect, currentElements.regency);
    currentElements.district.parentNode.replaceChild(newDistrictSelect, currentElements.district);
    currentElements.village.parentNode.replaceChild(newVillageSelect, currentElements.village);

    // Province change handler
    newProvinceSelect.addEventListener('change', function() {
        const provinceId = this.value;
        console.log('Province selected:', provinceId);
        
        const els = getElements();
        
        // Reset dependent dropdowns
        els.regency.innerHTML = '<option value="">-- Loading... --</option>';
        els.district.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
        els.village.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';
        
        els.regency.disabled = true;
        els.district.disabled = true;
        els.village.disabled = true;

        if (provinceId) {
            console.log('Fetching regencies for province:', provinceId);
            
            fetch(`/get-regencies/${provinceId}`)
                .then(response => {
                    console.log('Response status:', response.status);
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Regencies data received:', data);
                    
                    const currentEls = getElements();
                    currentEls.regency.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
                    
                    if (data && Array.isArray(data) && data.length > 0) {
                        data.forEach((item, index) => {
                            console.log(`Adding regency ${index}:`, item);
                            currentEls.regency.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                        });
                        currentEls.regency.disabled = false;
                        console.log('Regencies loaded successfully');
                    } else {
                        currentEls.regency.innerHTML += '<option value="">-- Tidak ada data --</option>';
                        currentEls.regency.disabled = false;
                        console.log('No regencies data found');
                    }
                })
                .catch(error => {
                    console.error('Error fetching regencies:', error);
                    const currentEls = getElements();
                    currentEls.regency.innerHTML = '<option value="">-- Error loading data --</option>';
                    currentEls.regency.disabled = false;
                    showUserNotification('Gagal memuat data kabupaten/kota', 'error');
                });
        } else {
            const currentEls = getElements();
            currentEls.regency.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
            currentEls.regency.disabled = false;
            console.log('Province cleared, resetting regency');
        }
    });

    // Regency change handler
    newRegencySelect.addEventListener('change', function() {
        const regencyId = this.value;
        console.log('Regency selected:', regencyId);
        
        const els = getElements();
        
        els.district.innerHTML = '<option value="">-- Loading... --</option>';
        els.village.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';
        
        els.district.disabled = true;
        els.village.disabled = true;

        if (regencyId) {
            console.log('Fetching districts for regency:', regencyId);
            
            fetch(`/get-districts/${regencyId}`)
                .then(response => {
                    console.log('Districts response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Districts data received:', data);
                    
                    const currentEls = getElements();
                    currentEls.district.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                    
                    if (data && Array.isArray(data) && data.length > 0) {
                        data.forEach(item => {
                            currentEls.district.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                        });
                        currentEls.district.disabled = false;
                        console.log('Districts loaded successfully');
                    } else {
                        currentEls.district.innerHTML += '<option value="">-- Tidak ada data --</option>';
                        currentEls.district.disabled = false;
                        console.log('No districts data found');
                    }
                })
                .catch(error => {
                    console.error('Error fetching districts:', error);
                    const currentEls = getElements();
                    currentEls.district.innerHTML = '<option value="">-- Error loading data --</option>';
                    currentEls.district.disabled = false;
                    showUserNotification('Gagal memuat data kecamatan', 'error');
                });
        } else {
            const currentEls = getElements();
            currentEls.district.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
            currentEls.district.disabled = false;
        }
    });

    // District change handler  
    newDistrictSelect.addEventListener('change', function() {
        const districtId = this.value;
        console.log('District selected:', districtId);
        
        const els = getElements();
        
        els.village.innerHTML = '<option value="">-- Loading... --</option>';
        els.village.disabled = true;

        if (districtId) {
            console.log('Fetching villages for district:', districtId);
            
            fetch(`/get-villages/${districtId}`)
                .then(response => {
                    console.log('Villages response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Villages data received:', data);
                    
                    const currentEls = getElements();
                    currentEls.village.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';
                    
                    if (data && Array.isArray(data) && data.length > 0) {
                        data.forEach(item => {
                            currentEls.village.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                        });
                        currentEls.village.disabled = false;
                        console.log('Villages loaded successfully');
                    } else {
                        currentEls.village.innerHTML += '<option value="">-- Tidak ada data --</option>';
                        currentEls.village.disabled = false;
                        console.log('No villages data found');
                    }
                })
                .catch(error => {
                    console.error('Error fetching villages:', error);
                    const currentEls = getElements();
                    currentEls.village.innerHTML = '<option value="">-- Error loading data --</option>';
                    currentEls.village.disabled = false;
                    showUserNotification('Gagal memuat data kelurahan/desa', 'error');
                });
        } else {
            const currentEls = getElements();
            currentEls.village.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';
            currentEls.village.disabled = false;
        }
    });

    console.log('Address cascade initialized successfully');
}

// Test function - panggil di console browser
function testCascade() {
    console.log('Testing cascade setup...');
    checkElements();
    initializeAddressCascade();
}

// Event listeners
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('userModal').classList.contains('hidden')) {
        closeUserModal();
    }
});

// Modal click outside to close
document.addEventListener('click', function(e) {
    const modal = document.getElementById('userModal');
    if (modal && e.target === modal) {
        closeUserModal();
    }
});

// Real-time validation
document.addEventListener('input', function(e) {
    if (e.target.matches('#userModal input, #userModal textarea')) {
        clearUserFieldError(e.target);
    }
});

// Role selection change handler
document.addEventListener('change', function(e) {
    if (e.target.matches('#userModal select[name="role_id"]')) {
        showRolePreview(e.target.value);
    }
});

// Initialize saat DOM loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Initializing cascade');
    setTimeout(() => {
        initializeAddressCascade();
        setupFormSubmission();
    }, 100);
});
</script>