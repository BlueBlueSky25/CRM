@extends('layout.main')

@section('content')
<div class="container-fluid mt-12" style="padding: 1.5rem;">
    
    <!-- Success Message -->
    @if($message = Session::get('success'))
    <div style="background-color: #dcfce7; border: 1px solid #86efac; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; animation: slideDown 0.3s ease-out;">
        <i class="fas fa-check-circle" style="color: #22c55e; font-size: 1.25rem;"></i>
        <span style="color: #166534; font-weight: 500;">{{ $message }}</span>
        <button onclick="this.parentElement.style.display='none'" style="margin-left: auto; background: none; border: none; color: #166534; cursor: pointer; font-size: 1.25rem;">×</button>
    </div>
    @endif

    <!-- Main Card -->
    <div style="background-color: white; border-radius: 0.5rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; overflow: hidden;">
        
        <!-- Header -->
        <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin: 0;">Manajemen Transaksi</h3>
                <p style="font-size: 0.875rem; color: #6b7280; margin: 0.25rem 0 0 0;">Kelola transaksi deals dan fails dari sales visit</p>
            </div>
            @if(auth()->user()->canAccess($currentMenuId ?? 17, 'create'))
            <button onclick="openTransaksiModal()"
                style="display: flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1rem; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border: none; border-radius: 0.5rem; font-weight: 500; font-size: 0.875rem; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);"
                onmouseover="this.style.boxShadow='0 10px 15px rgba(37, 99, 235, 0.3)'; this.style.transform='translateY(-2px)'"
                onmouseout="this.style.boxShadow='0 4px 6px rgba(37, 99, 235, 0.2)'; this.style.transform='translateY(0)'">
                <i class="fas fa-plus"></i>
                <span>Tambah Transaksi</span>
            </button>
            @endif
        </div>

        <!-- KPI Cards -->
        @include('components.transaksi.kpi', ['transaksi' => $transaksi])

        <!-- Search Filter -->
        <div style="padding: 1rem 1.5rem; background-color: white; border-bottom: 1px solid #e5e7eb;">
            <form action="{{ route('transaksi.search') }}" method="GET" style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                <input type="text" name="search" placeholder="Cari nama sales, perusahaan, atau status..."
                    value="{{ request('search', '') }}"
                    style="flex: 1; min-width: 200px; padding: 0.625rem 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; transition: all 0.2s;"
                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'">
                <button type="submit"
                    style="padding: 0.625rem 1rem; background-color: #3b82f6; color: white; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer; transition: all 0.2s;"
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
        border-radius: 0.5rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        animation: slideUp 0.3s ease-out;
    }

    @keyframes slideUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>

<script>
    function openTransaksiModal() {
        document.getElementById('transaksiModal').classList.add('active');
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('transaksiForm').reset();
        document.getElementById('modalTitle').textContent = 'Tambah Transaksi Baru';
        document.getElementById('transaksiId').value = '';
        document.getElementById('submitBtn').textContent = 'Simpan Transaksi';
        
        // Reset file names
        document.getElementById('bukti_spk_name').textContent = '';
        document.getElementById('bukti_dp_name').textContent = '';
    }

    function closeTransaksiModal() {
        document.getElementById('transaksiModal').classList.remove('active');
    }

    function editTransaksi(id) {
        fetch(`/transaksi/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('transaksiId').value = data.id;
                document.getElementById('sales_id').value = data.sales_id;
                document.getElementById('company_id').value = data.company_id;
                document.getElementById('nama_sales').value = data.nama_sales;
                document.getElementById('nama_perusahaan').value = data.nama_perusahaan;
                document.getElementById('nilai_proyek').value = data.nilai_proyek;
                document.getElementById('status').value = data.status;
                document.getElementById('tanggal_mulai_kerja').value = data.tanggal_mulai_kerja;
                document.getElementById('tanggal_selesai_kerja').value = data.tanggal_selesai_kerja;
                document.getElementById('keterangan').value = data.keterangan;
                document.getElementById('sales_visit_id').value = data.sales_visit_id;
                
                document.getElementById('formMethod').value = 'PUT';
                document.getElementById('modalTitle').textContent = 'Edit Transaksi';
                document.getElementById('submitBtn').textContent = 'Update Transaksi';
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
                let html = `
                    <div style="padding: 1.5rem;">
                        <div style="margin-bottom: 1.5rem;">
                            <p style="font-size: 0.75rem; color: #6b7280; font-weight: 600; text-transform: uppercase; margin: 0;">Nama Sales</p>
                            <p style="color: #111827; font-weight: 500; margin: 0.25rem 0 0 0;">${data.nama_sales}</p>
                        </div>
                        <div style="margin-bottom: 1.5rem;">
                            <p style="font-size: 0.75rem; color: #6b7280; font-weight: 600; text-transform: uppercase; margin: 0;">Perusahaan</p>
                            <p style="color: #111827; font-weight: 500; margin: 0.25rem 0 0 0;">${data.nama_perusahaan}</p>
                        </div>
                        <div style="margin-bottom: 1.5rem;">
                            <p style="font-size: 0.75rem; color: #6b7280; font-weight: 600; text-transform: uppercase; margin: 0;">Nilai Proyek</p>
                            <p style="color: #111827; font-weight: 500; margin: 0.25rem 0 0 0;">Rp${new Intl.NumberFormat('id-ID').format(data.nilai_proyek)}</p>
                        </div>
                        <div style="margin-bottom: 1.5rem;">
                            <p style="font-size: 0.75rem; color: #6b7280; font-weight: 600; text-transform: uppercase; margin: 0;">Status</p>
                            <p style="margin: 0.25rem 0 0 0;">
                                ${data.status === 'Deals' 
                                    ? '<span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.375rem 0.75rem; background-color: #dcfce7; color: #166534; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;"><i class="fas fa-check-circle"></i> Deals</span>'
                                    : '<span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.375rem 0.75rem; background-color: #fee2e2; color: #991b1b; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;"><i class="fas fa-times-circle"></i> Fails</span>'
                                }
                            </p>
                        </div>
                        ${data.tanggal_mulai_kerja ? `
                        <div style="margin-bottom: 1.5rem;">
                            <p style="font-size: 0.75rem; color: #6b7280; font-weight: 600; text-transform: uppercase; margin: 0;">Tanggal Kerja</p>
                            <p style="color: #111827; font-weight: 500; margin: 0.25rem 0 0 0;">${data.tanggal_mulai_kerja} s/d ${data.tanggal_selesai_kerja || '-'}</p>
                        </div>
                        ` : ''}
                        ${data.keterangan ? `
                        <div style="margin-bottom: 1.5rem;">
                            <p style="font-size: 0.75rem; color: #6b7280; font-weight: 600; text-transform: uppercase; margin: 0;">Keterangan</p>
                            <p style="color: #111827; margin: 0.25rem 0 0 0;">${data.keterangan}</p>
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

    function updateSalesName() {
        const select = document.getElementById('sales_id');
        const selected = select.options[select.selectedIndex];
        document.getElementById('nama_sales').value = selected.getAttribute('data-name') || '';
    }

    function updateCompanyName() {
        const select = document.getElementById('company_id');
        const selected = select.options[select.selectedIndex];
        document.getElementById('nama_perusahaan').value = selected.getAttribute('data-name') || '';
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
    }

    // Close modal when clicking outside
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