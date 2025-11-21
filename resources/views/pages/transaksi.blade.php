@extends('layout.main')

@section('content')
<div class="container-fluid mt-12" style="padding: 1rem;">
    
    <!-- Success Message -->
    @if($message = Session::get('success'))
    <div style="background-color: #dcfce7; border: 1px solid #86efac; border-radius: 0.375rem; padding: 0.875rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.75rem; animation: slideDown 0.3s ease-out;">
        <i class="fas fa-check-circle" style="color: #22c55e; font-size: 1rem;"></i>
        <span style="color: #166534; font-weight: 500; font-size: 0.875rem;">{{ $message }}</span>
        <button onclick="this.parentElement.style.display='none'" style="margin-left: auto; background: none; border: none; color: #166534; cursor: pointer; font-size: 1.25rem;">×</button>
    </div>
    @endif

    <!-- KPI Cards -->
    @include('components.transaksi.kpi', ['transaksi' => $transaksi])
        
    <!-- Main Card -->
    <div style="background-color: white; border-radius: 0.375rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; overflow: hidden;">
        
        <!-- Header -->
        <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h3 style="font-size: 1rem; font-weight: 600; color: #111827; margin: 0;">Manajemen Transaksi</h3>
                <p style="font-size: 0.75rem; color: #6b7280; margin: 0.25rem 0 0 0;">Kelola transaksi deals dan fails</p>
            </div>
            @if(auth()->user()->canAccess($currentMenuId ?? 17, 'create'))
            <button onclick="openTransaksiModal()"
                style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.875rem; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border: none; border-radius: 0.375rem; font-weight: 500; font-size: 0.75rem; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2);"
                onmouseover="this.style.boxShadow='0 10px 15px rgba(59, 130, 246, 0.3)'; this.style.transform='translateY(-2px)'"
                onmouseout="this.style.boxShadow='0 4px 6px rgba(59, 130, 246, 0.2)'; this.style.transform='translateY(0)'">
                <i class="fas fa-plus"></i>
                <span>Tambah Transaksi</span>
            </button>
            @endif
        </div>

        <!-- Search Filter -->
        <div style="padding: 0.75rem 1rem; background-color: white; border-bottom: 1px solid #e5e7eb;">
            <form action="{{ route('transaksi.search') }}" method="GET" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <input type="text" name="search" placeholder="Cari nama sales, perusahaan, atau status..."
                    value="{{ request('search', '') }}"
                    style="flex: 1; min-width: 200px; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.75rem; transition: all 0.2s;"
                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'">
                <button type="submit"
                    style="padding: 0.5rem 0.875rem; background-color: #3b82f6; color: white; border: none; border-radius: 0.375rem; font-weight: 500; font-size: 0.75rem; cursor: pointer; transition: all 0.2s;"
                    onmouseover="this.style.backgroundColor='#2563eb'"
                    onmouseout="this.style.backgroundColor='#3b82f6'">
                    <i class="fas fa-search"></i> Cari
                </button>
            </form>
        </div>

        <!-- Table -->
        @include('components.transaksi.table', ['transaksi' => $transaksi, 'currentMenuId' => $currentMenuId])
    </div>
</div>

<!-- Modal Form Create/Edit -->
@include('components.transaksi.modal-form', ['sales' => $sales, 'companies' => $companies, 'salesVisits' => $salesVisits])

<!-- Modal Detail -->
@include('components.transaksi.modal-detail')

