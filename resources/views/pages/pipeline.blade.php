@extends('layout.main')
@section('title','Pipeline Management')

@section('content')
<div class="container-expanded mx-auto px-6 lg:px-8 py-8 pt-[60px] mt-4">
    
    <!-- Card -->
    <div style="background-color: #ffffff; border-radius: 0.5rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; overflow: hidden;">
        
        <!-- Header -->
        <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin: 0;">Pipeline Management</h3>
            <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">Track your leads, visits, proposals, and follow-ups</p>
        </div>

        <!-- Kanban Board Container -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0; min-height: 500px;">
            
            <!-- LEADS COLUMN -->
            <div style="border-right: 1px solid #e5e7eb;">
                <!-- Header -->
                <div style="padding: 1rem; font-weight: 600; color: #374151; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                    <span><i class="fas fa-user-plus"></i> Leads</span>
                    <span style="background-color: rgba(255,255,255,0.2); padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem;">{{ $leads->count() }}</span>
                </div>
                
                <!-- Data Items -->
                <div style="max-height: 600px; overflow-y: auto;">
                    @forelse($leads as $lead)
                    <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb; cursor: pointer; transition: background-color 0.2s;" 
                         onmouseover="this.style.backgroundColor='#f9fafb'" 
                         onmouseout="this.style.backgroundColor='#ffffff'"
                         onclick="showDetail('lead', {{ $lead->id }})">
                        <h4 style="margin: 0; font-weight: 600; color: #111827; font-size: 0.9rem;">{{ $lead->name }}</h4>
                        <small style="color: #6b7280; display: block; margin-top: 0.5rem;">
                            <i class="fas fa-envelope" style="width: 14px;"></i> {{ $lead->email }}
                        </small>
                        <small style="color: #6b7280; display: block; margin-top: 0.25rem;">
                            <i class="fas fa-phone" style="width: 14px;"></i> {{ $lead->phone }}
                        </small>
                        <small style="color: #6b7280; display: block; margin-top: 0.25rem;">
                            <i class="fas fa-user" style="width: 14px;"></i> PIC: {{ $lead->pic }}
                        </small>
                        @if($lead->province)
                        <small style="color: #6b7280; display: block; margin-top: 0.25rem;">
                            <i class="fas fa-map-marker-alt" style="width: 14px;"></i> {{ $lead->province->name }}
                        </small>
                        @endif
                        <div style="margin-top: 0.5rem; font-size: 0.75rem; color: #9ca3af;">
                            <i class="fas fa-clock"></i> {{ $lead->created_at->diffForHumans() }}
                        </div>
                    </div>
                    @empty
                    <div style="padding: 2rem; text-align: center; color: #9ca3af;">
                        <i class="fas fa-inbox" style="font-size: 2rem; opacity: 0.3;"></i>
                        <p style="margin-top: 0.5rem; font-size: 0.875rem;">Tidak ada leads</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- VISIT COLUMN -->
            <div style="border-right: 1px solid #e5e7eb;">
                <!-- Header -->
                <div style="padding: 1rem; font-weight: 600; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                    <span><i class="fas fa-handshake"></i> Visit</span>
                    <span style="background-color: rgba(255,255,255,0.2); padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem;">{{ $visits->count() }}</span>
                </div>
                
                <!-- Data Items -->
                <div style="max-height: 600px; overflow-y: auto;">
                    @forelse($visits as $visit)
                    <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb; cursor: pointer; transition: background-color 0.2s;" 
                         onmouseover="this.style.backgroundColor='#f9fafb'" 
                         onmouseout="this.style.backgroundColor='#ffffff'"
                         onclick="showDetail('visit', {{ $visit->id }})">
                        <h4 style="margin: 0; font-weight: 600; color: #111827; font-size: 0.9rem;">
                            {{ $visit->company->company_name ?? 'No Company' }}
                        </h4>
                        <small style="color: #6b7280; display: block; margin-top: 0.5rem;">
                            <i class="fas fa-user" style="width: 14px;"></i> PIC: {{ $visit->pic_name }}
                        </small>
                        <small style="color: #6b7280; display: block; margin-top: 0.25rem;">
                            <i class="fas fa-user-tie" style="width: 14px;"></i> Sales: {{ $visit->sales->username ?? '-' }}
                        </small>
                        @if($visit->province)
                        <small style="color: #6b7280; display: block; margin-top: 0.25rem;">
                            <i class="fas fa-map-marker-alt" style="width: 14px;"></i> {{ $visit->province->name }}
                        </small>
                        @endif
                        <small style="color: #6b7280; display: block; margin-top: 0.25rem;">
                            <i class="fas fa-calendar" style="width: 14px;"></i> {{ $visit->visit_date->format('d M Y') }}
                        </small>
                        @if($visit->is_follow_up)
                        <span style="display: inline-block; margin-top: 0.5rem; background-color: #fef3c7; color: #92400e; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.7rem; font-weight: 600;">
                            Follow Up
                        </span>
                        @endif
                    </div>
                    @empty
                    <div style="padding: 2rem; text-align: center; color: #9ca3af;">
                        <i class="fas fa-inbox" style="font-size: 2rem; opacity: 0.3;"></i>
                        <p style="margin-top: 0.5rem; font-size: 0.875rem;">Tidak ada visit</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- PENAWARAN COLUMN -->
            <div style="border-right: 1px solid #e5e7eb;">
                <!-- Header -->
                <div style="padding: 1rem; font-weight: 600; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                    <span><i class="fas fa-file-invoice-dollar"></i> Penawaran</span>
                    <span style="background-color: rgba(255,255,255,0.2); padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem;">{{ $penawaran->count() }}</span>
                </div>
                
                <!-- Data Items -->
                <div style="max-height: 600px; overflow-y: auto;">
                    @forelse($penawaran as $item)
                    <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb; cursor: pointer; transition: background-color 0.2s;" 
                         onmouseover="this.style.backgroundColor='#f9fafb'" 
                         onmouseout="this.style.backgroundColor='#ffffff'"
                         onclick="showDetail('penawaran', {{ $item->id }})">
                        <h4 style="margin: 0; font-weight: 600; color: #111827; font-size: 0.9rem;">{{ $item->nama_perusahaan }}</h4>
                        <small style="color: #6b7280; display: block; margin-top: 0.5rem;">
                            <i class="fas fa-user" style="width: 14px;"></i> PIC: {{ $item->pic_name ?? '-' }}
                        </small>
                        <small style="color: #6b7280; display: block; margin-top: 0.25rem;">
                            <i class="fas fa-user-tie" style="width: 14px;"></i> Sales: {{ $item->nama_sales }}
                        </small>
                        <div style="margin-top: 0.5rem; font-weight: 600; color: #059669; font-size: 0.85rem;">
                            <i class="fas fa-money-bill-wave"></i> Rp {{ number_format($item->nilai_proyek, 0, ',', '.') }}
                        </div>
                        <span style="display: inline-block; margin-top: 0.5rem; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.7rem; font-weight: 600; {{ $item->status == 'Deals' ? 'background-color: #d1fae5; color: #065f46;' : 'background-color: #fee2e2; color: #991b1b;' }}">
                            {{ $item->status }}
                        </span>
                        <div style="margin-top: 0.5rem; font-size: 0.75rem; color: #9ca3af;">
                            <i class="fas fa-clock"></i> {{ $item->created_at->diffForHumans() }}
                        </div>
                    </div>
                    @empty
                    <div style="padding: 2rem; text-align: center; color: #9ca3af;">
                        <i class="fas fa-inbox" style="font-size: 2rem; opacity: 0.3;"></i>
                        <p style="margin-top: 0.5rem; font-size: 0.875rem;">Tidak ada penawaran</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- FOLLOW UP COLUMN -->
            <div>
                <!-- Header -->
                <div style="padding: 1rem; font-weight: 600; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                    <span><i class="fas fa-redo"></i> Follow Up</span>
                    <span style="background-color: rgba(255,255,255,0.2); padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem;">{{ $followUps->count() }}</span>
                </div>
                
                <!-- Data Items -->
                <div style="max-height: 600px; overflow-y: auto;">
                    @forelse($followUps as $followUp)
                    <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb; cursor: pointer; transition: background-color 0.2s;" 
                         onmouseover="this.style.backgroundColor='#f9fafb'" 
                         onmouseout="this.style.backgroundColor='#ffffff'"
                         onclick="showDetail('followup', {{ $followUp->id }})">
                        <h4 style="margin: 0; font-weight: 600; color: #111827; font-size: 0.9rem;">
                            {{ $followUp->company->company_name ?? 'No Company' }}
                        </h4>
                        <small style="color: #6b7280; display: block; margin-top: 0.5rem;">
                            <i class="fas fa-user" style="width: 14px;"></i> PIC: {{ $followUp->pic_name }}
                        </small>
                        <small style="color: #6b7280; display: block; margin-top: 0.25rem;">
                            <i class="fas fa-user-tie" style="width: 14px;"></i> Sales: {{ $followUp->sales->username ?? '-' }}
                        </small>
                        @if($followUp->province)
                        <small style="color: #6b7280; display: block; margin-top: 0.25rem;">
                            <i class="fas fa-map-marker-alt" style="width: 14px;"></i> {{ $followUp->province->name }}
                        </small>
                        @endif
                        <small style="color: #6b7280; display: block; margin-top: 0.25rem;">
                            <i class="fas fa-calendar" style="width: 14px;"></i> {{ $followUp->visit_date->format('d M Y') }}
                        </small>
                        <small style="color: #6b7280; display: block; margin-top: 0.25rem; font-size: 0.75rem;">
                            {{ \Illuminate\Support\Str::limit($followUp->visit_purpose, 50) }}
                        </small>
                    </div>
                    @empty
                    <div style="padding: 2rem; text-align: center; color: #9ca3af;">
                        <i class="fas fa-inbox" style="font-size: 2rem; opacity: 0.3;"></i>
                        <p style="margin-top: 0.5rem; font-size: 0.875rem;">Tidak ada follow up</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailModalBody">
                <div style="text-align: center; padding: 3rem;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showDetail(type, id) {
    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
    const routes = {
        'lead': '/pipeline/lead/' + id,
        'visit': '/pipeline/visit/' + id,
        'penawaran': '/pipeline/penawaran/' + id,
        'followup': '/pipeline/follow-up/' + id
    };
    
    modal.show();
    
    fetch(routes[type])
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderDetail(data.type, data.data);
            }
        })
        .catch(error => {
            document.getElementById('detailModalBody').innerHTML = 
                '<div class="alert alert-danger">Error loading data</div>';
        });
}

