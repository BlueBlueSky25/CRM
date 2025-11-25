@extends('layout.main')
@section('title','Pipeline Management')

@section('content')
<style>
    .pipeline-item {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        cursor: pointer;
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }

    .pipeline-item:hover {
        background-color: #f9fafb;
        border-left-color: #3b82f6;
        transform: translateX(4px);
    }

    .detail-label {
        font-size: 0.7rem;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 0.25rem;
        display: block;
    }

    .detail-value {
        font-size: 0.9rem;
        color: #111827;
        margin-bottom: 0.75rem;
    }

    .detail-info-box {
        background-color: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1rem;
    }

    .badge-status {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-success { background-color: #d1fae5; color: #065f46; }
    .badge-danger { background-color: #fee2e2; color: #991b1b; }
    .badge-warning { background-color: #fef3c7; color: #92400e; }
</style>

<div class="container-expanded mx-auto px-6 lg:px-8 py-8 pt-[60px] mt-4">
    <div style="background:#fff;border-radius:0.5rem;box-shadow:0 1px 3px rgba(0,0,0,0.1);border:1px solid #e5e7eb;overflow:hidden;">
        <div style="padding:1.5rem;border-bottom:1px solid #e5e7eb;">
            <h3 style="font-size:1.125rem;font-weight:600;color:#111827;margin:0;">Pipeline Management</h3>
            <p style="font-size:0.875rem;color:#6b7280;margin-top:0.25rem;">Track your leads, visits, proposals, and follow-ups</p>
        </div>

        <div style="display:grid;grid-template-columns:repeat(4,1fr);min-height:500px;">
            {{-- LEADS --}}
            <div style="border-right:1px solid #e5e7eb;">
                <div style="padding:1rem;font-weight:600;background:linear-gradient(135deg,#3b82f6,#2563eb);color:#fff;display:flex;justify-content:space-between;">
                    <span><i class="fas fa-user-plus"></i> Leads</span>
                    <span style="background:rgba(255,255,255,0.2);padding:0.25rem 0.5rem;border-radius:0.25rem;font-size:0.75rem;">{{ $leads->count() }}</span>
                </div>
                <div style="max-height:600px;overflow-y:auto;">
                    @forelse($leads as $lead)
                    <div class="pipeline-item" onclick="openModal('lead', {{ $lead->id }})">
                        <h4 style="margin:0;font-weight:600;color:#111827;font-size:0.9rem;">{{ $lead->name }}</h4>
                        <small style="color:#6b7280;display:block;margin-top:0.5rem;">
                            <i class="fas fa-envelope" style="width:14px;"></i> {{ $lead->email }}
                        </small>
                        <small style="color:#6b7280;display:block;margin-top:0.25rem;">
                            <i class="fas fa-phone" style="width:14px;"></i> {{ $lead->phone }}
                        </small>
                        <div style="margin-top:0.5rem;font-size:0.75rem;color:#9ca3af;">
                            <i class="fas fa-clock"></i> {{ $lead->created_at->diffForHumans() }}
                        </div>
                    </div>
                    @empty
                    <div style="padding:2rem;text-align:center;color:#9ca3af;">Tidak ada leads</div>
                    @endforelse
                </div>
            </div>

            {{-- VISIT --}}
            <div style="border-right:1px solid #e5e7eb;">
                <div style="padding:1rem;font-weight:600;background:linear-gradient(135deg,#10b981,#059669);color:#fff;display:flex;justify-content:space-between;">
                    <span><i class="fas fa-handshake"></i> Visit</span>
                    <span style="background:rgba(255,255,255,0.2);padding:0.25rem 0.5rem;border-radius:0.25rem;font-size:0.75rem;">{{ $visits->count() }}</span>
                </div>
                <div style="max-height:600px;overflow-y:auto;">
                    @forelse($visits as $visit)
                    <div class="pipeline-item" onclick="openModal('visit', {{ $visit->id }})">
                        <h4 style="margin:0;font-weight:600;color:#111827;font-size:0.9rem;">{{ $visit->company->company_name ?? '-' }}</h4>
                        <small style="color:#6b7280;display:block;margin-top:0.5rem;">
                            <i class="fas fa-user" style="width:14px;"></i> PIC: {{ $visit->pic_name }}
                        </small>
                        <small style="color:#6b7280;display:block;margin-top:0.25rem;">
                            <i class="fas fa-calendar" style="width:14px;"></i> {{ $visit->visit_date->format('d M Y') }}
                        </small>
                    </div>
                    @empty
                    <div style="padding:2rem;text-align:center;color:#9ca3af;">Tidak ada visit</div>
                    @endforelse
                </div>
            </div>

            {{-- PENAWARAN --}}
            <div style="border-right:1px solid #e5e7eb;">
                <div style="padding:1rem;font-weight:600;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;display:flex;justify-content:space-between;">
                    <span><i class="fas fa-file-invoice-dollar"></i> Penawaran</span>
                    <span style="background:rgba(255,255,255,0.2);padding:0.25rem 0.5rem;border-radius:0.25rem;font-size:0.75rem;">{{ $penawaran->count() }}</span>
                </div>
                <div style="max-height:600px;overflow-y:auto;">
                    @forelse($penawaran as $item)
                    <div class="pipeline-item" onclick="openModal('penawaran', {{ $item->id }})">
                        <h4 style="margin:0;font-weight:600;color:#111827;font-size:0.9rem;">{{ $item->nama_perusahaan }}</h4>
                        <small style="color:#6b7280;display:block;margin-top:0.5rem;">
                            <i class="fas fa-money-bill-wave"></i> Rp {{ number_format($item->nilai_proyek,0,',','.') }}
                        </small>
                        <span class="badge-status {{ $item->status == 'Deals' ? 'badge-success' : 'badge-danger' }}" style="margin-top:0.5rem;">
                            {{ $item->status }}
                        </span>
                    </div>
                    @empty
                    <div style="padding:2rem;text-align:center;color:#9ca3af;">Tidak ada penawaran</div>
                    @endforelse
                </div>
            </div>

            {{-- FOLLOW UP --}}
            <div>
                <div style="padding:1rem;font-weight:600;background:linear-gradient(135deg,#8b5cf6,#7c3aed);color:#fff;display:flex;justify-content:space-between;">
                    <span><i class="fas fa-redo"></i> Follow Up</span>
                    <span style="background:rgba(255,255,255,0.2);padding:0.25rem 0.5rem;border-radius:0.25rem;font-size:0.75rem;">{{ $followUps->count() }}</span>
                </div>
                <div style="max-height:600px;overflow-y:auto;">
                    @forelse($followUps as $followUp)
                    <div class="pipeline-item" onclick="openModal('followup', {{ $followUp->id }})">
                        <h4 style="margin:0;font-weight:600;color:#111827;font-size:0.9rem;">{{ optional($followUp->company)->company_name ?? '-' }}</h4>
                        <small style="color:#6b7280;display:block;margin-top:0.5rem;">
                            <i class="fas fa-user" style="width:14px;"></i> PIC: {{ $followUp->pic_name }}
                        </small>
                        <small style="color:#6b7280;display:block;margin-top:0.25rem;">
                            <i class="fas fa-calendar" style="width:14px;"></i> {{ $followUp->visit_date->format('d M Y') }}
                        </small>
                    </div>
                    @empty
                    <div style="padding:2rem;text-align:center;color:#9ca3af;">Tidak ada follow up</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL SIMPLE - TANPA LOADING, TANPA ABU-ABU --}}
<div id="detailModal" 
     style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; z-index:9999; background:rgba(0,0,0,0.5);"
     onclick="closeModal(event)">
    
    <div style="display:flex; align-items:center; justify-content:center; min-height:100vh; padding:1rem;">
        <div onclick="event.stopPropagation()" 
             style="background:white; border-radius:0.5rem; max-width:1000px; width:100%; max-height:90vh; overflow-y:auto; box-shadow:0 20px 25px -5px rgba(0,0,0,0.3);">
            
            {{-- Header --}}
            <div style="background:linear-gradient(135deg,#3b82f6,#2563eb); padding:1.5rem; display:flex; justify-content:space-between; align-items:center;">
                <h3 style="color:white; font-size:1.25rem; font-weight:600; margin:0;">Detail</h3>
                <button onclick="closeModal()" style="color:white; background:none; border:none; cursor:pointer; font-size:1.5rem;">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Body --}}
            <div id="modalContent" style="padding:1.5rem;"></div>
        </div>
    </div>
