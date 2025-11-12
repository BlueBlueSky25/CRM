// ==================== GLOBAL VARIABLES ====================
let createVisitCascade = null;
let editVisitCascade = null;
let currentEditData = null;

// Company & PIC Dropdown Variables
let companyDropdownTimeout = null;
let currentCompanies = [];
let picDropdownTimeout = null;
let currentPICs = [];
let selectedCompanyId = null;
let editSelectedCompanyId = null;

// ==================== ADD MODAL ====================
function openVisitModal() {
    const modal = document.getElementById('visitModal');
    modal.style.display = 'flex';
    modal.style.zIndex = '9999';
    document.body.style.overflow = 'hidden';
    
    initCreateVisitCascade();
    initCompanyDropdown();
    initPICDropdown();
    initializePICInput(); 
    
    setTimeout(() => {
        const firstInput = modal.querySelector('select[name="sales_id"]');
        if (firstInput && !firstInput.disabled) firstInput.focus();
    }, 300);
}

function initCreateVisitCascade() {
    if (createVisitCascade) {
        createVisitCascade.destroy();
    }

    createVisitCascade = new AddressCascade({
        provinceId: 'create-province',
        regencyId: 'create-regency',
        districtId: 'create-district',
        villageId: 'create-village',
        baseUrl: '/salesvisit'
    });
}

function closeVisitModal() {
    const modal = document.getElementById('visitModal');
    modal.style.display = 'none';
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    if (createVisitCascade) {
        createVisitCascade.destroy();
        createVisitCascade = null;
    }
    
    const form = modal.querySelector('form');
    if (form) form.reset();
    
    // Reset dropdowns
    document.getElementById('create-regency').innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
    document.getElementById('create-district').innerHTML = '<option value="">Pilih Kecamatan</option>';
    document.getElementById('create-village').innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
    
    // Reset company & PIC
    document.getElementById('create-company-id').value = '';
    document.getElementById('create-company-search').value = '';
    document.getElementById('create-company-dropdown').classList.add('hidden');
    selectedCompanyId = null;
    
    initializePICInput();
    
    // Reset address state
    const content = document.getElementById('address-content');
    const icon = document.getElementById('address-toggle-icon');
    const statusText = document.getElementById('address-status');
    
    if (content) content.classList.add('hidden');
    if (icon) icon.style.transform = 'rotate(0deg)';
    if (statusText) {
        statusText.textContent = 'Belum diisi';
        statusText.classList.remove('text-green-600', 'font-medium');
        statusText.classList.add('text-gray-500');
    }
}

// ==================== COMPANY DROPDOWN (CREATE) ====================
async function loadCompanies(search = '') {
    try {
        const response = await fetch('/company/get-companies-dropdown');
        const data = await response.json();
        if (data.success) {
            currentCompanies = data.companies;
            updateCompanyDropdown(search);
        }
    } catch (error) {
        console.error('Error loading companies:', error);
    }
}

function updateCompanyDropdown(search = '') {
    const dropdown = document.getElementById('create-company-options');
    const searchTerm = search.toLowerCase();
    const filteredCompanies = currentCompanies.filter(company => 
        company.name.toLowerCase().includes(searchTerm)
    );
    
    dropdown.innerHTML = '';
    
    if (filteredCompanies.length === 0 && search.trim() !== '') {
        const addOption = document.createElement('div');
        addOption.className = 'px-3 py-2 text-sm text-green-600 hover:bg-green-50 cursor-pointer flex items-center gap-2';
        addOption.innerHTML = `<i class="fas fa-plus text-xs"></i><span>Tambah "${search}" sebagai company baru</span>`;
        addOption.onclick = () => {
            document.querySelector('#addCompanyModal input[name="company_name"]').value = search;
            showAddCompanyModal();
        };
        dropdown.appendChild(addOption);
    } else {
        filteredCompanies.forEach(company => {
            const option = document.createElement('div');
            option.className = 'px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 cursor-pointer';
            option.textContent = company.name;
            option.onclick = () => selectCompany(company.id, company.name);
            dropdown.appendChild(option);
        });
    }
    
    const dropdownContainer = document.getElementById('create-company-dropdown');
    if (filteredCompanies.length > 0 || search.trim() !== '') {
        dropdownContainer.classList.remove('hidden');
    } else {
        dropdownContainer.classList.add('hidden');
    }
}

function selectCompany(companyId, companyName) {
    console.log('✅ Company selected:', { companyId, companyName });
    
    document.getElementById('create-company-id').value = companyId;
    document.getElementById('create-company-search').value = companyName;
    document.getElementById('create-company-dropdown').classList.add('hidden');
    
    selectedCompanyId = companyId;
    
    enablePICInput();
    loadPICsByCompany(companyId);
}