function renderDetail(type, data) {
    let html = '';
    
    if (type === 'lead') {
        html = `
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-4">
                        <label class="form-label text-muted small">NAMA</label>
                        <h4 class="fw-bold mb-0">${data.nama}</h4>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">EMAIL</label>
                            <p class="mb-0"><a href="mailto:${data.email}">${data.email}</a></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">TELEPON</label>
                            <p class="mb-0"><a href="tel:${data.phone}">${data.phone}</a></p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small">PIC</label>
                        <p class="mb-0">${data.pic}</p>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small">ALAMAT</label>
                        <p class="mb-0">${data.address || '-'}</p>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small">LOKASI</label>
                        <p class="mb-0">${data.location}</p>
                    </div>

                    ${data.notes ? `
                    <div class="mb-4">
                        <label class="form-label text-muted small">CATATAN</label>
                        <p class="mb-0">${data.notes}</p>
                    </div>
                    ` : ''}
                </div>

                <div class="col-md-4">
                    <div class="card" style="background-color: #f9fafb; border: 1px solid #e5e7eb;">
                        <div class="card-body">
                            <h6 class="card-title mb-3"><i class="fas fa-info-circle"></i> Informasi</h6>
                            <small class="d-block mb-2"><strong>Status:</strong> ${data.status}</small>
                            ${data.source ? `<small class="d-block mb-2"><strong>Source:</strong> ${data.source}</small>` : ''}
                            <small class="d-block mb-2"><strong>Dibuat:</strong> ${data.created_at}</small>
                            <small class="d-block"><strong>Oleh:</strong> ${data.created_by}</small>
                        </div>
                    </div>
                </div>
            </div>
        `;
    } else if (type === 'visit' || type === 'followup') {
        html = `
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-4">
                        <label class="form-label text-muted small">PERUSAHAAN</label>
                        <h4 class="fw-bold mb-0">${data.company}</h4>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">PIC</label>
                            <p class="mb-0">${data.pic_name}</p>
                            <small class="text-muted">${data.pic_position}</small><br>
                            <small>${data.pic_phone} | ${data.pic_email}</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">SALES</label>
                            <p class="mb-0">${data.sales_name}</p>
                            <small>${data.sales_email}</small>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small">TANGGAL VISIT</label>
                        <p class="mb-0"><i class="fas fa-calendar"></i> ${data.visit_date}</p>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small">LOKASI</label>
                        <p class="mb-0">${data.location}</p>
                        <small class="text-muted">${data.address}</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small">TUJUAN KUNJUNGAN</label>
                        <p class="mb-0">${data.visit_purpose}</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card" style="background-color: #f9fafb; border: 1px solid #e5e7eb;">
                        <div class="card-body">
                            <h6 class="card-title mb-3"><i class="fas fa-info-circle"></i> Informasi</h6>
                            <small class="d-block mb-2"><strong>Follow Up:</strong> ${data.is_follow_up ? 'Ya' : 'Tidak'}</small>
                            <small class="d-block"><strong>Dibuat:</strong> ${data.created_at}</small>
                        </div>
                    </div>
                </div>
            </div>
        `;
    } else if (type === 'penawaran') {
        html = `
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-4">
                        <label class="form-label text-muted small">PERUSAHAAN</label>
                        <h4 class="fw-bold mb-0">${data.nama_perusahaan}</h4>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">PIC</label>
                            <p class="mb-0">${data.pic_name}</p>
                            <small>${data.pic_phone} | ${data.pic_email}</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">SALES</label>
                            <p class="mb-0">${data.nama_sales}</p>
                            <small>${data.sales_email}</small>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small">NILAI PROYEK</label>
                        <h4 class="fw-bold mb-0" style="color: #10b981;">${data.nilai_proyek}</h4>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">MULAI KERJA</label>
                            <p class="mb-0">${data.tanggal_mulai_kerja}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">SELESAI KERJA</label>
                            <p class="mb-0">${data.tanggal_selesai_kerja}</p>
                        </div>
                    </div>

                    ${data.keterangan !== '-' ? `
                    <div class="mb-4">
                        <label class="form-label text-muted small">KETERANGAN</label>
                        <p class="mb-0">${data.keterangan}</p>
                    </div>
                    ` : ''}
                </div>

                <div class="col-md-4">
                    <div class="card" style="background-color: #f9fafb; border: 1px solid #e5e7eb;">
                        <div class="card-body">
                            <h6 class="card-title mb-3"><i class="fas fa-info-circle"></i> Informasi</h6>
                            <small class="d-block mb-2"><strong>Status:</strong> 
                                <span class="badge ${data.status == 'Deals' ? 'bg-success' : 'bg-danger'}">${data.status}</span>
                            </small>
                            <small class="d-block"><strong>Dibuat:</strong> ${data.created_at}</small>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    document.getElementById('detailModalBody').innerHTML = html;
}
</script>

@endsection