</div>

<script>
function openModal(type, id) {
    const routes = {
        lead: `/pipeline/lead/${id}`,
        visit: `/pipeline/visit/${id}`,
        penawaran: `/pipeline/penawaran/${id}`,
        followup: `/pipeline/follow-up/${id}`
    };
    
    const modal = document.getElementById('detailModal');
    const content = document.getElementById('modalContent');
    
    // Tampilkan modal langsung
    modal.style.display = 'block';
    content.innerHTML = '<div style="text-align:center; padding:3rem; color:#6b7280;">Memuat data...</div>';
    
    // Fetch data
    fetch(routes[type])
        .then(res => {
            if (!res.ok) throw new Error('Network error: ' + res.status);
            return res.json();
        })
        .then(data => {
            console.log('Data received:', data);
            if(data.success) {
                content.innerHTML = renderDetail(data.type, data.data);
            } else {
                content.innerHTML = showError(data.message || 'Gagal memuat data');
            }
        })
        .catch(err => {
            console.error('Error:', err);
            content.innerHTML = showError('Terjadi kesalahan: ' + err.message);
        });
}

function closeModal(event) {
    const modal = document.getElementById('detailModal');
    modal.style.display = 'none';
}

// Close dengan ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

function renderDetail(type, data) {
    if (type === 'lead') return renderLead(data);
    else if (type === 'visit' || type === 'followup') return renderVisit(data);
    else if (type === 'penawaran') return renderPenawaran(data);
}