function initCompanyDropdown() {
    const searchInput = document.getElementById('create-company-search');
    const dropdown = document.getElementById('create-company-dropdown');
    
    if (!searchInput || !dropdown) return;
    
    searchInput.addEventListener('focus', () => loadCompanies(searchInput.value));
    
    searchInput.addEventListener('input', (e) => {
        clearTimeout(companyDropdownTimeout);
        companyDropdownTimeout = setTimeout(() => updateCompanyDropdown(e.target.value), 300);
    });
    
    searchInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            const searchValue = searchInput.value.trim();
            if (searchValue) {
                const matchedCompany = currentCompanies.find(company => 
                    company.name.toLowerCase() === searchValue.toLowerCase()
                );
                if (!matchedCompany) {
                    document.querySelector('#addCompanyModal input[name="company_name"]').value = searchValue;
                    showAddCompanyModal();
                }
            }
        }
    });
    
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
    
    loadCompanies();
}

// ==================== PIC DROPDOWN (CREATE) ====================
function initializePICInput() {
    const picInput = document.getElementById('create-pic-search');
    const picContainer = document.getElementById('pic-input-container');
    
    if (picContainer) {
        picContainer.classList.remove('hidden');
        
        if (picInput) {
            picInput.disabled = true;
            picInput.placeholder = 'Pilih company terlebih dahulu...';
            picInput.classList.add('bg-gray-100', 'cursor-not-allowed');
            picInput.classList.remove('bg-white');
        }
        
        document.getElementById('create-pic-id').value = '';
        document.getElementById('create-pic-name-hidden').value = '';
        document.getElementById('create-pic-search').value = '';
    }
}

function enablePICInput() {
    const picInput = document.getElementById('create-pic-search');
    const picContainer = document.getElementById('pic-input-container');
    
    if (picContainer && picInput) {
        picContainer.classList.remove('hidden');
        picInput.disabled = false;
        picInput.placeholder = 'Ketik atau pilih PIC...';
        picInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
        picInput.classList.add('bg-white');
        
        document.getElementById('create-pic-id').value = '';
        document.getElementById('create-pic-name-hidden').value = '';
        document.getElementById('create-pic-search').value = '';
    }
}

async function loadPICsByCompany(companyId, search = '') {
    try {
        console.log('🔄 Loading PICs for company:', companyId);
        
        const response = await fetch(`/pics/by-company/${companyId}`);
        const data = await response.json();
        
        console.log('📋 PICs response:', data);
        
        if (data.success) {
            currentPICs = data.pics;
            updatePICDropdown(search);
        }
    } catch (error) {
        console.error('Error loading PICs:', error);
    }
}

function updatePICDropdown(search = '') {
    const dropdown = document.getElementById('create-pic-options');
    const searchTerm = search.toLowerCase();
    const filteredPICs = currentPICs.filter(pic => 
        pic.name.toLowerCase().includes(searchTerm)
    );
    
    dropdown.innerHTML = '';
    
    if (filteredPICs.length === 0 && search.trim() !== '') {
        const addOption = document.createElement('div');
        addOption.className = 'px-3 py-2 text-sm text-green-600 hover:bg-green-50 cursor-pointer flex items-center gap-2';
        addOption.innerHTML = `<i class="fas fa-plus text-xs"></i><span>Tambah "${search}" sebagai PIC baru</span>`;
        addOption.onclick = () => {
            document.querySelector('#addPICModal input[name="pic_name"]').value = search;
            showAddPICModal();
        };
        dropdown.appendChild(addOption);
    } else {
        filteredPICs.forEach(pic => {
            const option = document.createElement('div');
            option.className = 'px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 cursor-pointer';
            
            let displayText = pic.name;
            if (pic.position) {
                displayText += ` - ${pic.position}`;
            }
            
            option.textContent = displayText;
            option.onclick = () => selectPIC(pic.id, pic.name);
            dropdown.appendChild(option);
        });
    }
    
    const dropdownContainer = document.getElementById('create-pic-dropdown');
    if (filteredPICs.length > 0 || search.trim() !== '') {
        dropdownContainer.classList.remove('hidden');
    } else {
        dropdownContainer.classList.add('hidden');
    }
}

function selectPIC(picId, picName) {
    console.log('✅ PIC selected:', { picId, picName });
    
    document.getElementById('create-pic-id').value = picId;
    document.getElementById('create-pic-name-hidden').value = picName;
    document.getElementById('create-pic-search').value = picName;
    document.getElementById('create-pic-dropdown').classList.add('hidden');
}

