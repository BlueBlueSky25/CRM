// Address Cascade System - Reusable untuk semua form

class AddressCascade {
    constructor(config) {
        this.provinceId = config.provinceId;
        this.regencyId = config.regencyId;
        this.districtId = config.districtId;
        this.villageId = config.villageId;
        
        this.province = null;
        this.regency = null;
        this.district = null;
        this.village = null;
        
        // Store bound event handlers for cleanup
        this.handlers = {
            province: null,
            regency: null,
            district: null
        };
        
        this.init();
    }
    
    // Initialize cascade
    init() {
        console.log('Initializing address cascade...');
        
        this.province = document.getElementById(this.provinceId);
        this.regency = document.getElementById(this.regencyId);
        this.district = document.getElementById(this.districtId);
        this.village = document.getElementById(this.villageId);
        
        if (!this.checkElements()) {
            console.error('One or more dropdown elements not found');
            return;
        }
        
        console.log('All dropdown elements found');
        this.attachEventListeners();
    }
    
    // Check if all elements exist
    checkElements() {
        const elements = {
            province: this.province,
            regency: this.regency,
            district: this.district,
            village: this.village
        };
        
        console.log('Elements check:', elements);
        
        let allFound = true;
        Object.keys(elements).forEach(key => {
            if (!elements[key]) {
                console.error(`Element ${key} not found!`);
                allFound = false;
            } else {
                console.log(`Element ${key} found`);
            }
        });
        
        return allFound;
    }
    
    // Attach event listeners
    attachEventListeners() {
        // Province change
        this.handlers.province = () => {
            const provinceId = this.province.value;
            console.log('Province selected:', provinceId);
            this.loadRegencies(provinceId);
        };
        this.province.addEventListener('change', this.handlers.province);
        
        // Regency change
        this.handlers.regency = () => {
            const regencyId = this.regency.value;
            console.log('Regency selected:', regencyId);
            this.loadDistricts(regencyId);
        };
        this.regency.addEventListener('change', this.handlers.regency);
        
        // District change
        this.handlers.district = () => {
            const districtId = this.district.value;
            console.log('District selected:', districtId);
            this.loadVillages(districtId);
        };
        this.district.addEventListener('change', this.handlers.district);
        
        console.log('Event listeners attached successfully');
    }
    
    // Destroy instance and cleanup
    destroy() {
        console.log('Destroying cascade instance...');
        
        if (this.province && this.handlers.province) {
            this.province.removeEventListener('change', this.handlers.province);
        }
        if (this.regency && this.handlers.regency) {
            this.regency.removeEventListener('change', this.handlers.regency);
        }
        if (this.district && this.handlers.district) {
            this.district.removeEventListener('change', this.handlers.district);
        }
        
        this.handlers = { province: null, regency: null, district: null };
        console.log('Cascade instance destroyed');
    }
    
    // Load regencies
    loadRegencies(provinceId) {
        this.resetSelect(this.regency, 'Loading...', true);
        this.resetSelect(this.district, 'Pilih Kecamatan', true);
        this.resetSelect(this.village, 'Pilih Kelurahan/Desa', true);
        
        if (!provinceId) {
            this.resetSelect(this.regency, 'Pilih Kabupaten/Kota', false);
            console.log('Province cleared, resetting regency');
            return;
        }
        
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
                this.populateSelect(this.regency, data, 'Pilih Kabupaten/Kota');
                console.log('Regencies loaded successfully');
            })
            .catch(error => {
                console.error('Error fetching regencies:', error);
                this.showError(this.regency, 'Error loading data');
                this.showNotification('Gagal memuat data kabupaten/kota', 'error');
            });
    }
    
    // Load districts
    loadDistricts(regencyId) {
        this.resetSelect(this.district, 'Loading...', true);
        this.resetSelect(this.village, 'Pilih Kelurahan/Desa', true);
        
        if (!regencyId) {
            this.resetSelect(this.district, 'Pilih Kecamatan', false);
            return;
        }
        
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
                this.populateSelect(this.district, data, 'Pilih Kecamatan');
                console.log('Districts loaded successfully');
            })
            .catch(error => {
                console.error('Error fetching districts:', error);
                this.showError(this.district, 'Error loading data');
                this.showNotification('Gagal memuat data kecamatan', 'error');
            });
    }
    
    // Load villages
    loadVillages(districtId) {
        this.resetSelect(this.village, 'Loading...', true);
        
        if (!districtId) {
            this.resetSelect(this.village, 'Pilih Kelurahan/Desa', false);
            return;
        }
        
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
                this.populateSelect(this.village, data, 'Pilih Kelurahan/Desa');
                console.log('Villages loaded successfully');
            })
            .catch(error => {
                console.error('Error fetching villages:', error);
                this.showError(this.village, 'Error loading data');
                this.showNotification('Gagal memuat data kelurahan/desa', 'error');
            });
    }
    
    // Reset select element
    resetSelect(select, placeholder, disabled = false) {
        select.innerHTML = `<option value="">-- ${placeholder} --</option>`;
        select.disabled = disabled;
    }
    
    // Populate select with data
    populateSelect(select, data, placeholder) {
        select.innerHTML = `<option value="">-- ${placeholder} --</option>`;
        
        if (data && Array.isArray(data) && data.length > 0) {
            data.forEach((item, index) => {
                console.log(`Adding item ${index}:`, item);
                select.innerHTML += `<option value="${item.id}">${item.name}</option>`;
            });
            select.disabled = false;
        } else {
            select.innerHTML += '<option value="">-- Tidak ada data --</option>';
            select.disabled = false;
            console.log('No data found');
        }
    }
    
    // Show error state
    showError(select, message) {
        select.innerHTML = `<option value="">-- ${message} --</option>`;
        select.disabled = false;
    }
    
    // Show notification
    showNotification(message, type = 'info') {
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
    
    // Reset all dropdowns
    resetAll() {
        this.resetSelect(this.regency, 'Pilih Kabupaten/Kota', false);
        this.resetSelect(this.district, 'Pilih Kecamatan', true);
        this.resetSelect(this.village, 'Pilih Kelurahan/Desa', true);
    }
}

// Helper function untuk quick initialization
function initAddressCascade(provinceId, regencyId, districtId, villageId) {
    return new AddressCascade({
        provinceId: provinceId,
        regencyId: regencyId,
        districtId: districtId,
        villageId: villageId
    });
}