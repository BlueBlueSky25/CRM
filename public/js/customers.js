// ===================================
// CUSTOMER CRUD MANAGEMENT SYSTEM
// ===================================

// Global Variables
let customersData = [];
let selectedCustomers = [];
let addressCascadeInstance = null;
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

// ===================================
// INITIALIZATION
// ===================================
document.addEventListener('DOMContentLoaded', function() {
    loadCustomers();
    setupEventListeners();
});

// Setup all event listeners
function setupEventListeners() {
    // Form submission
    const customerForm = document.getElementById('customerForm');
    if (customerForm) {
        customerForm.addEventListener('submit', handleFormSubmit);
    }
    
    // Import form
    const importForm = document.getElementById('importForm');
    if (importForm) {
        importForm.addEventListener('submit', handleImportSubmit);
    }
    
    // Select all checkbox
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', handleSelectAll);
    }
    
    // Modal button listeners
    const openAddModalBtn = document.getElementById('openAddModalBtn');
    if (openAddModalBtn) {
        openAddModalBtn.addEventListener('click', () => openModal('add'));
    }
    
    const closeModalBtn = document.getElementById('closeModalBtn');
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeModal);
    }
    
    const cancelModalBtn = document.getElementById('cancelModalBtn');
    if (cancelModalBtn) {
        cancelModalBtn.addEventListener('click', closeModal);
    }
    
    const openImportBtn = document.getElementById('openImportBtn');
    if (openImportBtn) {
        openImportBtn.addEventListener('click', openImportModal);
    }
    
    const closeImportModalBtn = document.getElementById('closeImportModalBtn');
    if (closeImportModalBtn) {
        closeImportModalBtn.addEventListener('click', closeImportModal);
    }
    
    const cancelImportBtn = document.getElementById('cancelImportBtn');
    if (cancelImportBtn) {
        cancelImportBtn.addEventListener('click', closeImportModal);
    }
    
    // Customer type radio buttons
    const customerTypeRadios = document.querySelectorAll('input[name="customerType"]');
    customerTypeRadios.forEach(radio => {
        radio.addEventListener('change', toggleCompanyFields);
    });
    
    // Close modal on outside click
    const customerModal = document.getElementById('customerModal');
    const importModal = document.getElementById('importModal');
    
    if (customerModal) {
        customerModal.addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    }
    
    if (importModal) {
        importModal.addEventListener('click', function(e) {
            if (e.target === this) closeImportModal();
        });
    }
}

// ===================================
// LOAD & DISPLAY CUSTOMERS
// ===================================
async function loadCustomers() {
    try {
        const response = await fetch('/customers/list', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });
        
        if (!response.ok) throw new Error('Failed to load customers');
        
        customersData = await response.json();
        displayCustomers(customersData);
        updateKPIs();
    } catch (error) {
        console.error('Error loading customers:', error);
        showNotification('Gagal memuat data customer', 'error');
    }
}