function initPICDropdown() {
    const searchInput = document.getElementById('create-pic-search');
    const dropdown = document.getElementById('create-pic-dropdown');
    
    if (!searchInput || !dropdown) return;
    
    searchInput.addEventListener('focus', () => {
        if (selectedCompanyId) {
            loadPICsByCompany(selectedCompanyId, searchInput.value);
        }
    });
    
    searchInput.addEventListener('input', (e) => {
        clearTimeout(picDropdownTimeout);
        picDropdownTimeout = setTimeout(() => updatePICDropdown(e.target.value), 300);
    });
    
    searchInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            const searchValue = searchInput.value.trim();
            if (searchValue && selectedCompanyId) {
                const matchedPIC = currentPICs.find(pic => 
                    pic.name.toLowerCase() === searchValue.toLowerCase()
                );
                if (!matchedPIC) {
                    document.querySelector('#addPICModal input[name="pic_name"]').value = searchValue;
                    showAddPICModal();
                }
            }
        }
    });
    
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
}

// ==================== COMPANY & PIC MODALS ====================
function showAddCompanyModal() {
    document.getElementById('addCompanyForm').reset();
    const modal = document.getElementById('addCompanyModal');
    modal.style.display = 'flex';
    modal.classList.remove('hidden');
    setTimeout(() => {
        document.querySelector('#addCompanyModal input[name="company_name"]').focus();
    }, 300);
}

function closeAddCompanyModal() {
    const modal = document.getElementById('addCompanyModal');
    modal.style.display = 'none';
    modal.classList.add('hidden');
    document.getElementById('addCompanyForm').reset();
}

function showAddPICModal() {
    if (!selectedCompanyId && !editSelectedCompanyId) {
        alert('Silakan pilih company terlebih dahulu!');
        return;
    }
    
    const companyId = selectedCompanyId || editSelectedCompanyId;
    document.getElementById('pic-form-company-id').value = companyId;
    document.getElementById('addPICForm').reset();
    const modal = document.getElementById('addPICModal');
    modal.style.display = 'flex';
    modal.classList.remove('hidden');
    setTimeout(() => {
        document.querySelector('#addPICModal input[name="pic_name"]').focus();
    }, 300);
}

function closeAddPICModal() {
    const modal = document.getElementById('addPICModal');
    modal.style.display = 'none';
    modal.classList.add('hidden');
    document.getElementById('addPICForm').reset();
}

// Company Form Submit
document.getElementById('addCompanyForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    try {
        const response = await fetch('/company/store-company-ajax', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        });
        
        if (response.status === 422) {
            const errorData = await response.json();
            let errorMessages = [];
            if (errorData.errors) {
                for (const field in errorData.errors) {
                    errorMessages.push(`${field}: ${errorData.errors[field].join(', ')}`);
                }
            }
            alert('Validasi gagal:\n' + errorMessages.join('\n'));
            return;
        }
        
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        
        const data = await response.json();
        
        if (data.success) {
            const newCompany = data.company;
            
            if (document.getElementById('editVisitModal').style.display === 'none') {
                selectCompany(newCompany.id, newCompany.name);
            } else {
                selectEditCompany(newCompany.id, newCompany.name);
            }
            
            loadCompanies();
            closeAddCompanyModal();
            alert('Company berhasil ditambahkan!');
        } else {
            throw new Error(data.message || 'Gagal menambahkan company');
        }
    } catch (error) {
        console.error('Error adding company:', error);
        alert('Gagal menambahkan company: ' + error.message);
    }
});

// PIC Form Submit
document.getElementById('addPICForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    try {
        const response = await fetch('/pics/store-pic-ajax', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        });
        
        if (response.status === 422) {
            const errorData = await response.json();
            let errorMessages = [];
            if (errorData.errors) {
                for (const field in errorData.errors) {
                    errorMessages.push(`${field}: ${errorData.errors[field].join(', ')}`);
                }
            }
            alert('Validasi gagal:\n' + errorMessages.join('\n'));
            return;
        }
        
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        
        const data = await response.json();
        
        if (data.success) {
            const newPIC = data.pic;
            
            if (document.getElementById('editVisitModal').style.display === 'none') {
                selectPIC(newPIC.id, newPIC.name);
                if (selectedCompanyId) {
                    loadPICsByCompany(selectedCompanyId);
                }
            } else {
                selectEditPIC(newPIC.id, newPIC.name);
                if (editSelectedCompanyId) {
                    loadEditPICsByCompany(editSelectedCompanyId);
                }
            }
            
            closeAddPICModal();
            alert('PIC berhasil ditambahkan!');
        } else {
            throw new Error(data.message || 'Gagal menambahkan PIC');
        }
    } catch (error) {
        console.error('Error adding PIC:', error);
        alert('Gagal menambahkan PIC: ' + error.message);
    }
});

