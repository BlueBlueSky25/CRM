// ===================================
// CUSTOMER CRUD MANAGEMENT SYSTEM
// ===================================

// Global Variables
let customersData = [];
let selectedCustomers = [];
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
    
    // Close modal on outside click (untuk modal tanpa Bootstrap)
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
        const response = await fetch('/customers', {
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
                <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-users text-4xl mb-2"></i>
                    <p>Belum ada data customer</p>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = customers.map((customer, index) => `
        <tr class="hover:bg-gray-50 transition-colors border-b">
            <td class="px-6 py-4 whitespace-nowrap">
                <input type="checkbox" 
                    class="customer-checkbox rounded border-gray-300" 
                    value="${customer.id}"
                    onchange="handleCheckboxChange()">
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${index + 1}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">${customer.name}</div>
                        <div class="text-sm text-gray-500">${customer.customer_id || ''}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 text-xs font-medium rounded-full ${
                    customer.type === 'Company' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'
                }">
                    ${customer.type}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <i class="fas fa-envelope mr-2"></i>${customer.email}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <i class="fas fa-phone mr-2"></i>${customer.phone}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                ${getStatusBadge(customer.status)}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <i class="fas fa-user-tie mr-2"></i>${customer.pic}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button onclick="viewCustomer(${customer.id})" 
                    class="text-blue-600 hover:text-blue-900 mr-3 transition" title="View">
                    <i class="fas fa-eye"></i>
                </button>
                <button onclick="editCustomer(${customer.id})" 
                    class="text-green-600 hover:text-green-900 mr-3 transition" title="Edit">
                    <i class="fas fa-edit"></i>
                </button>
                <button onclick="deleteCustomer(${customer.id})" 
                    class="text-red-600 hover:text-red-900 transition" title="Delete">
                    <i class="fas fa-trash"></i>
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
    
    return `<span class="px-2 py-1 text-xs font-medium rounded-full ${badges[status] || ''}">${status}</span>`;
}

// ===================================
// FILTER FUNCTIONS
// ===================================
function filterCustomers() {
    const search = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const type = document.getElementById('filterType')?.value || '';
    const status = document.getElementById('filterStatus')?.value || '';
    const source = document.getElementById('filterSource')?.value || '';
    
    const filtered = customersData.filter(customer => {
        const matchSearch = !search || 
            customer.name.toLowerCase().includes(search) ||
            customer.email.toLowerCase().includes(search) ||
            customer.phone.toLowerCase().includes(search) ||
            (customer.customer_id && customer.customer_id.toLowerCase().includes(search));
        
        const matchType = !type || customer.type === type;
        const matchStatus = !status || customer.status === status;
        const matchSource = !source || customer.source === source;
        
        return matchSearch && matchType && matchStatus && matchSource;
    });
    
    displayCustomers(filtered);
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterType').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterSource').value = '';
    displayCustomers(customersData);
}

// ===================================
// MODAL FUNCTIONS
// ===================================
function openModal(mode = 'add', customerId = null) {
    const modal = document.getElementById('customerModal');
    const form = document.getElementById('customerForm');
    const title = document.getElementById('modalTitle');
    
    // Reset form
    form.reset();
    document.getElementById('customerId').value = '';
    document.getElementById('formMethod').value = 'POST';
    
    if (mode === 'add') {
        title.textContent = 'Tambah Customer';
        document.querySelector('input[name="customerType"][value="Personal"]').checked = true;
        toggleCompanyFields();
    } else if (mode === 'edit' && customerId) {
        title.textContent = 'Edit Customer';
        document.getElementById('formMethod').value = 'PUT';
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
        document.getElementById('customerSource').value = customer.source || '';
        document.getElementById('customerPIC').value = customer.pic;
        document.getElementById('customerNotes').value = customer.notes || '';
        
        // Set customer type
        document.querySelector(`input[name="customerType"][value="${customer.type}"]`).checked = true;
        toggleCompanyFields();
        
        // Fill contact person if company
        if (customer.type === 'Company') {
            document.getElementById('contactPersonName').value = customer.contact_person_name || '';
            document.getElementById('contactPersonEmail').value = customer.contact_person_email || '';
            document.getElementById('contactPersonPhone').value = customer.contact_person_phone || '';
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
    const contactPersonHtml = customer.contact_person_name ? `
        <div class="border-t pt-4 mt-4">
            <h4 class="font-semibold text-gray-900 mb-3">Contact Person</h4>
            <div class="space-y-2">
                <p><span class="text-gray-600">Nama:</span> ${customer.contact_person_name}</p>
                <p><span class="text-gray-600">Email:</span> ${customer.contact_person_email || '-'}</p>
                <p><span class="text-gray-600">Telepon:</span> ${customer.contact_person_phone || '-'}</p>
            </div>
        </div>
    ` : '';
    
    const html = `
        <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-900">Detail Customer</h3>
                    <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="px-6 py-4 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-2xl font-bold text-gray-900">${customer.name}</h4>
                            <p class="text-sm text-gray-500">${customer.customer_id}</p>
                        </div>
                        ${getStatusBadge(customer.status)}
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Tipe</p>
                            <p class="font-medium">${customer.type}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Source</p>
                            <p class="font-medium">${customer.source || '-'}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="font-medium">${customer.email}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Telepon</p>
                            <p class="font-medium">${customer.phone}</p>
                        </div>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600">Alamat</p>
                        <p class="font-medium">${customer.address || '-'}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600">PIC</p>
                        <p class="font-medium">${customer.pic}</p>
                    </div>
                    
                    ${customer.notes ? `
                    <div>
                        <p class="text-sm text-gray-600">Notes</p>
                        <p class="font-medium">${customer.notes}</p>
                    </div>
                    ` : ''}
                    
                    ${contactPersonHtml}
                </div>
                
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                    <button onclick="this.closest('.fixed').remove()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Tutup
                    </button>
                    <button onclick="this.closest('.fixed').remove(); editCustomer(${customer.id})" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Edit
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
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${bgColors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-[100] animate-fadeIn`;
    notification.innerHTML = `
        <div class="flex items-center gap-3">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
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