function displayCustomers(customers) {
    const tbody = document.getElementById('customerTableBody');
    
    if (!customers || customers.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="px-3 py-6 text-center text-gray-500">
                    <i class="fas fa-users text-3xl mb-2"></i>
                    <p class="text-xs">Belum ada data customer</p>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = customers.map((customer, index) => `
        <tr class="hover:bg-gray-50 transition-colors border-b">
            <td class="px-3 py-2 whitespace-nowrap">
                <input type="checkbox" 
                    class="customer-checkbox rounded border-gray-300 w-3.5 h-3.5" 
                    value="${customer.id}"
                    onchange="handleCheckboxChange()">
            </td>
            <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900">
                ${index + 1}
            </td>
            <td class="px-3 py-2 whitespace-nowrap">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-blue-600 text-xs"></i>
                    </div>
                    <div class="ml-3">
                        <div class="text-xs font-medium text-gray-900">${customer.name}</div>
                        <div class="text-[10px] text-gray-500">${customer.customer_id || ''}</div>
                    </div>
                </div>
            </td>
            <td class="px-3 py-2 whitespace-nowrap">
                <span class="px-2 py-0.5 text-[10px] font-medium rounded-full ${
                    customer.type === 'Company' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'
                }">
                    ${customer.type}
                </span>
            </td>
            <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-500">
                <i class="fas fa-envelope mr-1 text-[10px]"></i>${customer.email}
            </td>
            <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-500">
                <i class="fas fa-phone mr-1 text-[10px]"></i>${customer.phone}
            </td>
            <td class="px-3 py-2 whitespace-nowrap">
                ${getStatusBadge(customer.status)}
            </td>
            <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-500">
                <i class="fas fa-user-tie mr-1 text-[10px]"></i>${customer.pic}
            </td>
            <td class="px-3 py-2 whitespace-nowrap text-right text-xs font-medium">
                <button onclick="viewCustomer(${customer.id})" 
                    class="text-blue-600 hover:text-blue-900 p-1.5 transition" title="View">
                    <i class="fas fa-eye text-xs"></i>
                </button>
                <button onclick="editCustomer(${customer.id})" 
                    class="text-green-600 hover:text-green-900 p-1.5 transition" title="Edit">
                    <i class="fas fa-edit text-xs"></i>
                </button>
                <button onclick="deleteCustomer(${customer.id})" 
                    class="text-red-600 hover:text-red-900 p-1.5 transition" title="Delete">
                    <i class="fas fa-trash text-xs"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function getStatusBadge(status) {
    const badges = {
        'Lead': 'bg-yellow-100 text-yellow-800',
        'Prospect': 'bg-blue-100 text-blue-800',
        'Active': 'bg-green-100 text-green-800',
        'Inactive': 'bg-gray-100 text-gray-800'
    };
    
    return `<span class="px-2 py-0.5 text-[10px] font-medium rounded-full ${badges[status] || ''}">${status}</span>`;
}

// ===================================
// FILTER FUNCTIONS
// ===================================
function filterCustomers() {
    const search = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const type = document.getElementById('filterType')?.value || '';
    const status = document.getElementById('filterStatus')?.value || '';
    
    const filtered = customersData.filter(customer => {
        const matchSearch = !search || 
            customer.name.toLowerCase().includes(search) ||
            customer.email.toLowerCase().includes(search) ||
            customer.phone.toLowerCase().includes(search) ||
            (customer.customer_id && customer.customer_id.toLowerCase().includes(search));
        
        const matchType = !type || customer.type === type;
        const matchStatus = !status || customer.status === status;
        
        return matchSearch && matchType && matchStatus;
    });
    
    displayCustomers(filtered);
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterType').value = '';
    document.getElementById('filterStatus').value = '';
    displayCustomers(customersData);
}

// ===================================
// MODAL FUNCTIONS
// ===================================
function openModal(mode = 'add', customerId = null) {
    const modal = document.getElementById('customerModal');
    const form = document.getElementById('customerForm');
    const title = document.getElementById('modalTitle');
    const titleIcon = title.previousElementSibling?.querySelector('i');
    
    // Reset form
    form.reset();
    document.getElementById('customerId').value = '';
    document.getElementById('formMethod').value = 'POST';
    
    if (mode === 'add') {
        title.textContent = 'Tambah Customer';
        if (titleIcon) {
            titleIcon.className = 'fas fa-user-plus text-white text-lg';
        }
        document.querySelector('input[name="customerType"][value="Personal"]').checked = true;
        toggleCompanyFields();
        
        // Initialize address cascade for add mode
        initializeAddressCascade();
    } else if (mode === 'edit' && customerId) {
        title.textContent = 'Edit Customer';
        if (titleIcon) {
            titleIcon.className = 'fas fa-user-edit text-white text-lg';
        }
        document.getElementById('formMethod').value = 'PUT';
        
        // Initialize address cascade first, then load data
        initializeAddressCascade();
        loadCustomerData(customerId);
    }
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
}

function closeModal() {
    const modal = document.getElementById('customerModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.classList.remove('overflow-hidden');
    
    // Destroy address cascade instance
    if (addressCascadeInstance) {
        addressCascadeInstance.destroy();
        addressCascadeInstance = null;
    }
}

function toggleCompanyFields() {
    const type = document.querySelector('input[name="customerType"]:checked').value;
    const companyFields = document.getElementById('companyFields');
    const nameLabel = document.getElementById('nameLabel');
    
    if (type === 'Company') {
        companyFields.classList.remove('hidden');
        nameLabel.textContent = 'Nama Perusahaan';
    } else {
        companyFields.classList.add('hidden');
        nameLabel.textContent = 'Nama Lengkap';
    }
}

function initializeAddressCascade() {
    // Destroy existing instance if any
    if (addressCascadeInstance) {
        addressCascadeInstance.destroy();
    }
    
    // Create new instance using the helper function from address-cascade.js
    addressCascadeInstance = initAddressCascade(
        'create-province',
        'create-regency',
        'create-district',
        'create-village'
    );
    
    console.log('Address cascade initialized for modal');
}

// ===================================
// CRUD OPERATIONS
// ===================================
async function handleFormSubmit(e) {
    e.preventDefault();
    
    const customerId = document.getElementById('customerId').value;
    const method = document.getElementById('formMethod').value;
    const form = e.target;
    const formData = new FormData(form);
    
    // Add customer type
    const type = document.querySelector('input[name="customerType"]:checked').value;
    formData.append('type', type);
    
    // Convert FormData to JSON
    const data = {};
    formData.forEach((value, key) => {
        if (key !== 'customerType') {
            data[key] = value;
        }
    });
    
    try {
        const url = customerId ? `/customers/${customerId}` : '/customers';
        const response = await fetch(url, {
            method: method === 'PUT' ? 'PUT' : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            showNotification(result.message, 'success');
            closeModal();
            loadCustomers();
        } else {
            showNotification(result.message || 'Terjadi kesalahan', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal menyimpan data', 'error');
    }
}

async function loadCustomerData(id) {
    try {
        const response = await fetch(`/customers/${id}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });
        
        if (!response.ok) throw new Error('Failed to load customer');
        
        const customer = await response.json();
        
        // Fill form
        document.getElementById('customerId').value = customer.id;
        document.getElementById('customerName').value = customer.name;
        document.getElementById('customerEmail').value = customer.email;
        document.getElementById('customerPhone').value = customer.phone;
        document.getElementById('customerAddress').value = customer.address || '';
        document.getElementById('customerStatus').value = customer.status;
        document.getElementById('customerSource').value = customer.source || 'Website';
        document.getElementById('customerPIC').value = customer.pic;
        document.getElementById('customerNotes').value = customer.notes || '';
        
        // Set customer type
        document.querySelector(`input[name="customerType"][value="${customer.type}"]`).checked = true;
        toggleCompanyFields();
        
        // Fill company fields if company type
        if (customer.type === 'Company') {
            document.getElementById('contactPersonName').value = customer.contact_person_name || '';
            document.getElementById('contactPersonEmail').value = customer.contact_person_email || '';
            document.getElementById('contactPersonPhone').value = customer.contact_person_phone || '';
        }
        
        // Load address cascade data
        if (customer.province_id) {
            document.getElementById('create-province').value = customer.province_id;
            
            // Trigger cascade loading
            if (addressCascadeInstance) {
                await addressCascadeInstance.loadRegencies(customer.province_id);
                
                if (customer.regency_id) {
                    // Wait a bit for regencies to load
                    setTimeout(async () => {
                        document.getElementById('create-regency').value = customer.regency_id;
                        await addressCascadeInstance.loadDistricts(customer.regency_id);
                        
                        if (customer.district_id) {
                            setTimeout(async () => {
                                document.getElementById('create-district').value = customer.district_id;
                                await addressCascadeInstance.loadVillages(customer.district_id);
                                
                                if (customer.village_id) {
                                    setTimeout(() => {
                                        document.getElementById('create-village').value = customer.village_id;
                                    }, 300);
                                }
                            }, 300);
                        }
                    }, 300);
                }
            }
        }
        
    } catch (error) {
        console.error('Error loading customer:', error);
        showNotification('Gagal memuat data customer', 'error');
    }
}