// ==================== EDIT MODAL ====================
function openEditVisitModal(visitData) {
    console.log('🚀 Opening Edit Visit Modal with data:', visitData);

    if (!visitData || !visitData.id) {
        console.error('❌ Invalid visit data:', visitData);
        showNotification('Data kunjungan tidak valid', 'error');
        return;
    }

    try {
        visitData.id = parseInt(visitData.id);
        visitData.salesId = parseInt(visitData.salesId);
        visitData.followUp = parseInt(visitData.followUp);
        
        visitData.provinceId = visitData.provinceId ? String(visitData.provinceId) : null;
        visitData.regencyId = visitData.regencyId ? String(visitData.regencyId) : null;
        visitData.districtId = visitData.districtId ? String(visitData.districtId) : null;
        visitData.villageId = visitData.villageId ? String(visitData.villageId) : null;

        console.log('✅ Parsed visit data:', visitData);

        const modal = document.getElementById('editVisitModal');
        if (!modal) {
            console.error('❌ Edit modal element not found');
            return;
        }
        
        // ✅ FIX: Pakai inline style display
        modal.style.display = 'flex';
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        console.log('✅ Modal displayed');
        
        initializeEditPICInput();

        const form = document.getElementById('editVisitForm');
        if (form) {
            form.action = `/salesvisit/${visitData.id}`;
        }

        const editVisitId = document.getElementById('editVisitId');
        const editVisitDate = document.getElementById('editVisitDate');
        const editAddress = document.getElementById('editAddress');
        const editPurpose = document.getElementById('editPurpose');
        
        if (editVisitId) editVisitId.value = visitData.id || '';
        if (editVisitDate) editVisitDate.value = visitData.visitDate || '';
        if (editAddress) editAddress.value = visitData.address || '';
        if (editPurpose) editPurpose.value = visitData.purpose || '';
        
        if (visitData.picId && visitData.picName) {
            console.log('✅ Setting PIC data:', { picId: visitData.picId, picName: visitData.picName });
            const editPicId = document.getElementById('edit-pic-id');
            const editPicNameHidden = document.getElementById('edit-pic-name-hidden');
            const editPicSearch = document.getElementById('edit-pic-search');
            
            if (editPicId) editPicId.value = visitData.picId;
            if (editPicNameHidden) editPicNameHidden.value = visitData.picName;
            if (editPicSearch) editPicSearch.value = visitData.picName;
        } else {
            console.log('⚠️ No PIC data available');
        }
        
        const editFollowUpYes = document.getElementById('editFollowUpYes');
        const editFollowUpNo = document.getElementById('editFollowUpNo');
        
        if (visitData.followUp == 1 && editFollowUpYes) {
            editFollowUpYes.checked = true;
        } else if (editFollowUpNo) {
            editFollowUpNo.checked = true;
        }

        currentEditData = {
            salesId: visitData.salesId || '',
            companyId: visitData.companyId || null,
            companyName: visitData.companyName || '',
            picId: visitData.picId || null,
            picName: visitData.picName || '',
            provinceId: visitData.provinceId || '',
            regencyId: visitData.regencyId || null,
            districtId: visitData.districtId || null,
            villageId: visitData.villageId || null
        };

        console.log('✅ Current edit data stored:', currentEditData);
        
        loadEditVisitData(visitData.id);
        
    } catch (error) {
        console.error('❌ Error opening edit modal:', error);
        showNotification('Gagal membuka modal edit: ' + error.message, 'error');
    }
}

