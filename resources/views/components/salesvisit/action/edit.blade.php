<!-- ==================== EDIT SALES VISIT MODAL ==================== -->
<div id="editVisitModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden animate-modal-in">
        
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-white">Edit Kunjungan Sales</h2>
                    <p class="text-indigo-100 text-sm mt-1">Update data kunjungan sales</p>
                </div>
                <button onclick="closeEditVisitModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="overflow-y-auto max-h-[calc(90vh-140px)] p-6">
            <form id="editVisitForm" action="" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" id="editVisitId" name="visit_id">

                <!-- Sales Information Section -->
                <div>
                    <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center border-b pb-2">
                        <i class="fas fa-user-tie text-indigo-500 mr-2"></i>
                        Informasi Sales
                    </h4>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- Sales -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Sales <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <select name="sales_id" id="editSalesId" required
                                    class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                    <option value="">-- Pilih Sales --</option>
                                    <!-- Data akan di-load via JavaScript -->
                                </select>
                            </div>
                        </div>

                        <!-- Customer Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Customer <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fas fa-user-circle absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="customer_name" id="editCustomerName" required
                                    class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                                    placeholder="Masukkan nama customer">
                            </div>
                        </div>

                        <!-- Company -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Perusahaan</label>
                            <div class="relative">
                                <i class="fas fa-building absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="company" id="editCompany"
                                    class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                                    placeholder="Nama perusahaan (opsional)">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visit Details Section -->
                <div>
                    <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center border-b pb-2">
                        <i class="fas fa-calendar-check text-indigo-500 mr-2"></i>
                        Detail Kunjungan
                    </h4>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- Provinsi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi <span class="text-red-500">*</span></label>
                            <select id="edit-province" name="province_id" class="w-full border border-gray-300 rounded-lg px-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                                <option value="">-- Pilih Provinsi --</option>
                                <!-- Data akan di-load via JavaScript -->
                            </select>
                        </div>

                        

                        <!-- Visit Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Kunjungan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fas fa-calendar absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="date" name="visit_date" id="editVisitDate" required
                                    class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                                    max="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <!-- Purpose -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tujuan Kunjungan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fas fa-clipboard-list absolute left-3 top-3 text-gray-400"></i>
                                <textarea name="purpose" id="editPurpose" rows="4" required
                                    class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none transition-all"
                                    placeholder="Jelaskan tujuan kunjungan sales..."></textarea>
                            </div>
                        </div>

                        <!-- Follow Up -->
                        <div class="md:col-span-2">
                            <div class="flex items-center">
                                <input type="checkbox" name="follow_up" value="1" id="editFollowUp"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="editFollowUp" class="ml-2 text-sm font-medium text-gray-700">
                                    Perlu Follow Up
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 ml-6">Centang jika kunjungan ini memerlukan tindak lanjut</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeEditVisitModal()" 
                        class="px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        Batal
                    </button>
                    <button type="submit" 
                        class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 transform hover:scale-105 font-medium shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i>
                        Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>