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
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    if (createVisitCascade) {
        createVisitCascade.destroy();
        createVisitCascade = null;
    }
    
    const form = modal.querySelector('form');
    if (form) form.reset();
    
    document.getElementById('create-regency').innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
    document.getElementById('create-district').innerHTML = '<option value="">Pilih Kecamatan</option>';
    document.getElementById('create-village').innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
}

// ==================== EDIT MODAL ====================
let editVisitCascade = null;
let currentEditData = null;

function openEditVisitModal(visitData) {
    console.log('🚀 Opening Edit Visit Modal with data:', visitData);

    if (!visitData || !visitData.id) {
        console.error('❌ Invalid visit data:', visitData);
        showNotification('Data kunjungan tidak valid', 'error');
        return;
    }

    try {
        // Parse IDs - HANYA untuk visit_id dan sales_id
        // Province/Regency/District/Village tetap STRING karena DB nya VARCHAR
        visitData.id = parseInt(visitData.id);
        visitData.salesId = parseInt(visitData.salesId);
        visitData.followUp = parseInt(visitData.followUp);
        
        // Keep address IDs as string (karena VARCHAR di database)
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
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        const form = document.getElementById('editVisitForm');
        if (form) {
            form.action = `/salesvisit/${visitData.id}`;
            console.log('✅ Form action set to:', form.action);
        }

        // Fill form fields
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

        // Store edit data globally
        currentEditData = {
            salesId: visitData.salesId || '',
            provinceId: visitData.provinceId || '',
            regencyId: visitData.regencyId || null,
            districtId: visitData.districtId || null,
            villageId: visitData.villageId || null
        };

        console.log('✅ Current edit data stored:', currentEditData);
        
        // Load dropdowns
        loadEditVisitData(visitData.id);
        
    } catch (error) {
        console.error('❌ Error opening edit modal:', error);
        showNotification('Gagal membuka modal edit: ' + error.message, 'error');
    }
}

