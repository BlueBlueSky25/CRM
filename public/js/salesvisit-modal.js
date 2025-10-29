// ==================== ADD MODAL ====================
let createVisitCascade = null;

function openVisitModal() {
    const modal = document.getElementById('visitModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Initialize address cascade untuk CREATE modal
    initCreateVisitCascade();
    
    setTimeout(() => {
        const firstInput = modal.querySelector('select[name="sales_id"]');
        if (firstInput && !firstInput.disabled) firstInput.focus();
    }, 300);
}

function initCreateVisitCascade() {
    // Destroy existing instance if any
    if (createVisitCascade) {
        createVisitCascade.destroy();
    }

    // Initialize new cascade instance untuk CREATE modal
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
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    // Destroy cascade instance
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
}

// ==================== EDIT MODAL ====================
let editVisitCascade = null;
let currentEditData = null;

function openEditVisitModal(visitData) {
    console.log('Opening Edit Visit Modal:', visitData);

    // Validasi data
    if (!visitData || !visitData.id) {
        console.error('Invalid visit data:', visitData);
        showNotification('Data kunjungan tidak valid', 'error');
        return;
    }

    try {
        // Convert string numbers to actual numbers
        visitData.id = parseInt(visitData.id);
        visitData.salesId = parseInt(visitData.salesId);
        visitData.provinceId = parseInt(visitData.provinceId);
        visitData.regencyId = visitData.regencyId ? parseInt(visitData.regencyId) : null;
        visitData.districtId = visitData.districtId ? parseInt(visitData.districtId) : null;
        visitData.villageId = visitData.villageId ? parseInt(visitData.villageId) : null;
        visitData.followUp = parseInt(visitData.followUp);

        // Show modal
        const modal = document.getElementById('editVisitModal');
        if (!modal) {
            console.error('Edit modal element not found');
            return;
        }
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Set form action
        const form = document.getElementById('editVisitForm');
        if (form) {
            form.action = `/salesvisit/${visitData.id}`;
        }

        // Set basic fields dengan null checking
        document.getElementById('editVisitId').value = visitData.id || '';
        document.getElementById('editCustomerName').value = visitData.customerName || '';
        document.getElementById('editCompany').value = visitData.company || '';
        document.getElementById('editAddress').value = visitData.address || '';
        document.getElementById('editVisitDate').value = visitData.visitDate || '';
        document.getElementById('editPurpose').value = visitData.purpose || '';
        
        // Set follow up radio buttons
        if (visitData.followUp == 1) {
            document.getElementById('editFollowUpYes').checked = true;
        } else {
            document.getElementById('editFollowUpNo').checked = true;
        }

        // Store current data dengan default values
        currentEditData = {
            salesId: visitData.salesId || '',
            provinceId: visitData.provinceId || '',
            regencyId: visitData.regencyId || null,
            districtId: visitData.districtId || null,
            villageId: visitData.villageId || null
        };

        console.log('Current edit data:', currentEditData);

        // Load data via AJAX untuk mendapatkan sales users dan provinces
        loadEditVisitData(visitData.id);
    } catch (error) {
        console.error('Error opening edit modal:', error);
        showNotification('Gagal membuka modal edit: ' + error.message, 'error');
    }
}

function loadEditVisitData(visitId) {
    console.log('Loading edit data for visit:', visitId);
    
    // Show loading state
    const salesSelect = document.getElementById('editSalesId');
    const provinceSelect = document.getElementById('edit-province');
    
    salesSelect.innerHTML = '<option value="">Loading...</option>';
    salesSelect.disabled = true;
    provinceSelect.innerHTML = '<option value="">Loading...</option>';
    provinceSelect.disabled = true;

    // Load sales users dan provinces via AJAX
    Promise.all([
        fetch('/users/search?role=sales').then(r => {
            if (!r.ok) throw new Error('Failed to fetch sales users');
            return r.json();
        }),
        fetch('/salesvisit/get-provinces').then(r => {
            if (!r.ok) throw new Error('Failed to fetch provinces');
            return r.json();
        })
    ]).then(([salesData, provincesResponse]) => {
        console.log('Sales data:', salesData);
        console.log('Provinces response:', provincesResponse);

        // Populate sales dropdown
        salesSelect.innerHTML = '<option value="">Pilih Sales</option>';
        
        if (salesData && salesData.users) {
            salesData.users.forEach(sales => {
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

        // Populate provinces dropdown
        provinceSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
        
        // Handle provinces dari response
        const provinces = provincesResponse.provinces || provincesResponse;
        
        if (Array.isArray(provinces)) {
            provinces.forEach(province => {
                const option = document.createElement('option');
                option.value = province.id;
                option.textContent = province.name;
                if (province.id == currentEditData.provinceId) {
                    option.selected = true;
                }
                provinceSelect.appendChild(option);
            });
        }

        provinceSelect.disabled = false;
        
        // Initialize cascade SETELAH data loaded
        initEditVisitCascade();
        
    }).catch(error => {
        console.error('Error loading edit data:', error);
        showNotification('Gagal memuat data edit: ' + error.message, 'error');
        
        // Enable select anyway
        salesSelect.disabled = false;
        salesSelect.innerHTML = '<option value="">Error loading data</option>';
        provinceSelect.disabled = false;
        provinceSelect.innerHTML = '<option value="">Error loading data</option>';
    });
}

function initEditVisitCascade() {
    console.log('Initializing edit cascade with data:', currentEditData);
    
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

    // Set province value dan trigger cascade loading
    if (currentEditData.provinceId) {
        const provinceSelect = document.getElementById('edit-province');
        provinceSelect.value = currentEditData.provinceId;
        
        // Trigger change untuk load regencies
        const changeEvent = new Event('change');
        provinceSelect.dispatchEvent(changeEvent);
        
        // Wait untuk regencies load, lalu set regency value
        if (currentEditData.regencyId) {
            setTimeout(() => {
                const regencySelect = document.getElementById('edit-regency');
                // Cek apakah regency sudah ada di dropdown
                const checkRegencyLoaded = setInterval(() => {
                    if (regencySelect.options.length > 1) {
                        clearInterval(checkRegencyLoaded);
                        regencySelect.value = currentEditData.regencyId;
                        regencySelect.dispatchEvent(new Event('change'));
                        
                        // Wait untuk districts load
                        if (currentEditData.districtId) {
                            setTimeout(() => {
                                const districtSelect = document.getElementById('edit-district');
                                const checkDistrictLoaded = setInterval(() => {
                                    if (districtSelect.options.length > 1) {
                                        clearInterval(checkDistrictLoaded);
                                        districtSelect.value = currentEditData.districtId;
                                        districtSelect.dispatchEvent(new Event('change'));
                                        
                                        // Wait untuk villages load
                                        if (currentEditData.villageId) {
                                            setTimeout(() => {
                                                const villageSelect = document.getElementById('edit-village');
                                                const checkVillageLoaded = setInterval(() => {
                                                    if (villageSelect.options.length > 1) {
                                                        clearInterval(checkVillageLoaded);
                                                        villageSelect.value = currentEditData.villageId;
                                                    }
                                                }, 100);
                                                
                                                // Safety timeout
                                                setTimeout(() => clearInterval(checkVillageLoaded), 5000);
                                            }, 300);
                                        }
                                    }
                                }, 100);
                                
                                // Safety timeout
                                setTimeout(() => clearInterval(checkDistrictLoaded), 5000);
                            }, 300);
                        }
                    }
                }, 100);
                
                // Safety timeout
                setTimeout(() => clearInterval(checkRegencyLoaded), 5000);
            }, 300);
        }
    }
}

function closeEditVisitModal() {
    const modal = document.getElementById('editVisitModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';

    if (editVisitCascade) {
        editVisitCascade.destroy();
        editVisitCascade = null;
    }

    currentEditData = null;

    const form = document.getElementById('editVisitForm');
    if (form) form.reset();

    // Reset dropdowns
    const salesSelect = document.getElementById('editSalesId');
    salesSelect.disabled = false;
    salesSelect.classList.remove('bg-gray-100', 'cursor-not-allowed');
    salesSelect.innerHTML = '<option value="">Pilih Sales</option>';

    document.getElementById('edit-province').innerHTML = '<option value="">Pilih Provinsi</option>';
    document.getElementById('edit-regency').innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
    document.getElementById('edit-district').innerHTML = '<option value="">Pilih Kecamatan</option>';
    document.getElementById('edit-village').innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
}


function deleteVisit(visitId, deleteRoute, csrfToken) {
    console.log('deleteVisit called:', { visitId, deleteRoute, csrfToken });

    if (confirm('Apakah Anda yakin ingin menghapus data kunjungan ini?')) {
        // Gunakan route yang benar dengan ID
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
                
                // ✅ FULL PAGE RELOAD untuk memastikan data fresh
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                throw new Error(data.message || 'Gagal menghapus data');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Gagal menghapus data: ' + error.message, 'error');
        });
    }
}

// Function untuk show notification
function showNotification(message, type = 'info') {
    // Buat element notification jika belum ada
    let notification = document.getElementById('global-notification');
    if (!notification) {
        notification = document.createElement('div');
        notification.id = 'global-notification';
        notification.className = 'fixed top-4 right-4 z-[1000] p-4 rounded-lg shadow-lg text-white transform transition-all duration-300';
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
    
    // Show notification
    setTimeout(() => {
        notification.classList.remove('opacity-0');
    }, 100);
    
    // Auto hide tidak perlu karena page akan reload
}

document.addEventListener('DOMContentLoaded', () => {
    console.log('SalesVisit page loaded');
});
// ==================== NOTIFICATION SYSTEM ====================
function showNotification(message, type = 'info') {
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
    
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    setTimeout(() => {
        notification.classList.add('translate-x-full');
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
        if (!document.getElementById('visitModal').classList.contains('hidden')) {
            closeVisitModal();
        }
        if (!document.getElementById('editVisitModal').classList.contains('hidden')) {
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
});

// ==================== INIT ON PAGE LOAD ====================
document.addEventListener('DOMContentLoaded', () => {
    console.log('SalesVisit Modal JS Loaded');
    
    // Check if AddressCascade is available
    if (typeof AddressCascade === 'undefined') {
        console.error('AddressCascade class not found! Make sure address-cascade.js is loaded.');
    }
});