@props(['currentMenuId'])

<!-- EDIT SALES VISIT MODAL -->
<div id="editVisitModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden animate-modal-in">
        
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-white">Edit Kunjungan Sales</h2>
                    <p class="text-purple-100 text-sm mt-1">Ubah data kunjungan sales</p>
                </div>
                <button onclick="closeEditVisitModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="overflow-y-auto max-h-[calc(90vh-140px)] p-6">
            <form id="editVisitForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <input type="hidden" id="editVisitId" name="visit_id">
                <input type="hidden" id="editIsSalesRole" value="{{ strtolower(auth()->user()->role->role_name ?? '') === 'sales' ? '1' : '0' }}">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sales <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i class="fas fa-user-tie absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <select name="sales_id" id="editSalesId"
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                            required>
                            <option value="">Pilih Sales</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="customer_name" id="editCustomerName"
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                            placeholder="Masukkan nama customer" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Company</label>
                    <div class="relative">
                        <i class="fas fa-building absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="company_name" id="editCompany"
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                            placeholder="Masukkan nama perusahaan">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Province <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i class="fas fa-map-marked-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <select name="edit-province" id="edit-province"
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                            required>
                            <option value="">Pilih Provinsi</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Regency</label>
                    <div class="relative">
                        <i class="fas fa-map-marker-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <select name="edit-regency" id="edit-regency"
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                            <option value="">Pilih Kabupaten/Kota</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">District</label>
                    <div class="relative">
                        <i class="fas fa-map-pin absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <select name="edit-district" id="edit-district"
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                            <option value="">Pilih Kecamatan</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Village</label>
                    <div class="relative">
                        <i class="fas fa-location-dot absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <select name="edit-village" id="edit-village"
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                            <option value="">Pilih Kelurahan/Desa</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <div class="relative">
                        <i class="fas fa-map-location-dot absolute left-3 top-4 text-gray-400"></i>
                        <textarea name="address" id="editAddress" rows="2"
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 resize-none transition-all"
                            placeholder="Masukkan alamat lengkap..."></textarea>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Visit Date <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i class="fas fa-calendar absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="date" name="visit_date" id="editVisitDate"
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                            required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Purpose <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i class="fas fa-bullseye absolute left-3 top-4 text-gray-400"></i>
                        <textarea name="visit_purpose" id="editPurpose" rows="3"
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 resize-none transition-all"
                            placeholder="Masukkan tujuan kunjungan..." required></textarea>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Follow Up</label>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_follow_up" id="editFollowUpYes" value="1" class="w-4 h-4 text-purple-600 focus:ring-purple-500">
                            <span class="text-sm text-gray-700">Ya</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_follow_up" id="editFollowUpNo" value="0" class="w-4 h-4 text-purple-600 focus:ring-purple-500">
                            <span class="text-sm text-gray-700">Tidak</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 mt-6">
                    <button type="button" onclick="closeEditVisitModal()" class="px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all duration-200 transform hover:scale-105 font-medium shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i>
                        Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
@keyframes modalSlideIn {
    from { 
        opacity: 0; 
        transform: translateY(-20px) scale(0.95); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0) scale(1); 
    }
}

.animate-modal-in { 
    animation: modalSlideIn 0.25s ease-out; 
}
</style>