<div id="transaksiModal" class="modal">
    <div class="modal-content">
        <!-- Header -->
        <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <div style="width: 2rem; height: 2rem; background-color: rgba(255, 255, 255, 0.2); border-radius: 0.3rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-file-invoice" style="color: white; font-size: 1rem;"></i>
                </div>
                <h3 id="modalTitle" style="color: white; font-weight: 600; margin: 0; font-size: 0.95rem;">Tambah Transaksi Baru</h3>
            </div>
            <button onclick="closeTransaksiModal()" style="background: none; border: none; color: white; font-size: 1.25rem; cursor: pointer;">×</button>
        </div>

        <!-- Form Container dengan 2 Kolom -->
        <div style="display: flex; flex: 1; overflow: hidden;">
            <!-- KOLOM KIRI: Sales Visit, Sales & Perusahaan -->
            <div style="flex: 0 0 45%; border-right: 1px solid #e5e7eb; overflow-y: auto; padding: 1rem;">
                <form id="transaksiForm">
                    @csrf
                    <input type="hidden" id="transaksiId" name="transaksi_id">
                    <input type="hidden" id="formMethod" name="_method" value="POST">

                    <!-- Sales Visit Selection -->
                    <div style="background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 0.375rem; padding: 0.75rem; margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.7rem; font-weight: 500; color: #1e40af; margin-bottom: 0.375rem;">
                            <i class="fas fa-link"></i> Pilih Sales Visit (Opsional)
                        </label>
                        <select name="sales_visit_id" id="sales_visit_id"
                            style="width: 100%; padding: 0.5rem; border: 1px solid #bfdbfe; border-radius: 0.3rem; font-size: 0.75rem;"
                            onchange="handleSalesVisitChange()"
                            onfocus="this.style.borderColor='#0284c7'; this.style.boxShadow='0 0 0 3px rgba(2, 132, 199, 0.1)'"
                            onblur="this.style.borderColor='#bfdbfe'; this.style.boxShadow='none'">
                            <option value="">-- Pilih Sales Visit --</option>
                            @foreach ($salesVisits as $visit)
                                @php
                                    $salesName = $visit->sales?->username ?? $visit->user?->username ?? 'N/A';
                                    $companyName = $visit->company?->nama ?? $visit->company_name ?? 'N/A';
                                    $salesId = $visit->sales_id ?? $visit->user_id;
                                @endphp
                                <option value="{{ $visit->id }}" 
                                    data-company-id="{{ $visit->company_id }}"
                                    data-company-name="{{ $companyName }}"
                                    data-sales-id="{{ $salesId }}"
                                    data-sales-name="{{ $salesName }}"
                                    data-visit-date="{{ $visit->visit_date ?? '' }}">
                                    {{ $companyName }} - {{ $salesName }} - {{ $visit->visit_date?->format('d M Y') ?? $visit->created_at->format('d M Y') }}
                                </option>
                            @endforeach
                        </select>
                        <small style="color: #1e40af; font-size: 0.65rem; margin-top: 0.25rem; display: block;">
                            <i class="fas fa-info-circle"></i> Pilih untuk mengisi otomatis perusahaan, sales, dan tanggal
                        </small>
                    </div>

                    <!-- Sales -->
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.7rem; font-weight: 600; color: #111827; margin-bottom: 0.375rem;">
                            Nama Sales <span style="color: #dc2626;">*</span>
                        </label>
                        <select name="sales_id" id="sales_id"
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.3rem; font-size: 0.75rem;"
                            required onchange="updateSalesName()"
                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                            onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'">
                            <option value="">-- Pilih Sales --</option>
                            @foreach ($sales as $s)
                                <option value="{{ $s->user_id }}" data-name="{{ $s->username }}">
                                    {{ $s->username }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nama Sales (Auto-filled) -->
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.7rem; font-weight: 600; color: #111827; margin-bottom: 0.375rem;">
                            Nama Sales Lengkap <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="text" name="nama_sales" id="nama_sales"
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.3rem; font-size: 0.75rem; background-color: #f9fafb; color: #111827;"
                            required readonly>
                    </div>

                    <!-- Company -->
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.7rem; font-weight: 600; color: #111827; margin-bottom: 0.375rem;">
                            Perusahaan <span style="color: #dc2626;">*</span>
                        </label>
                        <select name="company_id" id="company_id"
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.3rem; font-size: 0.75rem;"
                            required onchange="updateCompanyName()"
                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                            onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'">
                            <option value="">-- Pilih Perusahaan --</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->company_id }}" data-name="{{ $company->nama }}">
                                    {{ $company->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nama Perusahaan (Auto-filled) -->
                    <div>
                        <label style="display: block; font-size: 0.7rem; font-weight: 600; color: #111827; margin-bottom: 0.375rem;">
                            Nama Perusahaan <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="text" name="nama_perusahaan" id="nama_perusahaan"
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.3rem; font-size: 0.75rem; background-color: #f9fafb; color: #111827;"
                            required readonly>
                    </div>
                </form>
            </div>

            <!-- KOLOM KANAN: Nilai, Status, File, Tanggal, Keterangan -->
            <div style="flex: 0 0 55%; overflow-y: auto; padding: 1rem;">
                <form id="transaksiFormRight">
                    <!-- Nilai Proyek -->
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.7rem; font-weight: 600; color: #111827; margin-bottom: 0.375rem;">
                            Nilai Proyek (Rp) <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="number" name="nilai_proyek" id="nilai_proyek"
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.3rem; font-size: 0.75rem;"
                            placeholder="0" min="0" step="0.01" required
                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                            onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'">
                    </div>

                    <!-- Status -->
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.7rem; font-weight: 600; color: #111827; margin-bottom: 0.375rem;">
                            Status <span style="color: #dc2626;">*</span>
                        </label>
                        <select name="status" id="status"
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.3rem; font-size: 0.75rem;"
                            required
                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                            onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'">
                            <option value="">-- Pilih Status --</option>
                            <option value="Deals">Deals</option>
                            <option value="Fails">Fails</option>
                        </select>
                    </div>

                    <!-- Bukti SPK -->
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.7rem; font-weight: 600; color: #111827; margin-bottom: 0.375rem;">
                            <i class="fas fa-file-pdf"></i> Bukti SPK
                        </label>
                        <div style="border: 2px dashed #d1d5db; border-radius: 0.375rem; padding: 0.75rem; text-align: center; cursor: pointer; transition: all 0.2s;"
                            id="drop_spk"
                            onclick="document.getElementById('bukti_spk').click()"
                            ondrop="handleDrop(event, 'bukti_spk')"
                            ondragover="event.preventDefault(); this.style.borderColor='#3b82f6'; this.style.backgroundColor='#eff6ff'"
                            ondragleave="this.style.borderColor='#d1d5db'; this.style.backgroundColor='white'">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 1.125rem; color: #9ca3af; display: block;"></i>
                            <p style="color: #6b7280; margin: 0.25rem 0 0 0; font-size: 0.7rem; font-weight: 500;">Klik atau drag file</p>
                        </div>
                        <input type="file" name="bukti_spk" id="bukti_spk" class="hidden"
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                            onchange="updateFileName('bukti_spk')">
                        <p id="bukti_spk_name" style="color: #22c55e; font-size: 0.65rem; margin-top: 0.375rem;"></p>
                    </div>

                    <!-- Bukti DP -->
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.7rem; font-weight: 600; color: #111827; margin-bottom: 0.375rem;">
                            <i class="fas fa-file-pdf"></i> Bukti DP
                        </label>
                        <div style="border: 2px dashed #d1d5db; border-radius: 0.375rem; padding: 0.75rem; text-align: center; cursor: pointer; transition: all 0.2s;"
                            id="drop_dp"
                            onclick="document.getElementById('bukti_dp').click()"
                            ondrop="handleDrop(event, 'bukti_dp')"
                            ondragover="event.preventDefault(); this.style.borderColor='#3b82f6'; this.style.backgroundColor='#eff6ff'"
                            ondragleave="this.style.borderColor='#d1d5db'; this.style.backgroundColor='white'">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 1.125rem; color: #9ca3af; display: block;"></i>
                            <p style="color: #6b7280; margin: 0.25rem 0 0 0; font-size: 0.7rem; font-weight: 500;">Klik atau drag file</p>
                        </div>
                        <input type="file" name="bukti_dp" id="bukti_dp" class="hidden"
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                            onchange="updateFileName('bukti_dp')">
                        <p id="bukti_dp_name" style="color: #22c55e; font-size: 0.65rem; margin-top: 0.375rem;"></p>
                    </div>

                    <!-- Tanggal Mulai -->
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.7rem; font-weight: 600; color: #111827; margin-bottom: 0.375rem;">
                            <i class="fas fa-calendar"></i> Tanggal Mulai Kerja
                        </label>
                        <input type="date" name="tanggal_mulai_kerja" id="tanggal_mulai_kerja"
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.3rem; font-size: 0.75rem;"
                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                            onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'">
                    </div>

                    <!-- Tanggal Selesai -->
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.7rem; font-weight: 600; color: #111827; margin-bottom: 0.375rem;">
                            <i class="fas fa-calendar"></i> Tanggal Selesai Kerja
                        </label>
                        <input type="date" name="tanggal_selesai_kerja" id="tanggal_selesai_kerja"
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.3rem; font-size: 0.75rem;"
                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                            onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'">
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label style="display: block; font-size: 0.7rem; font-weight: 600; color: #111827; margin-bottom: 0.375rem;">
                            <i class="fas fa-sticky-note"></i> Keterangan
                        </label>
                        <textarea name="keterangan" id="keterangan" rows="2"
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.3rem; font-size: 0.75rem; font-family: inherit; resize: vertical;"
                            placeholder="Catatan tambahan..."
                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                            onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"></textarea>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer dengan 2 Tombol (Deals & Fails) -->
        <div style="padding: 0.875rem 1rem; border-top: 1px solid #e5e7eb; background-color: #f9fafb; display: flex; gap: 0.75rem; flex-shrink: 0;">
            <button type="button" onclick="closeTransaksiModal()"
                style="flex: 1; padding: 0.5rem; background-color: #e5e7eb; color: #111827; border: none; border-radius: 0.3rem; font-weight: 500; font-size: 0.75rem; cursor: pointer; transition: all 0.2s;"
                onmouseover="this.style.backgroundColor='#d1d5db'"
                onmouseout="this.style.backgroundColor='#e5e7eb'">
                <i class="fas fa-times"></i> Batal
            </button>
            <button type="button" id="submitBtn"
                style="flex: 1; padding: 0.5rem; background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); color: white; border: none; border-radius: 0.3rem; font-weight: 500; font-size: 0.75rem; cursor: pointer; transition: all 0.2s;"
                onclick="submitTransaksiForm(event)"
                onmouseover="this.style.boxShadow='0 10px 15px rgba(34, 197, 94, 0.3)'"
                onmouseout="this.style.boxShadow='none'">
                <i class="fas fa-check"></i> Deals
            </button>
            <button type="button" id="submitBtnFail"
                style="flex: 1; padding: 0.5rem; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; border: none; border-radius: 0.3rem; font-weight: 500; font-size: 0.75rem; cursor: pointer; transition: all 0.2s;"
                onclick="submitTransaksiFormFails(event)"
                onmouseover="this.style.boxShadow='0 10px 15px rgba(239, 68, 68, 0.3)'"
                onmouseout="this.style.boxShadow='none'">
                <i class="fas fa-times"></i> Fails
            </button>
        </div>
    </div>
</div>

<script>
    // Handle perubahan Sales Visit
    function handleSalesVisitChange() {
        const select = document.getElementById('sales_visit_id');
        const visitId = select.value;
        const option = select.options[select.selectedIndex];

        if (visitId) {
            const companyId = option.getAttribute('data-company-id');
            const companyName = option.getAttribute('data-company-name');
            const salesId = option.getAttribute('data-sales-id');
            const salesName = option.getAttribute('data-sales-name');
            const visitDate = option.getAttribute('data-visit-date');
            
            // Set Company
            document.getElementById('company_id').value = companyId;
            document.getElementById('nama_perusahaan').value = companyName;
            
            // Set Sales
            document.getElementById('sales_id').value = salesId;
            document.getElementById('nama_sales').value = salesName;
            
            // Set Tanggal Mulai dari visit_date
            if (visitDate) {
                document.getElementById('tanggal_mulai_kerja').value = visitDate;
            }
        } else {
            // Clear jika tidak dipilih
            document.getElementById('company_id').value = '';
            document.getElementById('nama_perusahaan').value = '';
            document.getElementById('sales_id').value = '';
            document.getElementById('nama_sales').value = '';
            document.getElementById('tanggal_mulai_kerja').value = '';
            document.getElementById('tanggal_selesai_kerja').value = '';
        }
    }

    // Update nama sales saat dipilih
    function updateSalesName() {
        const select = document.getElementById('sales_id');
        const selected = select.options[select.selectedIndex];
        document.getElementById('nama_sales').value = selected.getAttribute('data-name') || '';
    }

    // Update nama perusahaan saat dipilih
    function updateCompanyName() {
        const select = document.getElementById('company_id');
        const selected = select.options[select.selectedIndex];
        document.getElementById('nama_perusahaan').value = selected.getAttribute('data-name') || '';
    }

    // Update nama file yang diupload
    function updateFileName(fieldId) {
        const input = document.getElementById(fieldId);
        const nameDisplay = document.getElementById(fieldId + '_name');
        if (input.files.length > 0) {
            nameDisplay.textContent = '✓ ' + input.files[0].name;
        } else {
            nameDisplay.textContent = '';
        }
    }

    // Handle drag & drop file
    function handleDrop(e, fieldId) {
        e.preventDefault();
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            document.getElementById(fieldId).files = files;
            updateFileName(fieldId);
        }
        // Reset style
        const dropZone = fieldId === 'bukti_spk' ? 'drop_spk' : 'drop_dp';
        document.getElementById(dropZone).style.borderColor = '#d1d5db';
        document.getElementById(dropZone).style.backgroundColor = 'white';
    }

    // Submit form dengan status Deals
    function submitTransaksiForm(e) {
        e.preventDefault();
        document.getElementById('status').value = 'Deals';
        submitForm();
    }

    // Submit form dengan status Fails
    function submitTransaksiFormFails(e) {
        e.preventDefault();
        document.getElementById('status').value = 'Fails';
        submitForm();
    }

    // Fungsi submit utama
    function submitForm() {
        // Validasi field wajib
        if (!document.getElementById('sales_id').value) {
            alert('Silakan pilih Sales terlebih dahulu!');
            return;
        }
        if (!document.getElementById('company_id').value) {
            alert('Silakan pilih Perusahaan terlebih dahulu!');
            return;
        }
        if (!document.getElementById('nilai_proyek').value) {
            alert('Silakan masukkan Nilai Proyek!');
            return;
        }
        if (!document.getElementById('status').value) {
            alert('Silakan pilih Status!');
            return;
        }

        const transaksiId = document.getElementById('transaksiId').value;
        const form = document.getElementById('transaksiForm');
        const formData = new FormData(form);
        
        // Tambahkan field dari kolom kanan
        formData.append('nilai_proyek', document.getElementById('nilai_proyek').value);
        formData.append('status', document.getElementById('status').value);
        formData.append('tanggal_mulai_kerja', document.getElementById('tanggal_mulai_kerja').value);
        formData.append('tanggal_selesai_kerja', document.getElementById('tanggal_selesai_kerja').value);
        formData.append('keterangan', document.getElementById('keterangan').value);
        
        // Tambahkan file jika ada
        if (document.getElementById('bukti_spk').files.length > 0) {
            formData.append('bukti_spk', document.getElementById('bukti_spk').files[0]);
        }
        if (document.getElementById('bukti_dp').files.length > 0) {
            formData.append('bukti_dp', document.getElementById('bukti_dp').files[0]);
        }

        const url = transaksiId 
            ? `/transaksi/${transaksiId}`
            : '/transaksi';

        if (transaksiId) {
            formData.append('_method', 'PUT');
        }

        // Tampilkan loading state
        const submitBtn = document.getElementById('submitBtn');
        const submitBtnFail = document.getElementById('submitBtnFail');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtnFail.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (response.ok) {
                window.location.href = '/transaksi';
            } else {
                return response.json().then(data => {
                    let errors = '';
                    if (data.errors) {
                        errors = Object.values(data.errors).flat().join('\n');
                    }
                    alert('Terjadi kesalahan:\n' + (errors || 'Coba lagi'));
                    
                    // Reset button
                    submitBtn.disabled = false;
                    submitBtnFail.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan: ' + error);
            
            // Reset button
            submitBtn.disabled = false;
            submitBtnFail.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    }

    // Buka modal untuk tambah baru
    function openTransaksiModal() {
        document.getElementById('transaksiModal').classList.add('active');
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('transaksiForm').reset();
        document.getElementById('transaksiFormRight').reset();
        document.getElementById('modalTitle').textContent = 'Tambah Transaksi Baru';
        document.getElementById('transaksiId').value = '';
        document.getElementById('submitBtn').innerHTML = '<i class="fas fa-check"></i> Deals';
        document.getElementById('submitBtnFail').innerHTML = '<i class="fas fa-times"></i> Fails';
        
        // Reset file names
        document.getElementById('bukti_spk_name').textContent = '';
        document.getElementById('bukti_dp_name').textContent = '';
    }

    // Tutup modal
    function closeTransaksiModal() {
        document.getElementById('transaksiModal').classList.remove('active');
    }

    // Edit transaksi
    function editTransaksi(id) {
        fetch(`/transaksi/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('transaksiId').value = data.id;
                document.getElementById('sales_visit_id').value = data.sales_visit_id || '';
                document.getElementById('sales_id').value = data.sales_id;
                document.getElementById('company_id').value = data.company_id;
                document.getElementById('nama_sales').value = data.nama_sales;
                document.getElementById('nama_perusahaan').value = data.nama_perusahaan;
                document.getElementById('nilai_proyek').value = data.nilai_proyek;
                document.getElementById('status').value = data.status;
                document.getElementById('tanggal_mulai_kerja').value = data.tanggal_mulai_kerja || '';
                document.getElementById('tanggal_selesai_kerja').value = data.tanggal_selesai_kerja || '';
                document.getElementById('keterangan').value = data.keterangan || '';
                
                document.getElementById('formMethod').value = 'PUT';
                document.getElementById('modalTitle').textContent = 'Edit Transaksi';
                document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save"></i> Update Deals';
                document.getElementById('submitBtnFail').innerHTML = '<i class="fas fa-save"></i> Update Fails';
                document.getElementById('transaksiModal').classList.add('active');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil data');
            });
    }

    // Lihat detail transaksi
    function viewTransaksi(id) {
        fetch(`/transaksi/${id}`)
            .then(response => response.json())
            .then(data => {
                let statusBadge = data.status === 'Deals' 
                    ? '<span style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.25rem 0.625rem; background-color: #dcfce7; color: #166534; border-radius: 9999px; font-size: 0.7rem; font-weight: 600;"><i class="fas fa-check-circle"></i> Deals</span>'
                    : '<span style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.25rem 0.625rem; background-color: #fee2e2; color: #991b1b; border-radius: 9999px; font-size: 0.7rem; font-weight: 600;"><i class="fas fa-times-circle"></i> Fails</span>';
                
                let html = `
                    <div style="padding: 1rem;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                            <div>
                                <p style="font-size: 0.65rem; color: #6b7280; font-weight: 600; text-transform: uppercase; margin: 0;">Nama Sales</p>
                                <p style="color: #111827; font-weight: 500; margin: 0.25rem 0 0 0; font-size: 0.875rem;">${data.nama_sales}</p>
                            </div>
                            <div>
                                <p style="font-size: 0.65rem; color: #6b7280; font-weight: 600; text-transform: uppercase; margin: 0;">Perusahaan</p>
                                <p style="color: #111827; font-weight: 500; margin: 0.25rem 0 0 0; font-size: 0.875rem;">${data.nama_perusahaan}</p>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                            <div>
                                <p style="font-size: 0.65rem; color: #6b7280; font-weight: 600; text-transform: uppercase; margin: 0;">Nilai Proyek</p>
                                <p style="color: #111827; font-weight: 500; margin: 0.25rem 0 0 0; font-size: 0.875rem;">Rp${new Intl.NumberFormat('id-ID').format(data.nilai_proyek)}</p>
                            </div>
                            <div>
                                <p style="font-size: 0.65rem; color: #6b7280; font-weight: 600; text-transform: uppercase; margin: 0;">Status</p>
                                <div style="margin: 0.25rem 0 0 0;">${statusBadge}</div>
                            </div>
                        </div>

                        ${data.tanggal_mulai_kerja ? `
                        <div style="margin-bottom: 1rem;">
                            <p style="font-size: 0.65rem; color: #6b7280; font-weight: 600; text-transform: uppercase; margin: 0;">Tanggal Kerja</p>
                            <p style="color: #111827; font-weight: 500; margin: 0.25rem 0 0 0; font-size: 0.875rem;">${data.tanggal_mulai_kerja} s/d ${data.tanggal_selesai_kerja || '-'}</p>
                        </div>
                        ` : ''}
                        
                        ${data.keterangan ? `
                        <div>
                            <p style="font-size: 0.65rem; color: #6b7280; font-weight: 600; text-transform: uppercase; margin: 0;">Keterangan</p>
                            <p style="color: #111827; margin: 0.25rem 0 0 0; font-size: 0.875rem;">${data.keterangan}</p>
                        </div>
                        ` : ''}
                    </div>
                `;
                document.getElementById('detailContent').innerHTML = html;
                document.getElementById('detailModal').classList.add('active');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil data detail');
            });
    }

    // Tutup detail modal
    function closeDetailModal() {
        document.getElementById('detailModal').classList.remove('active');
    }

    // Close modal ketika click outside
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('transaksiModal');
        const detailModal = document.getElementById('detailModal');
        if (event.target === modal) {
            modal.classList.remove('active');
        }
        if (event.target === detailModal) {
            detailModal.classList.remove('active');
        }
    });
</script>