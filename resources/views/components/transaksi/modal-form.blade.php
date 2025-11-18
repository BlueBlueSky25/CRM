<div id="transaksiModal" class="modal">
    <div class="modal-content">
        <!-- Header -->
        <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="width: 2.5rem; height: 2.5rem; background-color: rgba(255, 255, 255, 0.2); border-radius: 0.375rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-file-invoice" style="color: white; font-size: 1.25rem;"></i>
                </div>
                <h3 id="modalTitle" style="color: white; font-weight: 600; margin: 0;">Tambah Transaksi Baru</h3>
            </div>
            <button onclick="closeTransaksiModal()" style="background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer;">×</button>
        </div>

        <!-- Form -->
        <form id="transaksiForm" style="padding: 1.5rem;">
            @csrf
            <input type="hidden" id="transaksiId" name="transaksi_id">
            <input type="hidden" id="formMethod" name="_method" value="POST">

            <!-- Sales Visit -->
            <div style="background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #1e40af; margin-bottom: 0.5rem;">
                    <i class="fas fa-link"></i> Pilih Sales Visit (Opsional)
                </label>
                <select name="sales_visit_id" id="sales_visit_id"
                    style="width: 100%; padding: 0.625rem; border: 1px solid #bfdbfe; border-radius: 0.375rem; font-size: 0.875rem;">
                    <option value="">-- Pilih Sales Visit --</option>
                    @foreach ($salesVisits as $visit)
                        <option value="{{ $visit->id }}">
                            {{ $visit->company->nama ?? 'N/A' }} - {{ $visit->created_at->format('d M Y') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                <!-- Sales -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">
                        Nama Sales <span style="color: #dc2626;">*</span>
                    </label>
                    <select name="sales_id" id="sales_id"
                        style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;"
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
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">
                        Nama Sales Lengkap <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="text" name="nama_sales" id="nama_sales"
                        style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; background-color: #f9fafb;"
                        required readonly>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                <!-- Company -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">
                        Perusahaan <span style="color: #dc2626;">*</span>
                    </label>
                    <select name="company_id" id="company_id"
                        style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;"
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
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">
                        Nama Perusahaan <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="text" name="nama_perusahaan" id="nama_perusahaan"
                        style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; background-color: #f9fafb;"
                        required readonly>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                <!-- Nilai -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">
                        Nilai Proyek (Rp) <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="number" name="nilai_proyek" id="nilai_proyek"
                        style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;"
                        placeholder="0" min="0" step="0.01" required
                        onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                        onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'">
                </div>

                <!-- Status -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">
                        Status <span style="color: #dc2626;">*</span>
                    </label>
                    <select name="status" id="status"
                        style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;"
                        required
                        onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                        onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'">
                        <option value="">-- Pilih Status --</option>
                        <option value="Deals">Deals</option>
                        <option value="Fails">Fails</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                <!-- Bukti SPK -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">
                        <i class="fas fa-file-pdf"></i> Bukti SPK (File)
                    </label>
                    <div style="border: 2px dashed #d1d5db; border-radius: 0.5rem; padding: 1rem; text-align: center; cursor: pointer;"
                        onclick="document.getElementById('bukti_spk').click()"
                        ondrop="handleDrop(event, 'bukti_spk')"
                        ondragover="event.preventDefault(); this.style.borderColor='#3b82f6'; this.style.backgroundColor='#eff6ff'"
                        ondragleave="this.style.borderColor='#d1d5db'; this.style.backgroundColor='white'">
                        <i class="fas fa-cloud-upload-alt" style="font-size: 1.5rem; color: #9ca3af; display: block;"></i>
                        <p style="color: #6b7280; margin: 0.5rem 0 0 0; font-size: 0.875rem; font-weight: 500;">Klik atau drag file</p>
                    </div>
                    <input type="file" name="bukti_spk" id="bukti_spk" class="hidden"
                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                        onchange="updateFileName('bukti_spk')">
                    <p id="bukti_spk_name" style="color: #22c55e; font-size: 0.75rem; margin-top: 0.5rem;"></p>
                </div>

                <!-- Bukti DP -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">
                        <i class="fas fa-file-pdf"></i> Bukti DP (File)
                    </label>
                    <div style="border: 2px dashed #d1d5db; border-radius: 0.5rem; padding: 1rem; text-align: center; cursor: pointer;"
                        onclick="document.getElementById('bukti_dp').click()"
                        ondrop="handleDrop(event, 'bukti_dp')"
                        ondragover="event.preventDefault(); this.style.borderColor='#3b82f6'; this.style.backgroundColor='#eff6ff'"
                        ondragleave="this.style.borderColor='#d1d5db'; this.style.backgroundColor='white'">
                        <i class="fas fa-cloud-upload-alt" style="font-size: 1.5rem; color: #9ca3af; display: block;"></i>
                        <p style="color: #6b7280; margin: 0.5rem 0 0 0; font-size: 0.875rem; font-weight: 500;">Klik atau drag file</p>
                    </div>
                    <input type="file" name="bukti_dp" id="bukti_dp" class="hidden"
                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                        onchange="updateFileName('bukti_dp')">
                    <p id="bukti_dp_name" style="color: #22c55e; font-size: 0.75rem; margin-top: 0.5rem;"></p>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                <!-- Tanggal Mulai -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">
                        <i class="fas fa-calendar"></i> Tanggal Mulai Kerja
                    </label>
                    <input type="date" name="tanggal_mulai_kerja" id="tanggal_mulai_kerja"
                        style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;"
                        onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                        onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'">
                </div>

                <!-- Tanggal Selesai -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">
                        <i class="fas fa-calendar"></i> Tanggal Selesai Kerja
                    </label>
                    <input type="date" name="tanggal_selesai_kerja" id="tanggal_selesai_kerja"
                        style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;"
                        onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                        onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'">
                </div>
            </div>

            <!-- Keterangan -->
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">
                    <i class="fas fa-sticky-note"></i> Keterangan
                </label>
                <textarea name="keterangan" id="keterangan" rows="3"
                    style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; font-family: inherit;"
                    placeholder="Catatan tambahan..."
                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"></textarea>
            </div>

            <!-- Buttons -->
            <div style="display: flex; gap: 1rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                <button type="button" onclick="closeTransaksiModal()"
                    style="flex: 1; padding: 0.75rem; background-color: #e5e7eb; color: #111827; border: none; border-radius: 0.375rem; font-weight: 500; cursor: pointer; transition: all 0.2s;"
                    onmouseover="this.style.backgroundColor='#d1d5db'"
                    onmouseout="this.style.backgroundColor='#e5e7eb'">
                    Batal
                </button>
                <button type="submit" id="submitBtn"
                    style="flex: 1; padding: 0.75rem; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border: none; border-radius: 0.375rem; font-weight: 500; cursor: pointer;"
                    onclick="submitTransaksiForm(event)">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function submitTransaksiForm(e) {
        e.preventDefault();
        const transaksiId = document.getElementById('transaksiId').value;
        const form = document.getElementById('transaksiForm');
        const formData = new FormData(form);

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