function loadEditVisitData(visitId) {
    console.log('🔥 Loading edit data for visit:', visitId);
    
    const salesSelect = document.getElementById('editSalesId');
    const provinceSelect = document.getElementById('edit-province');
    
    if (!salesSelect || !provinceSelect) {
        console.error('❌ Sales or Province select not found!');
        return;
    }
    
    salesSelect.innerHTML = '<option value="">Loading...</option>';
    salesSelect.disabled = true;
    provinceSelect.innerHTML = '<option value="">Loading...</option>';
    provinceSelect.disabled = true;

    Promise.all([
        fetch('/salesvisit/get-sales').then(r => r.json()),
        fetch('/salesvisit/get-provinces').then(r => r.json())
    ]).then(([salesData, provincesResponse]) => {
        salesSelect.innerHTML = '<option value="">Pilih Sales</option>';
        
        let salesList = salesData.users || salesData.data || salesData;
        if (Array.isArray(salesList)) {
            salesList.forEach(sales => {
                const option = document.createElement('option');
                option.value = sales.user_id;
                option.textContent = `${sales.username} - ${sales.email}`;
                if (sales.user_id == currentEditData.salesId) {
                    option.selected = true;
                }
                salesSelect.appendChild(option);
            });
        }
        salesSelect.disabled = false;

        provinceSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
        
        let provincesList = provincesResponse.provinces || provincesResponse.data || provincesResponse;
        if (Array.isArray(provincesList)) {
            provincesList.forEach(province => {
                const option = document.createElement('option');
                option.value = province.id;
                option.textContent = province.name;
                if (String(province.id) == String(currentEditData.provinceId)) {
                    option.selected = true;
                }
                provinceSelect.appendChild(option);
            });
        }
        provinceSelect.disabled = false;
        
        setTimeout(() => {
            initEditCompanyDropdown();
            initEditPICDropdown();
            initEditVisitCascade();
            
            if (currentEditData.companyId && currentEditData.companyName) {
                selectEditCompany(currentEditData.companyId, currentEditData.companyName);
                
                if (currentEditData.picId && currentEditData.picName) {
                    setTimeout(() => {
                        selectEditPIC(currentEditData.picId, currentEditData.picName);
                    }, 500);
                }
            }
        }, 500);
        
    }).catch(error => {
        console.error('❌ Error loading edit data:', error);
        showNotification('Gagal memuat data: ' + error.message, 'error');
        
        salesSelect.disabled = false;
        salesSelect.innerHTML = '<option value="">Error loading data</option>';
        provinceSelect.disabled = false;
        provinceSelect.innerHTML = '<option value="">Error loading data</option>';
    });
}

// ==================== EDIT COMPANY DROPDOWN ====================
let editCompanyDropdownTimeout = null;
let editCurrentCompanies = [];

async function loadEditCompanies(search = '') {
    try {
        const response = await fetch('/company/get-companies-dropdown');
        const data = await response.json();
        if (data.success) {
            editCurrentCompanies = data.companies;
            updateEditCompanyDropdown(search);
        }
    } catch (error) {
        console.error('Error loading edit companies:', error);
    }
}

function updateEditCompanyDropdown(search = '') {
    const dropdown = document.getElementById('edit-company-options');
    const searchTerm = search.toLowerCase();
    const filteredCompanies = editCurrentCompanies.filter(company => 
        company.name.toLowerCase().includes(searchTerm)
    );
    
    dropdown.innerHTML = '';
    
    filteredCompanies.forEach(company => {
        const option = document.createElement('div');
        option.className = 'px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 cursor-pointer';
        option.textContent = company.name;
        option.onclick = () => selectEditCompany(company.id, company.name);
        dropdown.appendChild(option);
    });
    
    const dropdownContainer = document.getElementById('edit-company-dropdown');
    if (filteredCompanies.length > 0) {
        dropdownContainer.classList.remove('hidden');
    } else {
        dropdownContainer.classList.add('hidden');
    }
}

function selectEditCompany(companyId, companyName) {
    console.log('✅ Edit company selected:', { companyId, companyName });
    
    document.getElementById('edit-company-id').value = companyId;
    document.getElementById('edit-company-search').value = companyName;
    document.getElementById('edit-company-dropdown').classList.add('hidden');
    
    editSelectedCompanyId = companyId;
    
    enableEditPICInput();
    loadEditPICsByCompany(companyId);
}

function initEditCompanyDropdown() {
    const searchInput = document.getElementById('edit-company-search');
    const dropdown = document.getElementById('edit-company-dropdown');
    
    if (!searchInput || !dropdown) return;
    
    searchInput.addEventListener('focus', () => loadEditCompanies(searchInput.value));
    
    searchInput.addEventListener('input', (e) => {
        clearTimeout(editCompanyDropdownTimeout);
        editCompanyDropdownTimeout = setTimeout(() => updateEditCompanyDropdown(e.target.value), 300);
    });
    
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
    
    loadEditCompanies();
}

// ==================== EDIT PIC DROPDOWN ====================
let editPICDropdownTimeout = null;
let editCurrentPICs = [];

function initializeEditPICInput() {
    const picInput = document.getElementById('edit-pic-search');
    const picContainer = document.getElementById('edit-pic-input-container');
    
    if (picContainer) {
        picContainer.classList.remove('hidden');
        
        if (picInput) {
            picInput.disabled = true;
            picInput.placeholder = 'Pilih company terlebih dahulu...';
            picInput.classList.add('bg-gray-100', 'cursor-not-allowed');
            picInput.classList.remove('bg-white');
        }
        
        document.getElementById('edit-pic-id').value = '';
        document.getElementById('edit-pic-name-hidden').value = '';
        document.getElementById('edit-pic-search').value = '';
    }
}