<style>
    @keyframes slideDown {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    
    .modal {
        display: none;
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 50;
        align-items: center;
        justify-content: center;
        overflow-y: auto;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background-color: white;
        border-radius: 0.375rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        max-width: 1200px;
        width: 95%;
        max-height: 90vh;
        overflow: hidden;
        animation: slideUp 0.3s ease-out;
        display: flex;
        flex-direction: column;
    }

    @keyframes slideUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>

<script>
    // ✅ PERBAIKAN: Function untuk membuka modal dan reset form
    function openTransaksiModal() {
        // Reset form
        document.getElementById('transaksiForm').reset();
        document.getElementById('transaksiFormRight').reset();
        
        // Reset hidden inputs
        document.getElementById('transaksiId').value = '';
        document.getElementById('formMethod').value = 'POST';
        
        // Reset file names
        document.getElementById('bukti_spk_name').textContent = '';
        document.getElementById('bukti_dp_name').textContent = '';
        
        // Reset modal title dan buttons
        document.getElementById('modalTitle').textContent = 'Tambah Transaksi Baru';
        document.getElementById('submitBtn').innerHTML = '<i class="fas fa-check"></i> Simpan Deals';
        document.getElementById('submitBtnFail').innerHTML = '<i class="fas fa-times"></i> Simpan Fails';
        
        // Reset UI elements
        document.getElementById('companyInfoBox').style.display = 'none';
        document.getElementById('picInfoBox').style.display = 'none';
        document.getElementById('noPicBox').style.display = 'block';
        document.getElementById('pic_id').innerHTML = '<option value="">-- Pilih Company terlebih dahulu --</option>';
        
        // Show modal
        document.getElementById('transaksiModal').classList.add('active');
    }

    function closeTransaksiModal() {
        document.getElementById('transaksiModal').classList.remove('active');
    }

    // ✅ Load PIC dari Company yang dipilih
    function loadPicsForCompany() {
        const companyId = document.getElementById('company_id').value;
        const picSelect = document.getElementById('pic_id');
        
        if (!companyId) {
            picSelect.innerHTML = '<option value="">-- Pilih Company terlebih dahulu --</option>';
            document.getElementById('noPicBox').style.display = 'block';
            document.getElementById('picInfoBox').style.display = 'none';
            return;
        }

        fetch(`/transaksi/pics/by-company/${companyId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.pics.length > 0) {
                    let options = '<option value="">-- Pilih PIC --</option>';
                    data.pics.forEach(pic => {
                        options += `<option value="${pic.pic_id}" 
                            data-pic-name="${pic.pic_name}" 
                            data-pic-position="${pic.position || ''}"
                            data-pic-email="${pic.email || ''}"
                            data-pic-phone="${pic.phone || ''}">${pic.pic_name}</option>`;
                    });
                    picSelect.innerHTML = options;
                    document.getElementById('noPicBox').style.display = 'block';
                    document.getElementById('picInfoBox').style.display = 'none';
                } else {
                    picSelect.innerHTML = '<option value="">Tidak ada PIC untuk company ini</option>';
                    document.getElementById('noPicBox').style.display = 'block';
                    document.getElementById('picInfoBox').style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error loading PICs:', error);
                picSelect.innerHTML = '<option value="">Error loading PICs</option>';
            });
    }

    // ✅ Handle PIC selection
    function handlePicChange() {
        const select = document.getElementById('pic_id');
        const option = select.options[select.selectedIndex];

        if (select.value) {
            const picName = option.getAttribute('data-pic-name');
            const picPosition = option.getAttribute('data-pic-position');
            const picEmail = option.getAttribute('data-pic-email');
            const picPhone = option.getAttribute('data-pic-phone');

            document.getElementById('pic_name').value = picName;
            document.getElementById('picPosition').textContent = picPosition || '-';
            document.getElementById('picEmail').textContent = picEmail || '-';
            document.getElementById('picPhone').textContent = picPhone || '-';

            document.getElementById('picInfoBox').style.display = 'block';
            document.getElementById('noPicBox').style.display = 'none';
        } else {
            document.getElementById('pic_name').value = '';
            document.getElementById('picInfoBox').style.display = 'none';
            document.getElementById('noPicBox').style.display = 'block';
        }
    }

    function updateCompanyInfo() {
        const select = document.getElementById('company_id');
        const selected = select.options[select.selectedIndex];
        
        if (selected.value) {
            document.getElementById('nama_perusahaan').value = selected.getAttribute('data-name') || '';
            document.getElementById('companyType').textContent = selected.getAttribute('data-type') || '-';
            document.getElementById('companyPhone').textContent = selected.getAttribute('data-phone') || '-';
            document.getElementById('companyEmail').textContent = selected.getAttribute('data-email') || '-';
            document.getElementById('companyAddress').textContent = selected.getAttribute('data-address') || '-';
            document.getElementById('companyInfoBox').style.display = 'block';
        } else {
            document.getElementById('companyInfoBox').style.display = 'none';
        }
    }

    function updateSalesName() {
        const select = document.getElementById('sales_id');
        const selected = select.options[select.selectedIndex];
        document.getElementById('nama_sales').value = selected.getAttribute('data-name') || '';
    }

    function updateFileName(fieldId) {
        const input = document.getElementById(fieldId);
        const nameDisplay = document.getElementById(fieldId + '_name');
        if (input.files.length > 0) {
            nameDisplay.textContent = '✓ ' + input.files[0].name;
        } else {
            nameDisplay.textContent = '';
        }
    }

    function handleDrop(e, fieldId) {
        e.preventDefault();
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            document.getElementById(fieldId).files = files;
            updateFileName(fieldId);
        }
        const dropZone = fieldId === 'bukti_spk' ? 'drop_spk' : 'drop_dp';
        document.getElementById(dropZone).style.borderColor = '#d1d5db';
        document.getElementById(dropZone).style.backgroundColor = 'white';
    }

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
        
        formData.append('pic_id', document.getElementById('pic_id').value);
        formData.append('pic_name', document.getElementById('pic_name').value);
        formData.append('nilai_proyek', document.getElementById('nilai_proyek').value);
        formData.append('status', document.getElementById('status').value);
        formData.append('tanggal_mulai_kerja', document.getElementById('tanggal_mulai_kerja').value);
        formData.append('tanggal_selesai_kerja', document.getElementById('tanggal_selesai_kerja').value);
        formData.append('keterangan', document.getElementById('keterangan').value);
        
        if (document.getElementById('bukti_spk').files.length > 0) {
            formData.append('bukti_spk', document.getElementById('bukti_spk').files[0]);
        }
        if (document.getElementById('bukti_dp').files.length > 0) {
            formData.append('bukti_dp', document.getElementById('bukti_dp').files[0]);
        }

        const url = transaksiId ? `/transaksi/${transaksiId}` : '/transaksi';
        if (transaksiId) formData.append('_method', 'PUT');

        const submitBtn = document.getElementById('submitBtn');
        const submitBtnFail = document.getElementById('submitBtnFail');
        const originalText = submitBtn.innerHTML;
        const originalTextFail = submitBtnFail.innerHTML;
        submitBtn.disabled = true;
        submitBtnFail.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        submitBtnFail.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

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
                    submitBtn.disabled = false;
                    submitBtnFail.disabled = false;
                    submitBtn.innerHTML = originalText;
                    submitBtnFail.innerHTML = originalTextFail;
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan: ' + error);
            submitBtn.disabled = false;
            submitBtnFail.disabled = false;
            submitBtn.innerHTML = originalText;
            submitBtnFail.innerHTML = originalTextFail;
        });
    }

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
                
                updateCompanyInfo();
                
                setTimeout(() => {
                    loadPicsForCompany();
                    setTimeout(() => {
                        document.getElementById('pic_id').value = data.pic_id || '';
                        handlePicChange();
                    }, 200);
                }, 100);
                
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

    function closeDetailModal() {
        document.getElementById('detailModal').classList.remove('active');
    }

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
@endsection