function loadEditVisitData(visitId) {
    console.log('📥 Loading edit data for visit:', visitId);
    
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

    // Fetch sales users dan provinces
    Promise.all([
        fetch('/salesvisit/get-sales')
            .then(r => {
                console.log('📡 Sales fetch status:', r.status);
                if (!r.ok) throw new Error('Failed to fetch sales users');
                return r.json();
            })
            .then(data => {
                console.log('🔍 Raw sales response:', data);
                return data;
            }),
        fetch('/salesvisit/get-provinces')
            .then(r => {
                console.log('📡 Provinces fetch status:', r.status);
                if (!r.ok) throw new Error('Failed to fetch provinces');
                return r.json();
            })
            .then(data => {
                console.log('🔍 Raw provinces response:', data);
                return data;
            })
    ]).then(([salesData, provincesResponse]) => {
        console.log('✅ Processing sales data...');

        // Populate Sales Dropdown - handle multiple response formats
        salesSelect.innerHTML = '<option value="">Pilih Sales</option>';
        
        // Try different possible data structures
        let salesList = null;
        if (Array.isArray(salesData)) {
            salesList = salesData;
        } else if (salesData.users && Array.isArray(salesData.users)) {
            salesList = salesData.users;
        } else if (salesData.data && Array.isArray(salesData.data)) {
            salesList = salesData.data;
        }
        
        console.log('📋 Sales list to populate:', salesList);
        
        if (salesList && salesList.length > 0) {
            let foundSelected = false;
            salesList.forEach(sales => {
                const option = document.createElement('option');
                option.value = sales.user_id;
                option.textContent = `${sales.username} - ${sales.email}`;
                if (sales.user_id == currentEditData.salesId) {
                    option.selected = true;
                    foundSelected = true;
                    console.log('✅ Selected sales:', sales.username);
                }
                salesSelect.appendChild(option);
            });
            
            if (!foundSelected && currentEditData.salesId) {
                console.warn('⚠️ Sales ID', currentEditData.salesId, 'not found in list');
            }
            
            console.log(`✅ Loaded ${salesList.length} sales users`);
        } else {
            console.error('❌ No sales users found in response!');
            salesSelect.innerHTML = '<option value="">Tidak ada sales tersedia</option>';
        }

        salesSelect.disabled = false;

        // Populate Provinces Dropdown - handle multiple response formats
        console.log('✅ Processing provinces data...');
        provinceSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
        
        // Try different possible data structures
        let provincesList = null;
        if (Array.isArray(provincesResponse)) {
            provincesList = provincesResponse;
        } else if (provincesResponse.provinces && Array.isArray(provincesResponse.provinces)) {
            provincesList = provincesResponse.provinces;
        } else if (provincesResponse.data && Array.isArray(provincesResponse.data)) {
            provincesList = provincesResponse.data;
        }
        
        console.log('📋 Provinces list to populate:', provincesList);
        
        if (provincesList && provincesList.length > 0) {
            let foundSelected = false;
            provincesList.forEach(province => {
                const option = document.createElement('option');
                option.value = province.id;
                option.textContent = province.name;
                // Compare as string karena VARCHAR
                if (String(province.id) == String(currentEditData.provinceId)) {
                    option.selected = true;
                    foundSelected = true;
                    console.log('✅ Selected province:', province.name, '(ID:', province.id, ')');
                }
                provinceSelect.appendChild(option);
            });
            
            if (!foundSelected && currentEditData.provinceId) {
                console.warn('⚠️ Province ID', currentEditData.provinceId, 'not found in list');
            }
            
            console.log(`✅ Loaded ${provincesList.length} provinces`);
        } else {
            console.error('❌ No provinces found in response!');
            provinceSelect.innerHTML = '<option value="">Tidak ada provinsi tersedia</option>';
        }

        provinceSelect.disabled = false;
        
        // Initialize cascade setelah dropdown ter-populate
        console.log('🔄 Initializing cascade...');
        
        // Delay sedikit untuk memastikan DOM sudah siap
        setTimeout(() => {
            initEditVisitCascade();
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

    // Load cascade data jika ada provinceId
    if (currentEditData.provinceId) {
        console.log('🔄 Loading regencies for province:', currentEditData.provinceId);
        
        const provinceSelect = document.getElementById('edit-province');
        // Set value as string karena VARCHAR
        provinceSelect.value = String(currentEditData.provinceId);
        
        console.log('Province select value after set:', provinceSelect.value);
        
        // Trigger change untuk load regencies
        const changeEvent = new Event('change', { bubbles: true });
        provinceSelect.dispatchEvent(changeEvent);
        
        // Load regency jika ada
        if (currentEditData.regencyId) {
            console.log('⏳ Waiting for regencies to load...');
            
            let regencyWaitCount = 0;
            const maxRegencyWait = 50; // 50 x 200ms = 10 detik
            
            const waitForRegencies = setInterval(() => {
                const regencySelect = document.getElementById('edit-regency');
                regencyWaitCount++;
                
                console.log(`⏳ Regency wait count: ${regencyWaitCount}, options: ${regencySelect.options.length}`);
                
                if (regencySelect.options.length > 1) { // Ada options selain placeholder
                    clearInterval(waitForRegencies);
                    console.log('✅ Regencies loaded, setting value:', currentEditData.regencyId);
                    
                    // Set value (compare as string karena VARCHAR)
                    regencySelect.value = String(currentEditData.regencyId);
                    
                    // Cek apakah value berhasil di-set
                    if (regencySelect.value == currentEditData.regencyId) {
                        console.log('✅ Regency value set successfully:', regencySelect.value);
                        regencySelect.dispatchEvent(new Event('change', { bubbles: true }));
                    } else {
                        console.warn('⚠️ Regency value not found. Looking for:', currentEditData.regencyId);
                        console.warn('⚠️ Available regencies:', 
                            Array.from(regencySelect.options).map(o => ({value: o.value, text: o.text})));
                    }
                    
                    // Load district jika ada
                    if (currentEditData.districtId) {
                        console.log('⏳ Waiting for districts to load...');
                        
                        let districtWaitCount = 0;
                        const maxDistrictWait = 50; // 50 x 200ms = 10 detik
                        
                        const waitForDistricts = setInterval(() => {
                            const districtSelect = document.getElementById('edit-district');
                            districtWaitCount++;
                            
                            console.log(`⏳ District wait count: ${districtWaitCount}, options: ${districtSelect.options.length}`);
                            
                            if (districtSelect.options.length > 1) {
                                clearInterval(waitForDistricts);
                                console.log('✅ Districts loaded, setting value:', currentEditData.districtId);
                                
                                // Set value (compare as string karena VARCHAR)
                                districtSelect.value = String(currentEditData.districtId);
                                
                                // Cek apakah value berhasil di-set
                                if (districtSelect.value == currentEditData.districtId) {
                                    console.log('✅ District value set successfully:', districtSelect.value);
                                    districtSelect.dispatchEvent(new Event('change', { bubbles: true }));
                                    
                                    // Load village jika ada
                                    if (currentEditData.villageId) {
                                        console.log('⏳ Waiting for villages to load...');
                                        
                                        let villageWaitCount = 0;
                                        const maxVillageWait = 50;
                                        
                                        const waitForVillages = setInterval(() => {
                                            const villageSelect = document.getElementById('edit-village');
                                            villageWaitCount++;
                                            
                                            console.log(`⏳ Village wait count: ${villageWaitCount}, options: ${villageSelect.options.length}`);
                                            
                                            if (villageSelect.options.length > 1) {
                                                clearInterval(waitForVillages);
                                                console.log('✅ Villages loaded, setting value:', currentEditData.villageId);
                                                
                                                // Set value (compare as string karena VARCHAR)
                                                villageSelect.value = String(currentEditData.villageId);
                                                
                                                if (villageSelect.value == currentEditData.villageId) {
                                                    console.log('✅ Village value set successfully:', villageSelect.value);
                                                } else {
                                                    console.warn('⚠️ Village value not found in options');
                                                }
                                            }
                                            
                                            if (villageWaitCount >= maxVillageWait) {
                                                clearInterval(waitForVillages);
                                                console.warn('⚠️ Timeout waiting for villages - no data received');
                                            }
                                        }, 200);
                                    }
                                } else {
                                    console.warn('⚠️ District value not found in options');
                                }
                            }
                            
                            if (districtWaitCount >= maxDistrictWait) {
                                clearInterval(waitForDistricts);
                                console.warn('⚠️ Timeout waiting for districts - no data received');
                                console.warn('⚠️ Possible issue: regency might not have districts or API error');
                            }
                        }, 200);
                    }
                }
            }, 200); // Check every 200ms
            
            // Timeout setelah 10 detik
            setTimeout(() => {
                clearInterval(waitForRegencies);
                console.warn('⚠️ Timeout waiting for regencies');
            }, 10000);
        }
    } else {
        console.log('ℹ️ No province ID to load cascade');
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

    const salesSelect = document.getElementById('editSalesId');
    salesSelect.disabled = false;
    salesSelect.classList.remove('bg-gray-100', 'cursor-not-allowed');
    salesSelect.innerHTML = '<option value="">Pilih Sales</option>';

    document.getElementById('edit-province').innerHTML = '<option value="">Pilih Provinsi</option>';
    document.getElementById('edit-regency').innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
    document.getElementById('edit-district').innerHTML = '<option value="">Pilih Kecamatan</option>';
    document.getElementById('edit-village').innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
}

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
                
                // Full reload setelah 1 detik
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
    
    // Show notification
    notification.style.opacity = '0';
    setTimeout(() => {
        notification.style.opacity = '1';
    }, 100);
    
    // Hide after 3 seconds
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
    console.log('✅ SalesVisit Modal JS Loaded');
    
    if (typeof AddressCascade === 'undefined') {
        console.error('❌ AddressCascade class not found! Make sure address-cascade.js is loaded.');
    } else {
        console.log('✅ AddressCascade class found');
    }
});