function editCustomer(id) {
    openModal('edit', id);
}

async function deleteCustomer(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus customer ini?')) return;
    
    try {
        const response = await fetch(`/customers/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            showNotification(result.message, 'success');
            loadCustomers();
        } else {
            showNotification(result.message || 'Gagal menghapus customer', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal menghapus customer', 'error');
    }
}

// ===================================
// BULK ACTIONS
// ===================================
function handleSelectAll(e) {
    const checkboxes = document.querySelectorAll('.customer-checkbox');
    checkboxes.forEach(cb => cb.checked = e.target.checked);
    handleCheckboxChange();
}

function handleCheckboxChange() {
    const checkboxes = document.querySelectorAll('.customer-checkbox:checked');
    selectedCustomers = Array.from(checkboxes).map(cb => cb.value);
    
    const bulkActions = document.getElementById('bulkActions');
    if (bulkActions) {
        if (selectedCustomers.length === 0) {
            bulkActions.classList.add('hidden');
        } else {
            bulkActions.classList.remove('hidden');
        }
    }
}

async function bulkDeleteCustomers() {
    if (selectedCustomers.length === 0) {
        showNotification('Pilih customer yang ingin dihapus', 'warning');
        return;
    }
    
    if (!confirm(`Hapus ${selectedCustomers.length} customer yang dipilih?`)) return;
    
    try {
        const response = await fetch('/customers/bulk-delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ids: selectedCustomers })
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            showNotification(result.message, 'success');
            selectedCustomers = [];
            loadCustomers();
        } else {
            showNotification(result.message || 'Gagal menghapus customer', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal menghapus customer', 'error');
    }
}

// ===================================
// IMPORT/EXPORT
// ===================================
function openImportModal() {
    const modal = document.getElementById('importModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
}

function closeImportModal() {
    const modal = document.getElementById('importModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.classList.remove('overflow-hidden');
}

async function handleImportSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    
    try {
        const response = await fetch('/customers/import', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            showNotification(result.message, 'success');
            closeImportModal();
            loadCustomers();
            document.getElementById('importForm').reset();
        } else {
            showNotification(result.message || 'Gagal import data', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal import data', 'error');
    }
}

function exportCustomers() {
    window.location.href = '/customers/export/csv';
}

// ===================================
// VIEW CUSTOMER DETAIL
// ===================================
async function viewCustomer(id) {
    try {
        const response = await fetch(`/customers/${id}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });
        
        if (!response.ok) throw new Error('Failed to load customer');
        
        const customer = await response.json();
        showCustomerDetail(customer);
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal memuat detail customer', 'error');
    }
}