function renderLead(d) {
    return `
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="md:col-span-2 space-y-3">
        <div>
          <span class="detail-label"><i class="fas fa-user text-blue-600"></i> Nama</span>
          <div class="text-lg font-bold text-gray-900">${h(d.nama)}</div>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <span class="detail-label"><i class="fas fa-envelope text-green-600"></i> Email</span>
            <div class="detail-value"><a href="mailto:${h(d.email)}" class="text-blue-600 hover:underline">${h(d.email)}</a></div>
          </div>
          <div>
            <span class="detail-label"><i class="fas fa-phone text-yellow-600"></i> Telepon</span>
            <div class="detail-value"><a href="tel:${h(d.phone)}" class="text-blue-600 hover:underline">${h(d.phone)}</a></div>
          </div>
        </div>
        <div>
          <span class="detail-label"><i class="fas fa-user-circle text-purple-600"></i> PIC</span>
          <div class="detail-value">${h(d.pic)}</div>
        </div>
        <div>
          <span class="detail-label"><i class="fas fa-home text-pink-600"></i> Alamat</span>
          <div class="detail-value">${h(d.address)}</div>
        </div>
        <div>
          <span class="detail-label"><i class="fas fa-map-marker-alt text-red-600"></i> Lokasi</span>
          <div class="detail-value">${h(d.location)}</div>
        </div>
        ${d.notes && d.notes !== '-' ? `
        <div>
          <span class="detail-label"><i class="fas fa-sticky-note text-teal-600"></i> Catatan</span>
          <div class="bg-green-50 border-l-4 border-green-500 p-2 rounded text-sm">${h(d.notes)}</div>
        </div>
        ` : ''}
      </div>
      <div>
        <div class="detail-info-box">
          <h6 class="font-semibold mb-3 text-gray-900 text-sm"><i class="fas fa-info-circle text-blue-600"></i> Informasi</h6>
          <div class="space-y-3">
            <div class="pb-3 border-b">
              <small class="text-gray-600 font-medium block text-xs">Status</small>
              <span class="badge-status badge-warning mt-1">${h(d.status)}</span>
            </div>
            ${d.source && d.source !== '-' ? `
            <div class="pb-3 border-b">
              <small class="text-gray-600 font-medium block text-xs">Source</small>
              <small class="text-gray-900 block mt-1 text-sm">${h(d.source)}</small>
            </div>
            ` : ''}
            <div class="pb-3 border-b">
              <small class="text-gray-600 font-medium block text-xs">Dibuat</small>
              <small class="text-gray-900 block mt-1 text-sm">${h(d.created_at)}</small>
            </div>
            <div>
              <small class="text-gray-600 font-medium block text-xs">Oleh</small>
              <small class="text-gray-900 block mt-1 text-sm">${h(d.created_by)}</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  `;
}

