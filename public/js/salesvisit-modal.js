// ==================== ADD MODAL ====================
function openVisitModal() {
    const modal = document.getElementById('visitModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeVisitModal() {
    const modal = document.getElementById('visitModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    modal.querySelector('form').reset();
}

// ==================== EDIT MODAL ====================
let editVisitCascade = null;
let currentEditData = null;

function openEditVisitModal(id, salesId, customerName, company, provinceId, visitDate, purpose, followUp) {
    console.log('Opening Edit Visit Modal:', { id, salesId, customerName, company, provinceId, visitDate, purpose, followUp });

    // Show loading state
    const modal = document.getElementById('editVisitModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Set form action
    const form = document.getElementById('editVisitForm');
    form.action = `/salesvisit/${id}`;

    // Set basic fields
    document.getElementById('editVisitId').value = id ?? '';
    document.getElementById('editCustomerName').value = customerName ?? '';
    document.getElementById('editCompany').value = company ?? '';
    document.getElementById('editVisitDate').value = visitDate ?? '';
    document.getElementById('editPurpose').value = purpose ?? '';
    document.getElementById('editFollowUp').checked = followUp == 1;

    // Store current data untuk nanti
    currentEditData = {
        salesId: salesId,
        provinceId: provinceId
    };

    // Load data via AJAX
    loadEditVisitData(id);
}

function loadEditVisitData(visitId) {
    // Show loading state
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
            console.log('Edit data loaded:', data); // Debug log
            if (data.success) {
                populateEditForm(data);
            } else {
                throw new Error('Failed to load edit data');
            }
        })
        .catch(error => {
            console.error('Error loading edit data:', error);
            // Reset to empty options
            salesSelect.innerHTML = '<option value="">-- Pilih Sales --</option>';
            provinceSelect.innerHTML = '<option value="">-- Pilih Provinsi --</option>';
            alert('Error memuat data untuk edit: ' + error.message);
        });
}

function populateEditForm(data) {
    // Populate sales dropdown
    const salesSelect = document.getElementById('editSalesId');
    salesSelect.innerHTML = '<option value="">-- Pilih Sales --</option>';
    
    if (data.salesUsers && Array.isArray(data.salesUsers)) {
        console.log('Sales users data:', data.salesUsers); // Debug log
        
        data.salesUsers.forEach(sales => {
            const option = document.createElement('option');
            option.value = sales.user_id;
            option.textContent = `${sales.username} (${sales.email})`;
            salesSelect.appendChild(option);
        });
        
        // Set selected sales setelah semua options ditambahkan
        if (currentEditData.salesId) {
            salesSelect.value = currentEditData.salesId;
            console.log('Set sales selection to:', currentEditData.salesId); // Debug log
        }
    } else {
        console.warn('No sales users data found');
        salesSelect.innerHTML = '<option value="">Data sales tidak tersedia</option>';
    }

    // Populate provinces dropdown
    const provinceSelect = document.getElementById('edit-province');
    provinceSelect.innerHTML = '<option value="">-- Pilih Provinsi --</option>';
    
    if (data.provinces && Array.isArray(data.provinces)) {
        console.log('Provinces data:', data.provinces); // Debug log
        
        data.provinces.forEach(province => {
            const option = document.createElement('option');
            option.value = province.id;
            option.textContent = province.name;
            provinceSelect.appendChild(option);
        });
        
        // Set selected province setelah semua options ditambahkan
        if (currentEditData.provinceId) {
            provinceSelect.value = currentEditData.provinceId;
            console.log('Set province selection to:', currentEditData.provinceId); // Debug log
            
            // Trigger cascade untuk wilayah
            setTimeout(() => {
                initEditVisitCascade(currentEditData.provinceId);
            }, 100);
        }
    } else {
        console.warn('No provinces data found');
        provinceSelect.innerHTML = '<option value="">Data provinsi tidak tersedia</option>';
    }
}

function initEditVisitCascade(provinceId) {
    if (editVisitCascade) editVisitCascade.destroy();

    editVisitCascade = new AddressCascade({
        provinceId: 'edit-province',
        regencyId: 'edit-regency',
        districtId: 'edit-district',
        villageId: 'edit-village'
    });

    if (provinceId) {
        // Trigger change event untuk load data cascade
        const provinceSelect = document.getElementById('edit-province');
        provinceSelect.value = provinceId;
        provinceSelect.dispatchEvent(new Event('change'));
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

    // Reset form
    const form = document.getElementById('editVisitForm');
    if (form) form.reset();

    // Reset dropdowns ke state awal
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

// ==================== CLOSE MODAL (ESC + OUTSIDE) ====================
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        if (!document.getElementById('visitModal').classList.contains('hidden')) closeVisitModal();
        if (!document.getElementById('editVisitModal').classList.contains('hidden')) closeEditVisitModal();
    }
});

document.addEventListener('click', (e) => {
    if (e.target.id === 'visitModal') closeVisitModal();
    if (e.target.id === 'editVisitModal') closeEditVisitModal();
});