function enableEditPICInput() {
    const picInput = document.getElementById('edit-pic-search');
    const picContainer = document.getElementById('edit-pic-input-container');
    
    if (picContainer && picInput) {
        picContainer.classList.remove('hidden');
        picInput.disabled = false;
        picInput.placeholder = 'Ketik atau pilih PIC...';
        picInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
        picInput.classList.add('bg-white');
        
        document.getElementById('edit-pic-id').value = '';
        document.getElementById('edit-pic-name-hidden').value = '';
        document.getElementById('edit-pic-search').value = '';
    }
}

async function loadEditPICsByCompany(companyId, search = '') {
    try {
        console.log('🔄 Loading edit PICs for company:', companyId);
        
        const response = await fetch(`/pics/by-company/${companyId}`);
        const data = await response.json();
        
        console.log('📋 Edit PICs response:', data);
        
        if (data.success) {
            editCurrentPICs = data.pics;
            updateEditPICDropdown(search);
        }
    } catch (error) {
        console.error('Error loading edit PICs:', error);
    }
}

function updateEditPICDropdown(search = '') {
    const dropdown = document.getElementById('edit-pic-options');
    const searchTerm = search.toLowerCase();
    const filteredPICs = editCurrentPICs.filter(pic => 
        pic.name.toLowerCase().includes(searchTerm)
    );
    
    dropdown.innerHTML = '';
    
    filteredPICs.forEach(pic => {
        const option = document.createElement('div');
        option.className = 'px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 cursor-pointer';
        
        let displayText = pic.name;
        if (pic.position) {
            displayText += ` - ${pic.position}`;
        }
        
        option.textContent = displayText;
        option.onclick = () => selectEditPIC(pic.id, pic.name);
        dropdown.appendChild(option);
    });
    
    const dropdownContainer = document.getElementById('edit-pic-dropdown');
    if (filteredPICs.length > 0) {
        dropdownContainer.classList.remove('hidden');
    } else {
        dropdownContainer.classList.add('hidden');
    }
}

function selectEditPIC(picId, picName) {
    console.log('✅ Edit PIC selected:', { picId, picName });
    
    document.getElementById('edit-pic-id').value = picId;
    document.getElementById('edit-pic-name-hidden').value = picName;
    document.getElementById('edit-pic-search').value = picName;
    document.getElementById('edit-pic-dropdown').classList.add('hidden');
}

function initEditPICDropdown() {
    const searchInput = document.getElementById('edit-pic-search');
    const dropdown = document.getElementById('edit-pic-dropdown');
    
    if (!searchInput || !dropdown) return;
    
    searchInput.addEventListener('focus', () => {
        if (editSelectedCompanyId) {
            loadEditPICsByCompany(editSelectedCompanyId, searchInput.value);
        }
    });
    
    searchInput.addEventListener('input', (e) => {
        clearTimeout(editPICDropdownTimeout);
        editPICDropdownTimeout = setTimeout(() => updateEditPICDropdown(e.target.value), 300);
    });
    
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
}