function showCustomerDetail(customer) {
    const typeIcon = customer.type === 'Company' ? 'fa-building' : 'fa-user';
    
    const html = `
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas ${typeIcon} text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">${customer.name}</h3>
                            <p class="text-xs text-gray-500">${customer.customer_id || 'No ID'}</p>
                        </div>
                    </div>
                    <button onclick="this.closest('.fixed').remove()" 
                        class="text-gray-400 hover:text-gray-600 p-2">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <!-- Content -->
                <div class="px-6 py-5 overflow-y-auto max-h-[calc(90vh-140px)] space-y-5">
                    <!-- Status & Type Row -->
                    <div class="flex gap-3">
                        ${getStatusBadge(customer.status)}
                        <span class="px-3 py-1 text-xs font-medium rounded-full ${
                            customer.type === 'Company' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'
                        }">
                            <i class="fas ${typeIcon} mr-1"></i>${customer.type}
                        </span>
                    </div>
                    
                    <!-- Contact Info -->
                    <div>
                        <h4 class="text-xs font-semibold text-gray-500 uppercase mb-3 flex items-center">
                            <i class="fas fa-id-card mr-2"></i>Informasi Kontak ${customer.type === 'Company' ? 'Perusahaan' : ''}
                        </h4>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg">
                                <div class="w-9 h-9 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-envelope text-white text-sm"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs text-gray-600">Email</p>
                                    <p class="font-medium text-gray-900 truncate">${customer.email}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg">
                                <div class="w-9 h-9 bg-green-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-phone text-white text-sm"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs text-gray-600">Telepon</p>
                                    <p class="font-medium text-gray-900">${customer.phone}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Person Info (Only for Company) -->
                    ${customer.type === 'Company' && (customer.contact_person_name || customer.contact_person_email || customer.contact_person_phone) ? `
                    <div>
                        <h4 class="text-xs font-semibold text-gray-500 uppercase mb-3 flex items-center">
                            <i class="fas fa-user-circle mr-2"></i>Informasi Contact Person
                        </h4>
                        <div class="space-y-3">
                            ${customer.contact_person_name ? `
                            <div class="flex items-center gap-3 p-3 bg-indigo-50 rounded-lg">
                                <div class="w-9 h-9 bg-indigo-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs text-gray-600">Nama</p>
                                    <p class="font-medium text-gray-900">${customer.contact_person_name}</p>
                                </div>
                            </div>
                            ` : ''}
                            
                            ${customer.contact_person_email ? `
                            <div class="flex items-center gap-3 p-3 bg-cyan-50 rounded-lg">
                                <div class="w-9 h-9 bg-cyan-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-envelope text-white text-sm"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs text-gray-600">Email</p>
                                    <p class="font-medium text-gray-900 truncate">${customer.contact_person_email}</p>
                                </div>
                            </div>
                            ` : ''}
                            
                            ${customer.contact_person_phone ? `
                            <div class="flex items-center gap-3 p-3 bg-teal-50 rounded-lg">
                                <div class="w-9 h-9 bg-teal-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-phone text-white text-sm"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs text-gray-600">Telepon</p>
                                    <p class="font-medium text-gray-900">${customer.contact_person_phone}</p>
                                </div>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                    ` : ''}
                    
                    <!-- Address -->
                    ${customer.address ? `
                    <div>
                        <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2 flex items-center">
                            <i class="fas fa-map-marker-alt mr-2"></i>Alamat
                        </h4>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-700">${customer.address}</p>
                        </div>
                    </div>
                    ` : ''}
                    
                    <!-- PIC -->
                    <div>
                        <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2 flex items-center">
                            <i class="fas fa-user-tie mr-2"></i>Person In Charge
                        </h4>
                        <div class="flex items-center gap-3 p-3 bg-purple-50 rounded-lg">
                            <div class="w-9 h-9 bg-purple-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <p class="font-medium text-gray-900">${customer.pic}</p>
                        </div>
                    </div>
                    
                    <!-- Notes -->
                    ${customer.notes ? `
                    <div>
                        <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2 flex items-center">
                            <i class="fas fa-sticky-note mr-2"></i>Catatan
                        </h4>
                        <div class="p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-400">
                            <p class="text-sm text-gray-700">${customer.notes}</p>
                        </div>
                    </div>
                    ` : ''}
                </div>
                
                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-2">
                    <button onclick="this.closest('.fixed').remove()" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition">
                        <i class="fas fa-times mr-1"></i>Tutup
                    </button>
                    <button onclick="this.closest('.fixed').remove(); editCustomer(${customer.id})" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', html);
}

