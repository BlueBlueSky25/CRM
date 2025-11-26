@extends('layout.main')
@section('title','Pipeline Management')

@section('content')
<style>
    .pipeline-item {
        padding: 0.75rem;
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
        font-size: 0.65rem;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 0.15rem;
        display: block;
        letter-spacing: 0.5px;
    }

    .detail-value {
        font-size: 0.85rem;
        color: #111827;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }

    .detail-info-box {
        background-color: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 0.75rem;
    }

    .badge-status {
        display: inline-block;
        padding: 0.25rem 0.6rem;
        border-radius: 0.375rem;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .badge-success { background-color: #d1fae5; color: #065f46; }
    .badge-danger { background-color: #fee2e2; color: #991b1b; }
    .badge-warning { background-color: #fef3c7; color: #92400e; }
    .badge-info { background-color: #dbeafe; color: #0c4a6e; }

    @media (max-width: 1024px) {
        .responsive-grid {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }
    }

    @media (max-width: 768px) {
        .responsive-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
        .pipeline-item {
            padding: 0.6rem;
        }
    }

    [x-cloak] { display: none !important; }

    #detailModal {
        scroll-behavior: smooth;
    }

    #modalContent::-webkit-scrollbar {
        width: 6px;
    }

    #modalContent::-webkit-scrollbar-track {
        background: transparent;
    }

    #modalContent::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    #modalContent::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<div class="container-expanded mx-auto px-4 lg:px-8 py-8 pt-[60px] mt-4">
    <div style="background:#fff;border-radius:0.5rem;box-shadow:0 1px 3px rgba(0,0,0,0.1);border:1px solid #e5e7eb;overflow:hidden;">
        <div style="padding:1.25rem;border-bottom:1px solid #e5e7eb;">
            <h3 style="font-size:1.125rem;font-weight:600;color:#111827;margin:0;">Pipeline Management</h3>
            <p style="font-size:0.8rem;color:#6b7280;margin-top:0.15rem;">Track your leads, visits, proposals, and follow-ups</p>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));min-height:500px;">
            <div style="border-right:1px solid #e5e7eb;">
                <div style="padding:0.875rem;font-weight:600;font-size:0.95rem;background:linear-gradient(135deg,#3b82f6,#2563eb);color:#fff;display:flex;justify-content:space-between;align-items:center;">
                    <span><i class="fas fa-user-plus"></i> Leads</span>
                    <span style="background:rgba(255,255,255,0.2);padding:0.2rem 0.45rem;border-radius:0.25rem;font-size:0.7rem;">{{ $leads->count() }}</span>
                </div>
                <div style="max-height:600px;overflow-y:auto;">
                    @forelse($leads as $lead)
                    <div class="pipeline-item" onclick="openModal('lead', {{ $lead->id }})">
                        <h4 style="margin:0;font-weight:600;color:#111827;font-size:0.85rem;line-height:1.3;">{{ $lead->name }}</h4>
                        <small style="color:#6b7280;display:block;margin-top:0.3rem;font-size:0.75rem;">
                            <i class="fas fa-envelope" style="width:12px;"></i> {{ Str::limit($lead->email, 20) }}
                        </small>
                        <small style="color:#6b7280;display:block;margin-top:0.15rem;font-size:0.75rem;">
                            <i class="fas fa-phone" style="width:12px;"></i> {{ $lead->phone }}
                        </small>
                        <div style="margin-top:0.3rem;font-size:0.7rem;color:#9ca3af;">
                            <i class="fas fa-clock"></i> {{ $lead->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                    @empty
                    <div style="padding:1.5rem;text-align:center;color:#9ca3af;font-size:0.85rem;">Tidak ada leads</div>
                    @endforelse
                </div>
            </div>

            <div style="border-right:1px solid #e5e7eb;">
                <div style="padding:0.875rem;font-weight:600;font-size:0.95rem;background:linear-gradient(135deg,#10b981,#059669);color:#fff;display:flex;justify-content:space-between;align-items:center;">
                    <span><i class="fas fa-handshake"></i> Visit</span>
                    <span style="background:rgba(255,255,255,0.2);padding:0.2rem 0.45rem;border-radius:0.25rem;font-size:0.7rem;">{{ $visits->count() }}</span>
                </div>
                <div style="max-height:600px;overflow-y:auto;">
                    @forelse($visits as $visit)
                    <div class="pipeline-item" onclick="openModal('visit', {{ $visit->id }})">
                        <h4 style="margin:0;font-weight:600;color:#111827;font-size:0.85rem;line-height:1.3;">{{ $visit->company->company_name ?? '-' }}</h4>
                        <small style="color:#6b7280;display:block;margin-top:0.3rem;font-size:0.75rem;">
                            <i class="fas fa-user" style="width:12px;"></i> {{ optional($visit->sales)->username ?? '-' }}
                        </small>
                        <small style="color:#6b7280;display:block;margin-top:0.15rem;font-size:0.75rem;">
                            <i class="fas fa-calendar" style="width:12px;"></i> {{ $visit->visit_date->format('d/m/Y') }}
                        </small>
                    </div>
                    @empty
                    <div style="padding:1.5rem;text-align:center;color:#9ca3af;font-size:0.85rem;">Tidak ada visit</div>
                    @endforelse
                </div>
            </div>

            <div style="border-right:1px solid #e5e7eb;">
                <div style="padding:0.875rem;font-weight:600;font-size:0.95rem;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;display:flex;justify-content:space-between;align-items:center;">
                    <span><i class="fas fa-file-invoice-dollar"></i> Penawaran</span>
                    <span style="background:rgba(255,255,255,0.2);padding:0.2rem 0.45rem;border-radius:0.25rem;font-size:0.7rem;">{{ $penawaran->count() }}</span>
                </div>
                <div style="max-height:600px;overflow-y:auto;">
                    @forelse($penawaran as $item)
                    <div class="pipeline-item" onclick="openModal('penawaran', {{ $item->id }})">
                        <h4 style="margin:0;font-weight:600;color:#111827;font-size:0.85rem;line-height:1.3;">{{ Str::limit($item->nama_perusahaan, 25) }}</h4>
                        <small style="color:#6b7280;display:block;margin-top:0.3rem;font-size:0.75rem;">
                            <i class="fas fa-money-bill-wave" style="width:12px;"></i> Rp {{ number_format($item->nilai_proyek,0,',','.') }}
                        </small>
                        <small style="color:#6b7280;display:block;margin-top:0.15rem;font-size:0.75rem;">
                            <i class="fas fa-user-tie" style="width:12px;"></i> {{ Str::limit($item->nama_sales, 20) }}
                        </small>
                        <span class="badge-status {{ $item->status == 'Deals' ? 'badge-success' : 'badge-danger' }}" style="margin-top:0.3rem;">
                            {{ $item->status }}
                        </span>
                    </div>
                    @empty
                    <div style="padding:1.5rem;text-align:center;color:#9ca3af;font-size:0.85rem;">Tidak ada penawaran</div>
                    @endforelse
                </div>
            </div>

            <div>
                <div style="padding:0.875rem;font-weight:600;font-size:0.95rem;background:linear-gradient(135deg,#8b5cf6,#7c3aed);color:#fff;display:flex;justify-content:space-between;align-items:center;">
                    <span><i class="fas fa-redo"></i> Follow Up</span>
                    <span style="background:rgba(255,255,255,0.2);padding:0.2rem 0.45rem;border-radius:0.25rem;font-size:0.7rem;">{{ $followUps->count() }}</span>
                </div>
                <div style="max-height:600px;overflow-y:auto;">
                    @forelse($followUps as $followUp)
                    <div class="pipeline-item" onclick="openModal('followup', {{ $followUp->id }})">
                        <h4 style="margin:0;font-weight:600;color:#111827;font-size:0.85rem;line-height:1.3;">{{ optional($followUp->company)->company_name ?? '-' }}</h4>
                        <small style="color:#6b7280;display:block;margin-top:0.3rem;font-size:0.75rem;">
                            <i class="fas fa-user" style="width:12px;"></i> {{ optional($followUp->sales)->username ?? '-' }}
                        </small>
                        <small style="color:#6b7280;display:block;margin-top:0.15rem;font-size:0.75rem;">
                            <i class="fas fa-calendar" style="width:12px;"></i> {{ $followUp->visit_date->format('d/m/Y') }}
                        </small>
                    </div>
                    @empty
                    <div style="padding:1.5rem;text-align:center;color:#9ca3af;font-size:0.85rem;">Tidak ada follow up</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div id="detailModal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; z-index:9999; background:rgba(0,0,0,0.5); overflow-y:auto;" onclick="closeModal(event)">
    <div style="display:flex; align-items:center; justify-content:center; min-height:100vh; padding:0.75rem;">
        <div onclick="event.stopPropagation()" style="background:white; border-radius:0.5rem; max-width:900px; width:100%; box-shadow:0 20px 25px -5px rgba(0,0,0,0.3);">
            <div style="background:linear-gradient(135deg,#3b82f6,#2563eb); padding:1rem; display:flex; justify-content:space-between; align-items:center; position: sticky; top: 0; z-index: 10;">
                <h3 style="color:white; font-size:1rem; font-weight:600; margin:0;">Detail</h3>
                <button onclick="closeModal()" style="color:white; background:none; border:none; cursor:pointer; font-size:1.25rem; padding: 0; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-times"></i></button>
            </div>
            <div id="modalContent" style="padding:1.25rem; max-height: calc(100vh - 100px); overflow-y: auto;"></div>
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
    
    modal.style.display = 'block';
    content.innerHTML = '<div style="text-align:center; padding:2rem; color:#6b7280;"><i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #3b82f6; margin-bottom: 0.75rem;"></i><p style="font-size:0.9rem;">Memuat data...</p></div>';
    
    fetch(routes[type])
        .then(res => {
            if (!res.ok) throw new Error('Network error: ' + res.status);
            return res.json();
        })
        .then(data => {
            if(data.success) {
                content.innerHTML = renderDetail(data.type, data.data);
            } else {
                content.innerHTML = showError(data.message || 'Gagal memuat data');
            }
        })
        .catch(err => {
            content.innerHTML = showError('Terjadi kesalahan: ' + err.message);
        });
}

function closeModal(event) {
    if (event && event.target.id !== 'detailModal') return;
    const modal = document.getElementById('detailModal');
    modal.style.display = 'none';
}

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
    <div style="display:flex;flex-direction:column;gap:0.5rem;">
      <div style="background:linear-gradient(135deg,#3b82f6,#2563eb);padding:0.75rem;border-radius:0.5rem;margin:-0.5rem -0.5rem 0;">
        <span style="font-size:0.6rem;color:rgba(255,255,255,0.8);font-weight:600;text-transform:uppercase;display:block;margin-bottom:0.1rem;"><i class="fas fa-user-plus"></i> Nama Lead</span>
        <div style="font-size:1rem;font-weight:700;color:#fff;">${h(d.nama)}</div>
      </div>
      
      <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:0.6rem;">
        <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:0.4rem;padding:0.6rem;">
          <span class="detail-label" style="font-size:0.7rem;"><i class="fas fa-envelope"></i> Email</span>
          <a href="mailto:${h(d.email)}" style="color:#3b82f6;text-decoration:none;font-size:0.75rem;word-break:break-all;display:block;margin-top:0.2rem;">${h(d.email)}</a>
        </div>
        <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:0.4rem;padding:0.6rem;">
          <span class="detail-label" style="font-size:0.7rem;"><i class="fas fa-phone"></i> Telepon</span>
          <a href="tel:${h(d.phone)}" style="color:#3b82f6;text-decoration:none;font-size:0.8rem;font-weight:600;display:block;margin-top:0.2rem;">${h(d.phone)}</a>
        </div>
        <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:0.4rem;padding:0.6rem;">
          <span class="detail-label" style="font-size:0.7rem;"><i class="fas fa-user-circle"></i> PIC</span>
          <div style="font-size:0.8rem;font-weight:600;color:#111827;margin-top:0.2rem;">${h(d.pic)}</div>
        </div>
      </div>
      
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.6rem;">
        <div>
          <span class="detail-label" style="font-size:0.7rem;"><i class="fas fa-flag"></i> Status</span>
          <span class="badge-status badge-warning" style="margin-top:0.2rem;display:inline-block;">${h(d.status)}</span>
        </div>
        ${d.source && d.source !== '-' ? `
        <div>
          <span class="detail-label" style="font-size:0.7rem;"><i class="fas fa-bullseye"></i> Source</span>
          <div style="font-size:0.8rem;font-weight:600;color:#111827;margin-top:0.2rem;">${h(d.source)}</div>
        </div>
        ` : ''}
      </div>
      
      <div>
        <span class="detail-label" style="font-size:0.7rem;"><i class="fas fa-map-marker-alt"></i> Lokasi</span>
        <div style="font-size:0.85rem;color:#111827;margin-top:0.2rem;">${h(d.location)}</div>
        ${d.address && d.address !== '-' ? `<small style="color:#6b7280;display:block;margin-top:0.1rem;font-size:0.75rem;">${h(d.address)}</small>` : ''}
      </div>
      
      ${d.notes && d.notes !== '-' ? `
      <div>
        <span class="detail-label" style="font-size:0.7rem;"><i class="fas fa-sticky-note"></i> Catatan</span>
        <div style="background:#f0fdf4;border-left:3px solid #10b981;padding:0.6rem;border-radius:0.3rem;font-size:0.8rem;color:#065f46;line-height:1.4;margin-top:0.2rem;">${h(d.notes)}</div>
      </div>
      ` : ''}
      
      <div style="background:#f9fafb;border-top:1px solid #e5e7eb;padding:0.5rem;margin:0 -1.25rem -1.25rem;border-radius:0 0 0.5rem 0.5rem;">
        <small style="color:#6b7280;font-size:0.7rem;"><i class="fas fa-clock"></i> ${h(d.created_at)} • ${h(d.created_by)}</small>
      </div>
    </div>
  `;
}

function renderVisit(d) {
    return `
    <div style="display:flex;flex-direction:column;gap:0.5rem;">
      <div style="background:linear-gradient(135deg,#10b981,#059669);padding:0.75rem;border-radius:0.5rem;margin:-0.5rem -0.5rem 0;">
        <span style="font-size:0.6rem;color:rgba(255,255,255,0.8);font-weight:600;text-transform:uppercase;display:block;margin-bottom:0.1rem;"><i class="fas fa-building"></i> Perusahaan</span>
        <div style="font-size:1rem;font-weight:700;color:#fff;">${h(d.company)}</div>
      </div>
      
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.6rem;">
        <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:0.4rem;padding:0.7rem;">
          <span class="detail-label" style="font-size:0.7rem;"><i class="fas fa-user"></i> PIC</span>
          <div style="font-weight:600;font-size:0.9rem;color:#111827;margin-top:0.2rem;">${h(d.pic_name)}</div>
          <div style="display:flex;flex-direction:column;gap:0.15rem;margin-top:0.3rem;">
            <small style="color:#6b7280;font-size:0.7rem;"><i class="fas fa-briefcase" style="width:12px;"></i> ${h(d.pic_position)}</small>
            <small style="color:#6b7280;font-size:0.7rem;"><i class="fas fa-phone" style="width:12px;"></i> ${h(d.pic_phone)}</small>
            <small style="color:#6b7280;font-size:0.7rem;"><i class="fas fa-envelope" style="width:12px;"></i> ${h(d.pic_email)}</small>
          </div>
        </div>
        <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:0.4rem;padding:0.7rem;">
          <span class="detail-label" style="font-size:0.7rem;"><i class="fas fa-user-tie"></i> Sales</span>
          <div style="font-weight:600;font-size:0.9rem;color:#111827;margin-top:0.2rem;">${h(d.sales_name)}</div>
          <small style="color:#6b7280;display:block;font-size:0.7rem;margin-top:0.3rem;"><i class="fas fa-envelope" style="width:12px;"></i> ${h(d.sales_email)}</small>
        </div>
      </div>
      
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.6rem;">
        <div>
          <span class="detail-label" style="font-size:0.7rem;"><i class="fas fa-calendar"></i> Tanggal Visit</span>
          <div style="font-size:0.9rem;font-weight:600;color:#111827;margin-top:0.2rem;">${h(d.visit_date)}</div>
        </div>
        <div>
          <span class="detail-label" style="font-size:0.7rem;"><i class="fas fa-redo"></i> Follow Up</span>
          <span class="badge-status ${d.is_follow_up === 'Ya' ? 'badge-success' : 'badge-danger'}" style="margin-top:0.2rem;display:inline-block;">${h(d.is_follow_up)}</span>
        </div>
      </div>
      
      <div>
        <span class="detail-label" style="font-size:0.7rem;"><i class="fas fa-map-marker-alt"></i> Lokasi</span>
        <div style="font-size:0.85rem;color:#111827;line-height:1.3;margin-top:0.2rem;">
          ${h(d.location)}
          ${d.address && d.address !== '-' ? `<br><small style="color:#6b7280;font-size:0.75rem;">${h(d.address)}</small>` : ''}
        </div>
      </div>
      
      <div>
        <span class="detail-label" style="font-size:0.7rem;"><i class="fas fa-lightbulb"></i> Purpose</span>
        <div style="background:#eff6ff;border-left:3px solid #3b82f6;padding:0.6rem;border-radius:0.3rem;font-size:0.8rem;color:#1e40af;line-height:1.4;margin-top:0.2rem;">${h(d.visit_purpose)}</div>
      </div>
      
      <div style="background:#f9fafb;border-top:1px solid #e5e7eb;padding:0.5rem;margin:0 -1.25rem -1.25rem;border-radius:0 0 0.5rem 0.5rem;">
        <small style="color:#6b7280;font-size:0.7rem;"><i class="fas fa-clock"></i> Dibuat: ${h(d.created_at)}</small>
      </div>
    </div>
  `;
}

function renderPenawaran(d) {
    return `
    <div style="display:flex;flex-direction:column;gap:0.5rem;">
      <div style="background:linear-gradient(135deg,#f59e0b,#d97706);padding:0.75rem;border-radius:0.5rem;margin:-0.5rem -0.5rem 0;">
        <span style="font-size:0.6rem;color:rgba(255,255,255,0.8);font-weight:600;text-transform:uppercase;display:block;margin-bottom:0.1rem;"><i class="fas fa-building"></i> Perusahaan</span>
        <div style="font-size:1rem;font-weight:700;color:#fff;">${h(d.nama_perusahaan)}</div>
      </div>
      
      <div style="background:linear-gradient(135deg,#d1fae5,#a7f3d0);border-left:3px solid #10b981;padding:0.75rem;border-radius:0.4rem;text-align:center;">
        <span class="detail-label" style="color:#065f46;font-size:0.7rem;"><i class="fas fa-money-bill-wave"></i> Nilai Proyek</span>
        <div style="font-size:1.3rem;font-weight:700;color:#047857;margin-top:0.2rem;">${h(d.nilai_proyek)}</div>
      </div>
      
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.6rem;">
        <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:0.4rem;padding:0.7rem;">
          <span class="detail-label" style="font-size:0.7rem;"><i class="fas fa-user"></i> PIC</span>
          <div style="font-weight:600;font-size:0.9rem;color:#111827;margin-top:0.2rem;">${h(d.pic_name)}</div>
          <div style="display:flex;flex-direction:column;gap:0.15rem;margin-top:0.3rem;">
            <small style="color:#6b7280;font-size:0.7rem;"><i class="fas fa-phone" style="width:12px;"></i> ${h(d.pic_phone)}</small>
            <small style="color:#6b7280;font-size:0.7rem;"><i class="fas fa-envelope" style="width:12px;"></i> ${h(d.pic_email)}</small>
          </div>
        </div>
        <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:0.4rem;padding:0.7rem;">
          <span class="detail-label" style="font-size:0.7rem;"><i class="fas fa-user-tie"></i> Sales</span>
          <div style="font-weight:600;font-size:0.9rem;color:#111827;margin-top:0.2rem;">${h(d.nama_sales)}</div>
          <small style="color:#6b7280;display:block;font-size:0.7rem;margin-top:0.3rem;"><i class="fas fa-envelope" style="width:12px;"></i> ${h(d.sales_email)}</small>
        </div>
      </div>
      
      <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:0.6rem;align-items:end;">
        <div>
          <span class="detail-label" style="font-size:0.7rem;"><i class="fas fa-calendar-check"></i> Mulai Kerja</span>
          <div style="font-size:0.85rem;font-weight:600;color:#111827;margin-top:0.2rem;">${h(d.tanggal_mulai_kerja)}</div>
        </div>
        <div>
          <span class="detail-label" style="font-size:0.7rem;"><i class="fas fa-calendar-times"></i> Selesai Kerja</span>
          <div style="font-size:0.85rem;font-weight:600;color:#111827;margin-top:0.2rem;">${h(d.tanggal_selesai_kerja)}</div>
        </div>
        <div>
          <span class="badge-status ${d.status === 'Deals' ? 'badge-success' : 'badge-danger'}" style="font-size:0.75rem;padding:0.35rem 0.75rem;">
            ${h(d.status)}
          </span>
        </div>
      </div>
      
      ${d.work_duration && d.work_duration !== '-' ? `
      <div>
        <span class="detail-label" style="font-size:0.7rem;"><i class="fas fa-hourglass-half"></i> Total Durasi</span>
        <div style="font-size:0.95rem;font-weight:700;color:#b45309;margin-top:0.2rem;">${h(d.work_duration)}</div>
      </div>
      ` : ''}
      
      ${d.keterangan && d.keterangan !== '-' ? `
      <div>
        <span class="detail-label" style="font-size:0.7rem;"><i class="fas fa-sticky-note"></i> Keterangan</span>
        <div style="background:#f0fdf4;border-left:3px solid #10b981;padding:0.6rem;border-radius:0.3rem;font-size:0.8rem;color:#065f46;line-height:1.4;margin-top:0.2rem;">${h(d.keterangan)}</div>
      </div>
      ` : ''}
      
      <div style="background:#f9fafb;border-top:1px solid #e5e7eb;padding:0.5rem;margin:0 -1.25rem -1.25rem;border-radius:0 0 0.5rem 0.5rem;">
        <small style="color:#6b7280;font-size:0.7rem;"><i class="fas fa-clock"></i> Dibuat: ${h(d.created_at)}</small>
      </div>
    </div>
  `;
}

function showError(msg) {
    return `
    <div style="background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 0.5rem; padding: 0.75rem;">
      <div style="display: flex; align-items: center; gap: 0.75rem;">
        <i class="fas fa-exclamation-circle" style="color: #dc2626; font-size: 1.75rem;"></i>
        <div>
          <h5 style="margin: 0 0 0.15rem 0; font-weight: 600; color: #7f1d1d; font-size: 0.9rem;">Gagal Memuat Data</h5>
          <p style="margin: 0; color: #991b1b; font-size: 0.8rem;">${h(msg)}</p>
        </div>
      </div>
    </div>
  `;
}

function h(t) {
    if (!t || t === 'null' || t === 'undefined') return '-';
    const m = {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'};
    return String(t).replace(/[&<>"']/g, c => m[c]);
}
</script>

<style>
[x-cloak] { display: none !important; }

#detailModal {
    scroll-behavior: smooth;
}

#modalContent::-webkit-scrollbar {
    width: 6px;
}

#modalContent::-webkit-scrollbar-track {
    background: transparent;
}

#modalContent::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

#modalContent::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>

@endsection