function renderVisit(d) {
    return `
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="md:col-span-2 space-y-3">
        <div>
          <span class="detail-label"><i class="fas fa-building text-blue-600"></i> Perusahaan</span>
          <div class="text-lg font-bold text-gray-900">${h(d.company)}</div>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <span class="detail-label"><i class="fas fa-user text-green-600"></i> PIC</span>
            <div>
              <div class="font-semibold text-sm">${h(d.pic_name)}</div>
              <small class="text-gray-600 text-xs">${h(d.pic_position)}</small><br>
              <small class="text-gray-600 text-xs">${h(d.pic_phone)} | ${h(d.pic_email)}</small>
            </div>
          </div>
          <div>
            <span class="detail-label"><i class="fas fa-user-tie text-yellow-600"></i> Sales</span>
            <div>
              <div class="font-semibold text-sm">${h(d.sales_name)}</div>
              <small class="text-gray-600 text-xs">${h(d.sales_email)}</small>
            </div>
          </div>
        </div>
        <div>
          <span class="detail-label"><i class="fas fa-calendar text-purple-600"></i> Tanggal Visit</span>
          <div class="detail-value">${h(d.visit_date)}</div>
        </div>
        <div>
          <span class="detail-label"><i class="fas fa-map-marker-alt text-pink-600"></i> Lokasi</span>
          <div class="detail-value">${h(d.location)}<br><small class="text-gray-600 text-xs">${h(d.address)}</small></div>
        </div>
        <div>
          <span class="detail-label"><i class="fas fa-lightbulb text-yellow-600"></i> Tujuan Kunjungan</span>
          <div class="bg-gray-100 border-l-4 border-blue-600 p-2 rounded text-sm">${h(d.visit_purpose)}</div>
        </div>
      </div>
      <div>
        <div class="detail-info-box">
          <h6 class="font-semibold mb-3 text-gray-900 text-sm"><i class="fas fa-info-circle text-blue-600"></i> Informasi</h6>
          <div class="space-y-3">
            <div class="pb-3 border-b">
              <small class="text-gray-600 font-medium block text-xs">Follow Up</small>
              <span class="badge-status ${d.is_follow_up === 'Ya' ? 'badge-success' : 'badge-danger'} mt-1">${h(d.is_follow_up)}</span>
            </div>
            <div>
              <small class="text-gray-600 font-medium block text-xs">Dibuat</small>
              <small class="text-gray-900 block mt-1 text-sm">${h(d.created_at)}</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  `;
}

function renderPenawaran(d) {
    return `
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="md:col-span-2 space-y-3">
        <div>
          <span class="detail-label"><i class="fas fa-building text-blue-600"></i> Perusahaan</span>
          <div class="text-lg font-bold text-gray-900">${h(d.nama_perusahaan)}</div>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <span class="detail-label"><i class="fas fa-user text-green-600"></i> PIC</span>
            <div>
              <div class="font-semibold text-sm">${h(d.pic_name)}</div>
              <small class="text-gray-600 text-xs">${h(d.pic_phone)} | ${h(d.pic_email)}</small>
            </div>
          </div>
          <div>
            <span class="detail-label"><i class="fas fa-user-tie text-yellow-600"></i> Sales</span>
            <div>
              <div class="font-semibold text-sm">${h(d.nama_sales)}</div>
              <small class="text-gray-600 text-xs">${h(d.sales_email)}</small>
            </div>
          </div>
        </div>
        <div>
          <span class="detail-label"><i class="fas fa-money-bill-wave text-green-600"></i> Nilai Proyek</span>
          <div class="text-xl font-bold text-green-600">${h(d.nilai_proyek)}</div>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <span class="detail-label"><i class="fas fa-calendar-check text-blue-600"></i> Mulai Kerja</span>
            <div class="detail-value">${h(d.tanggal_mulai_kerja)}</div>
          </div>
          <div>
            <span class="detail-label"><i class="fas fa-calendar-times text-red-600"></i> Selesai Kerja</span>
            <div class="detail-value">${h(d.tanggal_selesai_kerja)}</div>
          </div>
        </div>
        ${d.keterangan && d.keterangan !== '-' ? `
        <div>
          <span class="detail-label"><i class="fas fa-sticky-note text-teal-600"></i> Keterangan</span>
          <div class="bg-green-50 border-l-4 border-green-500 p-2 rounded text-sm">${h(d.keterangan)}</div>
        </div>
        ` : ''}
      </div>
      <div>
        <div class="detail-info-box">
          <h6 class="font-semibold mb-3 text-gray-900 text-sm"><i class="fas fa-info-circle text-blue-600"></i> Informasi</h6>
          <div class="space-y-3">
            <div class="pb-3 border-b">
              <small class="text-gray-600 font-medium block text-xs">Status</small>
              <span class="badge-status ${d.status === 'Deals' ? 'badge-success' : 'badge-danger'} mt-1">${h(d.status)}</span>
            </div>
            <div>
              <small class="text-gray-600 font-medium block text-xs">Dibuat</small>
              <small class="text-gray-900 block mt-1 text-sm">${h(d.created_at)}</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  `;
}

function showError(msg) {
    return `
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
      <div class="flex items-center gap-4">
        <i class="fas fa-exclamation-circle text-red-500 text-3xl"></i>
        <div>
          <h5 class="font-semibold text-red-900">Gagal Memuat Data</h5>
          <p class="text-red-700 text-sm mt-1">${h(msg)}</p>
        </div>
      </div>
    </div>
  `;
}

function h(t) {
    if (!t) return '';
    const m = {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'};
    return String(t).replace(/[&<>"']/g, c => m[c]);
}
</script>

<style>
[x-cloak] { display: none !important; }
</style>

@endsection