// ===================================
// KPI UPDATE
// ===================================
function updateKPIs() {
    const total = customersData.length;
    const active = customersData.filter(c => c.status === 'Active').length;
    const leads = customersData.filter(c => c.status === 'Lead').length;
    const prospects = customersData.filter(c => c.status === 'Prospect').length;
    
    // Update KPI elements if they exist
    const kpiElements = {
        'totalCustomers': total,
        'activeCustomers': active,
        'leadCustomers': leads,
        'prospectCustomers': prospects
    };
    
    Object.entries(kpiElements).forEach(([id, value]) => {
        const element = document.getElementById(id);
        if (element) element.textContent = value;
    });
}

// ===================================
// NOTIFICATION
// ===================================
function showNotification(message, type = 'info') {
    const bgColors = {
        'success': 'bg-green-500',
        'error': 'bg-red-500',
        'warning': 'bg-yellow-500',
        'info': 'bg-blue-500'
    };
    
    const icons = {
        'success': 'fa-check-circle',
        'error': 'fa-exclamation-circle',
        'warning': 'fa-exclamation-triangle',
        'info': 'fa-info-circle'
    };
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${bgColors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-[100] animate-fadeIn`;
    notification.innerHTML = `
        <div class="flex items-center gap-3">
            <i class="fas ${icons[type]}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transition = 'opacity 0.3s';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}