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
            <!-- KOLOM KIRI: Sales & Perusahaan -->
            <div style="flex: 0 0 45%; border-right: 1px solid #e5e7eb; overflow-y: auto; padding: 1rem;">
                <form id="transaksiForm">
                    @csrf
                    <input type="hidden" id="transaksiId" name="transaksi_id">
                    <input type="hidden" id="formMethod" name="_method" value="POST">

                    <!-- Sales Visit -->
                    <div style="background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 0.375rem; padding: 0.75rem; margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.7rem; font-weight: 500; color: #1e40af; margin-bottom: 0.375rem;">
                            <i class="fas fa-link"></i> Pilih Sales Visit (Opsional)
                        </label>
                        <select name="sales_visit_id" id="sales_visit_id"
                            style="width: 100%; padding: 0.5rem; border: 1px solid #bfdbfe; border-radius: 0.3rem; font-size: 0.75rem;">
                            <option value="">-- Pilih Sales Visit --</option>
                            @foreach ($salesVisits as $visit)
                                <option value="{{ $visit->id }}">
                                    {{ $visit->company->nama ?? 'N/A' }} - {{ $visit->created_at->format('d M Y') }}
                                </option>
                            @endforeach
                        </select>
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
                                <option value="{{ $s->user_id }}" data-name="{{ $s->nama }}">
                                    {{ $s->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nama Sales -->
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.7rem; font-weight: 600; color: #111827; margin-bottom: 0.375rem;">
                            Nama Sales Lengkap <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="text" name="nama_sales" id="nama_sales"
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.3rem; font-size: 0.75rem; background-color: #f9fafb;"
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

                    <!-- Nama Perusahaan -->
                    <div>
                        <label style="display: block; font-size: 0.7rem; font-weight: 600; color: #111827; margin-bottom: 0.375rem;">
                            Nama Perusahaan <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="text" name="nama_perusahaan" id="nama_perusahaan"
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.3rem; font-size: 0.75rem; background-color: #f9fafb;"
                            required readonly>
                    </div>
                </form>
            </div>

            <!-- KOLOM KANAN: Sisanya -->
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
                            onclick="document.getElementById('bukti_spk').click()"
                            ondrop="handleDrop(event, 'bukti_spk')"
                            ondragover="event.preventDefault(); this.style.borderColor='#3b82f6'; this.style.backgroundColor='#eff6ff'"
                            ondragleave="this.style.borderColor='#d1d5db'; this.style.backgroundColor='white'">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 1.125rem; color: #9ca3af; display: block;"></i>
                            <p style="color: #6b7280; margin: 0.25rem 0 0 0; font-size: 0.7rem; font-weight: 500;">Klik atau drag</p>
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
                            onclick="document.getElementById('bukti_dp').click()"
                            ondrop="handleDrop(event, 'bukti_dp')"
                            ondragover="event.preventDefault(); this.style.borderColor='#3b82f6'; this.style.backgroundColor='#eff6ff'"
                            ondragleave="this.style.borderColor='#d1d5db'; this.style.backgroundColor='white'">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 1.125rem; color: #9ca3af; display: block;"></i>
                            <p style="color: #6b7280; margin: 0.25rem 0 0 0; font-size: 0.7rem; font-weight: 500;">Klik atau drag</p>
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
    function submitTransaksiForm(e) {
        e.preventDefault();
        document.getElementById('status').value = 'Deals';
        submitForm();
    }

    function submitTransaksiFormFails(e) {
        e.preventDefault();
        document.getElementById('status').value = 'Fails';
        submitForm();
    }

    function submitForm() {
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
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan: ' + error);
        });
    }
</script>