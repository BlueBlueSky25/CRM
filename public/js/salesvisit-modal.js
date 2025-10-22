// ==================== ADD MODAL ====================
function openVisitModal() {
    const modal = document.getElementById('visitModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        const firstInput = modal.querySelector('select[name="sales_id"]');
        if (firstInput) firstInput.focus();
    }, 300);
}

function closeVisitModal() {
    const modal = document.getElementById('visitModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    const form = modal.querySelector('form');
    if (form) form.reset();
    
    document.getElementById('create-regency').innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
    document.getElementById('create-district').innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
    document.getElementById('create-village').innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';
}

// ==================== EDIT MODAL ====================
let editVisitCascade = null;
let currentEditData = null;

function openEditVisitModal(visitData) {
    console.log('Opening Edit Visit Modal:', visitData);

    // Show modal
    const modal = document.getElementById('editVisitModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Set form action
    const form = document.getElementById('editVisitForm');
    form.action = `/salesvisit/${visitData.id}`;

    // Set basic fields
    document.getElementById('editVisitId').value = visitData.id || '';
    document.getElementById('editCustomerName').value = visitData.customerName || '';
    document.getElementById('editCompany').value = visitData.company || '';
    document.getElementById('editAddress').value = visitData.address || '';
    document.getElementById('editVisitDate').value = visitData.visitDate || '';
    document.getElementById('editPurpose').value = visitData.purpose || '';
    document.getElementById('editFollowUp').checked = visitData.followUp == 1;

    // Store current data
    currentEditData = {
        salesId: visitData.salesId,
        provinceId: visitData.provinceId,
        regencyId: visitData.regencyId,
        districtId: visitData.districtId,
        villageId: visitData.villageId
    };

    // Load data via AJAX
    loadEditVisitData(visitData.id);
}

function loadEditVisitData(visitId) {
    const salesSelect = document.getElementById('editSalesId');
    const provinceSelect = document.getElementById('edit-province');
    
    salesSelect.innerHTML = '<option value="">Loading data sales...</option>';
    provinceSelect.innerHTML = '<option value="">Loading data provinsi...</option>';

    fetch(`/salesvisit/${visitId}/edit`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Edit data loaded:', data);
            if (data.success) {
                populateEditForm(data);
            } else {
                throw new Error('Failed to load edit data');
            }
        })
        .catch(error => {
            console.error('Error loading edit data:', error);
            salesSelect.innerHTML = '<option value="">-- Pilih Sales --</option>';
            provinceSelect.innerHTML = '<option value="">-- Pilih Provinsi --</option>';
            showNotification('Error memuat data untuk edit: ' + error.message, 'error');
        });
}

function populateEditForm(data) {
    // Populate sales dropdown
    const salesSelect = document.getElementById('editSalesId');
    salesSelect.innerHTML = '<option value="">-- Pilih Sales --</option>';
    
    if (data.salesUsers && Array.isArray(data.salesUsers)) {
        console.log('Sales users data:', data.salesUsers);
        
        data.salesUsers.forEach(sales => {
            const option = document.createElement('option');
            option.value = sales.user_id;
            option.textContent = `${sales.username} (${sales.email})`;
            salesSelect.appendChild(option);
        });
        
        if (currentEditData.salesId) {
            salesSelect.value = currentEditData.salesId;
            console.log('Set sales selection to:', currentEditData.salesId);
        }
    } else {
        console.warn('No sales users data found');
        salesSelect.innerHTML = '<option value="">Data sales tidak tersedia</option>';
    }

    // Populate provinces dropdown
    const provinceSelect = document.getElementById('edit-province');
    provinceSelect.innerHTML = '<option value="">-- Pilih Provinsi --</option>';
    
    if (data.provinces && Array.isArray(data.provinces)) {
        console.log('Provinces data:', data.provinces);
        
        data.provinces.forEach(province => {
            const option = document.createElement('option');
            option.value = province.id;
            option.textContent = province.name;
            provinceSelect.appendChild(option);
        });
        
        if (currentEditData.provinceId) {
            provinceSelect.value = currentEditData.provinceId;
            console.log('Set province selection to:', currentEditData.provinceId);
            
            setTimeout(() => {
                initEditVisitCascade();
            }, 100);
        }
    } else {
        console.warn('No provinces data found');
        provinceSelect.innerHTML = '<option value="">Data provinsi tidak tersedia</option>';
    }
}

function initEditVisitCascade() {
    if (editVisitCascade) {
        editVisitCascade.destroy();
    }

    editVisitCascade = new AddressCascade({
        provinceId: 'edit-province',
        regencyId: 'edit-regency',
        districtId: 'edit-district',
        villageId: 'edit-village'
    });

    if (currentEditData.provinceId) {
        const provinceSelect = document.getElementById('edit-province');
        provinceSelect.value = currentEditData.provinceId;
        
        const changeEvent = new Event('change');
        provinceSelect.dispatchEvent(changeEvent);
        
        setTimeout(() => {
            if (currentEditData.regencyId) {
                const regencySelect = document.getElementById('edit-regency');
                regencySelect.value = currentEditData.regencyId;
                regencySelect.dispatchEvent(new Event('change'));
                
                setTimeout(() => {
                    if (currentEditData.districtId) {
                        const districtSelect = document.getElementById('edit-district');
                        districtSelect.value = currentEditData.districtId;
                        districtSelect.dispatchEvent(new Event('change'));
                        
                        setTimeout(() => {
                            if (currentEditData.villageId) {
                                const villageSelect = document.getElementById('edit-village');
                                villageSelect.value = currentEditData.villageId;
                            }
                        }, 500);
                    }
                }, 500);
            }
        }, 500);
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

    document.getElementById('editSalesId').innerHTML = '<option value="">-- Pilih Sales --</option>';
    document.getElementById('edit-province').innerHTML = '<option value="">-- Pilih Provinsi --</option>';
    document.getElementById('edit-regency').innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
    document.getElementById('edit-district').innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
    document.getElementById('edit-village').innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';
}

// ==================== DELETE ====================
function deleteVisit(id, deleteUrl, csrfToken) {
    if (confirm('Apakah Anda yakin ingin menghapus data kunjungan ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = deleteUrl;

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';

        form.appendChild(csrfInput);
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    }
}

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