// ==================== EDIT CASCADE ====================
function initEditVisitCascade() {
    console.log('🔄 Initializing edit cascade with data:', currentEditData);
    
    if (editVisitCascade) {
        editVisitCascade.destroy();
    }

    editVisitCascade = new AddressCascade({
        provinceId: 'edit-province',
        regencyId: 'edit-regency',
        districtId: 'edit-district',
        villageId: 'edit-village',
        baseUrl: '/salesvisit'
    });

    if (currentEditData.provinceId) {
        console.log('🔄 Loading regencies for province:', currentEditData.provinceId);
        
        const provinceSelect = document.getElementById('edit-province');
        provinceSelect.value = String(currentEditData.provinceId);
        
        const changeEvent = new Event('change', { bubbles: true });
        provinceSelect.dispatchEvent(changeEvent);
        
        if (currentEditData.regencyId) {
            console.log('⏳ Waiting for regencies to load...');
            
            let regencyWaitCount = 0;
            const maxRegencyWait = 50;
            
            const waitForRegencies = setInterval(() => {
                const regencySelect = document.getElementById('edit-regency');
                regencyWaitCount++;
                
                if (regencySelect.options.length > 1) {
                    clearInterval(waitForRegencies);
                    console.log('✅ Regencies loaded, setting value:', currentEditData.regencyId);
                    
                    regencySelect.value = String(currentEditData.regencyId);
                    
                    if (regencySelect.value == currentEditData.regencyId) {
                        console.log('✅ Regency value set successfully');
                        regencySelect.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                    
                    if (currentEditData.districtId) {
                        console.log('⏳ Waiting for districts to load...');
                        
                        let districtWaitCount = 0;
                        const maxDistrictWait = 50;
                        
                        const waitForDistricts = setInterval(() => {
                            const districtSelect = document.getElementById('edit-district');
                            districtWaitCount++;
                            
                            if (districtSelect.options.length > 1) {
                                clearInterval(waitForDistricts);
                                console.log('✅ Districts loaded, setting value:', currentEditData.districtId);
                                
                                districtSelect.value = String(currentEditData.districtId);
                                
                                if (districtSelect.value == currentEditData.districtId) {
                                    console.log('✅ District value set successfully');
                                    districtSelect.dispatchEvent(new Event('change', { bubbles: true }));
                                    
                                    if (currentEditData.villageId) {
                                        console.log('⏳ Waiting for villages to load...');
                                        
                                        let villageWaitCount = 0;
                                        const maxVillageWait = 50;
                                        
                                        const waitForVillages = setInterval(() => {
                                            const villageSelect = document.getElementById('edit-village');
                                            villageWaitCount++;
                                            
                                            if (villageSelect.options.length > 1) {
                                                clearInterval(waitForVillages);
                                                console.log('✅ Villages loaded, setting value:', currentEditData.villageId);
                                                
                                                villageSelect.value = String(currentEditData.villageId);
                                                
                                                if (villageSelect.value == currentEditData.villageId) {
                                                    console.log('✅ Village value set successfully');
                                                }
                                            }
                                            
                                            if (villageWaitCount >= maxVillageWait) {
                                                clearInterval(waitForVillages);
                                                console.warn('⚠️ Timeout waiting for villages');
                                            }
                                        }, 200);
                                    }
                                }
                            }
                            
                            if (districtWaitCount >= maxDistrictWait) {
                                clearInterval(waitForDistricts);
                                console.warn('⚠️ Timeout waiting for districts');
                            }
                        }, 200);
                    }
                }
                
                if (regencyWaitCount >= maxRegencyWait) {
                    clearInterval(waitForRegencies);
                    console.warn('⚠️ Timeout waiting for regencies');
                }
            }, 200);
        }
    }
}

function closeEditVisitModal() {
    const modal = document.getElementById('editVisitModal');
    // ✅ FIX: Pakai inline style display
    modal.style.display = 'none';
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';

    if (editVisitCascade) {
        editVisitCascade.destroy();
        editVisitCascade = null;
    }

    currentEditData = null;
    editSelectedCompanyId = null;

    const form = document.getElementById('editVisitForm');
    if (form) form.reset();

    const salesSelect = document.getElementById('editSalesId');
    salesSelect.disabled = false;
    salesSelect.innerHTML = '<option value="">Pilih Sales</option>';

    document.getElementById('edit-province').innerHTML = '<option value="">Pilih Provinsi</option>';
    document.getElementById('edit-regency').innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
    document.getElementById('edit-district').innerHTML = '<option value="">Pilih Kecamatan</option>';
    document.getElementById('edit-village').innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
    
    document.getElementById('edit-company-id').value = '';
    document.getElementById('edit-company-search').value = '';
    document.getElementById('edit-company-dropdown').classList.add('hidden');
    
    initializeEditPICInput();
    
    const content = document.getElementById('edit-address-content');
    const icon = document.getElementById('edit-address-toggle-icon');
    const statusText = document.getElementById('edit-address-status');
    
    if (content) content.classList.add('hidden');
    if (icon) icon.style.transform = 'rotate(0deg)';
    if (statusText) {
        statusText.textContent = 'Belum diisi';
        statusText.classList.remove('text-green-600', 'font-medium');
        statusText.classList.add('text-gray-500');
    }
}

// ✅ Window function untuk close modal
window.closeEditVisitModal = function() {
    closeEditVisitModal();
};

// ==================== DELETE ====================
function deleteVisit(visitId, deleteRoute, csrfToken) {
    console.log('🗑️ deleteVisit called:', { visitId, deleteRoute, csrfToken });

    if (confirm('Apakah Anda yakin ingin menghapus data kunjungan ini?')) {
        const correctRoute = `/salesvisit/${visitId}`;
        
        fetch(correctRoute, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(errorData => {
                    throw new Error(errorData.message || 'Network response was not ok');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                throw new Error(data.message || 'Gagal menghapus data');
            }
        })
        .catch(error => {
            console.error('❌ Error:', error);
            showNotification('Gagal menghapus data: ' + error.message, 'error');
        });
    }
}

// ==================== ADDRESS SECTION TOGGLE ====================
function toggleAddressSection() {
    const content = document.getElementById('address-content');
    const icon = document.getElementById('address-toggle-icon');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

function checkAddressCompletion() {
    const province = document.getElementById('create-province').value;
    const address = document.getElementById('create-address').value.trim();
    const statusText = document.getElementById('address-status');
    const content = document.getElementById('address-content');
    const icon = document.getElementById('address-toggle-icon');
    
    if (province && address) {
        statusText.textContent = 'Sudah diisi';
        statusText.classList.remove('text-gray-500');
        statusText.classList.add('text-green-600', 'font-medium');
        
        setTimeout(() => {
            if (!content.classList.contains('hidden')) {
                content.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        }, 800);
    } else {
        statusText.textContent = 'Belum diisi';
        statusText.classList.remove('text-green-600', 'font-medium');
        statusText.classList.add('text-gray-500');
    }
}

function toggleEditAddressSection() {
    const content = document.getElementById('edit-address-content');
    const icon = document.getElementById('edit-address-toggle-icon');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

function checkEditAddressCompletion() {
    const province = document.getElementById('edit-province').value;
    const address = document.getElementById('editAddress').value.trim();
    const statusText = document.getElementById('edit-address-status');
    const content = document.getElementById('edit-address-content');
    const icon = document.getElementById('edit-address-toggle-icon');
    
    if (province && address) {
        statusText.textContent = 'Sudah diisi';
        statusText.classList.remove('text-gray-500');
        statusText.classList.add('text-green-600', 'font-medium');
        
        setTimeout(() => {
            if (!content.classList.contains('hidden')) {
                content.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        }, 800);
    } else {
        statusText.textContent = 'Belum diisi';
        statusText.classList.remove('text-green-600', 'font-medium');
        statusText.classList.add('text-gray-500');
    }
}

// ==================== NOTIFICATION SYSTEM ====================
function showNotification(message, type = 'info') {
    let notification = document.getElementById('global-notification');
    if (!notification) {
        notification = document.createElement('div');
        notification.id = 'global-notification';
        document.body.appendChild(notification);
    }
    
    const bgColor = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500'
    };
    
    notification.className = `fixed top-4 right-4 z-[1000] p-4 rounded-lg shadow-lg text-white transform transition-all duration-300 ${bgColor[type]}`;
    notification.innerHTML = `
        <div class="flex items-center gap-2">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'info'}-circle"></i>
            <span>${message}</span>
        </div>
    `;
    
    notification.style.opacity = '0';
    setTimeout(() => {
        notification.style.opacity = '1';
    }, 100);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// ==================== EVENT LISTENERS ====================
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        if (!document.getElementById('addPICModal').classList.contains('hidden')) {
            closeAddPICModal();
        } else if (!document.getElementById('addCompanyModal').classList.contains('hidden')) {
            closeAddCompanyModal();
        } else if (!document.getElementById('visitModal').classList.contains('hidden')) {
            closeVisitModal();
        } else if (document.getElementById('editVisitModal').style.display === 'flex') {
            closeEditVisitModal();
        }
    }
});

document.addEventListener('click', (e) => {
    if (e.target.id === 'visitModal') {
        closeVisitModal();
    }
    if (e.target.id === 'editVisitModal') {
        closeEditVisitModal();
    }
    if (e.target.id === 'addCompanyModal') {
        closeAddCompanyModal();
    }
    if (e.target.id === 'addPICModal') {
        closeAddPICModal();
    }
});

// ==================== INIT ON PAGE LOAD ====================
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSalesVisitModal);
} else {
    initSalesVisitModal();
}

function initSalesVisitModal() {
    console.log('✅ Initializing SalesVisit Modal...');
    
    setTimeout(() => {
        if (typeof AddressCascade === 'undefined') {
            console.error('❌ AddressCascade class not found!');
        } else {
            console.log('✅ AddressCascade class found');
        }
        
        attachEditButtonListeners();
        console.log('✅ Edit button event listener attached');
    }, 100);
}

function attachEditButtonListeners() {
    document.body.addEventListener('click', function(e) {
        const editBtn = e.target.closest('.edit-visit-btn') || 
                       e.target.closest('button[data-visit]');
        
        if (editBtn && editBtn.hasAttribute('data-visit')) {
            e.preventDefault();
            console.log('🖱️ Edit button clicked');
            
            try {
                const encodedData = editBtn.getAttribute('data-visit');
                const visitData = JSON.parse(atob(encodedData));
                console.log('✅ Visit data:', visitData);
                openEditVisitModal(visitData);
            } catch (error) {
                console.error('❌ Error:', error);
                showNotification('Gagal membuka modal edit', 'error');
            }
        }
    });
}