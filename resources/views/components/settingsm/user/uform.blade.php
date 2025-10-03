<!-- Improved User Modal -->
<div id="userModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden animate-modal-in">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-semibold text-white">Add New User</h3>
                    <p class="text-indigo-100 text-sm mt-1">Create a new user account with role assignment</p>
                </div>
                <button onclick="closeUserModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body - Scrollable -->
        <div class="overflow-y-auto max-h-[calc(90vh-140px)]">
            <form action="{{ route('users.store') }}" method="POST" class="p-6">
                @csrf
                
                <!-- Personal Information Section -->
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-user-circle text-indigo-500 mr-2"></i>
                        Personal Information
                    </h4>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- Username -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Username <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="username" 
                                    class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
                                    placeholder="Enter username" required>
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <div class="relative">
                                <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="email" name="email" 
                                    class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
                                    placeholder="user@example.com">
                            </div>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <div class="relative">
                                <i class="fas fa-phone absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="phone" 
                                    class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
                                    placeholder="+62 812-3456-7890">
                            </div>
                        </div>

                        <!-- Birth Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Birth Date</label>
                            <div class="relative">
                                <i class="fas fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="date" name="birth_date" 
                                    class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
                                    max="{{ date('Y-M-D') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Section -->
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-indigo-500 mr-2"></i>
                        Address Information
                    </h4>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi <span class="text-red-500">*</span></label>
                        <select id="create-province" name="province_id" class="cascade-province w-full border border-gray-300 rounded-lg py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province->id }}">{{ $province->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kabupaten/Kota -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten/Kota <span class="text-red-500">*</span></label>
                        <select id="create-regency" name="regency_id" class="cascade-regency w-full border border-gray-300 rounded-lg py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                            <option value="">-- Pilih Kabupaten/Kota --</option>
                        </select>
                    </div>

                    <!-- Kecamatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan <span class="text-red-500">*</span></label>
                        <select id="create-district" name="district_id" class="cascade-district w-full border border-gray-300 rounded-lg py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                            <option value="">-- Pilih Kecamatan --</option>
                        </select>
                    </div>

                    <!-- Kelurahan/Desa -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kelurahan/Desa <span class="text-red-500">*</span></label>
                        <select id="create-village" name="village_id" class="cascade-village w-full border border-gray-300 rounded-lg py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                            <option value="">-- Pilih Kelurahan/Desa --</option>
                        </select>
                    </div>
        

                    <!-- Detail Alamat (full width) -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Detail Alamat</label>
                        <div class="relative">
                            <i class="fas fa-home absolute left-3 top-3 text-gray-400"></i>
                            <textarea id="address" name="address" rows="3" placeholder="Contoh: Jl. Merdeka No. 123, RT 01/RW 02" 
                                    class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none"></textarea>
                        </div>
                        <small class="text-gray-500">Isi dengan detail alamat seperti nama jalan, nomor rumah, RT/RW</small>
                    </div>
                </div>

                <!-- Account Settings Section -->
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-cog text-indigo-500 mr-2"></i>
                        Account Settings
                    </h4>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fas fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="password" name="password" id="userPassword"
                                    class="w-full border border-gray-300 rounded-lg pl-10 pr-10 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
                                    placeholder="Minimum 6 characters" required>
                                <button type="button" onclick="toggleUserPassword()" 
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye" id="userPasswordToggle"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Password must be at least 6 characters long</p>
                        </div>

                        <!-- Role -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Role <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fas fa-user-tag absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 z-10"></i>
                                <select name="role_id" 
                                    class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none bg-white" 
                                    required>
                                    <option value="">Select role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->role_id }}">{{ $role->role_name }}</option>
                                    @endforeach
                                </select>
                                <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Role Permission Preview (Optional Enhancement) -->
                <div id="rolePreview" class="mb-6 p-4 bg-gray-50 rounded-lg hidden">
                    <h5 class="font-medium text-gray-700 mb-2">Role Permissions Preview</h5>
                    <div id="rolePermissions" class="text-sm text-gray-600"></div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeUserModal()" 
                        class="px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 transform hover:scale-105 font-medium shadow-md hover:shadow-lg">
                        <i class="fas fa-user-plus mr-2"></i>
                        Create User
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

        /* Enhanced Form Styling */
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        /* Custom Select Styling */
        select {
            background-image: none;
        }

        /* Section Dividers */
        .section-divider {
            border-left: 4px solid #6366f1;
            padding-left: 1rem;
        }

        /* Custom Scrollbar */
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

        /* Form validation states */
        .error {
            border-color: #ef4444;
        }

        .error:focus {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .success {
            border-color: #10b981;
        }

        .success:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        /* Loading button state */
        .btn-loading {
            position: relative;
            pointer-events: none;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 16px;
            height: 16px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }

        /* Role preview styling */
        #rolePreview {
            border-left: 4px solid #6366f1;
            transition: all 0.3s ease;
        }

        /* Responsive adjustments */
        @media (max-width: 640px) {
            #userModal .bg-white {
                margin: 1rem;
                max-width: calc(100% - 2rem);
            }
            
            .grid.md\\:grid-cols-2 {
                grid-template-columns: 1fr;
            }
        }

        /* Icon sizing */
        .fa-user, .fa-envelope, .fa-phone, .fa-calendar-alt, 
        .fa-map-marker-alt, .fa-lock, .fa-user-tag {
            font-size: 14px;
        }
</style>

