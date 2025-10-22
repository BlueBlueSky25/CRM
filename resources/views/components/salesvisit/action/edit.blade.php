@props(['salesUsers', 'provinces'])

<!-- Modal Edit Sales Visit -->
<div id="editVisitModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden animate-modal-in">
        
        <!-- Modal Header -->
        <div style="background: linear-gradient(to right, #4f46e5, #7c3aed); padding: 1.25rem 1.5rem;">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-semibold text-white">Edit Kunjungan Sales</h3>
                    <p class="text-sm text-indigo-100 mt-1">Update data kunjungan sales</p>
                </div>
                <button onclick="closeEditVisitModal()" 
                    class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="overflow-y-auto max-h-[calc(90vh-140px)]" style="background-color: #f3f4f6; padding: 1.5rem;">
            <form id="editVisitForm" method="POST" style="display: flex; flex-direction: column; gap: 1.5rem;">
                @csrf
                @method('PUT')
                
                <input type="hidden" id="editVisitId" name="visit_id">

                <!-- Sales Information Section -->
                <div>
                    <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center border-b pb-2">
                        <i class="fas fa-user-tie text-indigo-500 mr-2"></i>
                        Informasi Sales
                    </h4>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                        <!-- Sales -->
                        <div style="grid-column: span 2;">
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                                Sales <span style="color: #ef4444;">*</span>
                            </label>
                            <div style="position: relative;">
                                <i class="fas fa-user" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                                <select id="editSalesId" name="sales_id" required
                                    style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem 0.75rem 2.5rem; font-size: 0.875rem;">
                                    <option value="">-- Pilih Sales --</option>
                                </select>
                            </div>
                        </div>

                        <!-- Customer Name -->
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                                Nama Customer <span style="color: #ef4444;">*</span>
                            </label>
                            <div style="position: relative;">
                                <i class="fas fa-user-circle" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                                <input type="text" id="editCustomerName" name="customer_name" required
                                    style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem 0.75rem 2.5rem; font-size: 0.875rem;"
                                    placeholder="Masukkan nama customer">
                            </div>
                        </div>

                        <!-- Company -->
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                                Perusahaan
                            </label>
                            <div style="position: relative;">
                                <i class="fas fa-building" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                                <input type="text" id="editCompany" name="company"
                                    style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem 0.75rem 2.5rem; font-size: 0.875rem;"
                                    placeholder="Nama perusahaan (opsional)">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Section -->
                <div>
                    <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center border-b pb-2">
                        <i class="fas fa-map-marker-alt text-indigo-500 mr-2"></i>
                        Informasi Alamat
                    </h4>
                    
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                        <!-- Provinsi -->
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                                Provinsi <span style="color: #ef4444;">*</span>
                            </label>
                            <select id="edit-province" name="province_id" class="cascade-province" required
                                style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem; font-size: 0.875rem;">
                                <option value="">-- Pilih Provinsi --</option>
                            </select>
                        </div>

                        <!-- Kabupaten/Kota -->
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                                Kabupaten/Kota <span style="color: #ef4444;">*</span>
                            </label>
                            <select id="edit-regency" name="regency_id" class="cascade-regency" required
                                style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem; font-size: 0.875rem;">
                                <option value="">-- Pilih Kabupaten/Kota --</option>
                            </select>
                        </div>

                        <!-- Kecamatan -->
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                                Kecamatan <span style="color: #ef4444;">*</span>
                            </label>
                            <select id="edit-district" name="district_id" class="cascade-district" required
                                style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem; font-size: 0.875rem;">
                                <option value="">-- Pilih Kecamatan --</option>
                            </select>
                        </div>

                        <!-- Kelurahan/Desa -->
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                                Kelurahan/Desa <span style="color: #ef4444;">*</span>
                            </label>
                            <select id="edit-village" name="village_id" class="cascade-village" required
                                style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem; font-size: 0.875rem;">
                                <option value="">-- Pilih Kelurahan/Desa --</option>
                            </select>
                        </div>

                        <!-- Detail Alamat (full width) -->
                        <div style="grid-column: span 2;">
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                                Detail Alamat
                            </label>
                            <div style="position: relative;">
                                <i class="fas fa-home" style="position: absolute; left: 0.75rem; top: 0.75rem; color: #9ca3af;"></i>
                                <textarea id="editAddress" name="address" rows="3" 
                                    placeholder="Contoh: Jl. Merdeka No. 123, RT 01/RW 02"
                                    style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem 0.75rem 2.5rem; font-size: 0.875rem; resize: none;"></textarea>
                            </div>
                            <small style="color: #6b7280; font-size: 0.75rem;">Isi dengan detail alamat seperti nama jalan, nomor rumah, RT/RW</small>
                        </div>
                    </div>
                </div>

                <!-- Visit Details Section -->
                <div>
                    <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center border-b pb-2">
                        <i class="fas fa-calendar-check text-indigo-500 mr-2"></i>
                        Detail Kunjungan
                    </h4>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                        <!-- Visit Date -->
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                                Tanggal Kunjungan <span style="color: #ef4444;">*</span>
                            </label>
                            <div style="position: relative;">
                                <i class="fas fa-calendar" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                                <input type="date" id="editVisitDate" name="visit_date" required
                                    style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem 0.75rem 2.5rem; font-size: 0.875rem;"
                                    max="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <!-- Purpose (full width) -->
                        <div style="grid-column: span 2;">
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                                Tujuan Kunjungan <span style="color: #ef4444;">*</span>
                            </label>
                            <div style="position: relative;">
                                <i class="fas fa-clipboard-list" style="position: absolute; left: 0.75rem; top: 0.75rem; color: #9ca3af;"></i>
                                <textarea id="editPurpose" name="purpose" rows="4" required
                                    placeholder="Jelaskan tujuan kunjungan sales..."
                                    style="width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem 0.75rem 2.5rem; font-size: 0.875rem; resize: none;"></textarea>
                            </div>
                        </div>

                        <!-- Follow Up (full width) -->
                        <div style="grid-column: span 2;">
                            <div style="display: flex; align-items: center;">
                                <input type="checkbox" name="is_follow_up" value="1" id="editFollowUp"
                                    style="width: 1rem; height: 1rem; color: #4f46e5; border-color: #d1d5db; border-radius: 0.25rem;">
                                <label for="editFollowUp" style="margin-left: 0.5rem; font-size: 0.875rem; font-weight: 500; color: #374151;">
                                    Perlu Follow Up
                                </label>
                            </div>
                            <p style="margin-left: 1.5rem; margin-top: 0.25rem; font-size: 0.75rem; color: #6b7280;">
                                Centang jika kunjungan ini memerlukan tindak lanjut
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div style="display: flex; justify-content: flex-end; gap: 0.75rem; padding-top: 1rem; border-top: 1px solid #e5e7eb; margin-top: 0.5rem;">
                    <button type="button" 
                            onclick="closeEditVisitModal()" 
                            style="background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.625rem 1.5rem; font-weight: 500; font-size: 0.875rem; cursor: pointer; transition: all 0.2s;">
                        Batal
                    </button>
                    <button type="submit" 
                            style="background-color: #4f46e5; color: white; border: none; border-radius: 0.5rem; padding: 0.625rem 1.5rem; font-weight: 500; font-size: 0.875rem; cursor: pointer; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); transition: all 0.2s;">
                        <i class="fas fa-save" style="margin-right: 0.5rem;"></i>
                        Update Data
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

/* Custom Scrollbar for Modal */
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

/* Enhanced focus states */
input:focus, textarea:focus, select:focus {
    outline: none;
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}
</style>