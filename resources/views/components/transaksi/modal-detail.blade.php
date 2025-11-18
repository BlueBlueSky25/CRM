<div id="detailModal" class="modal">
    <div class="modal-content">
        <!-- Header -->
        <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="color: white; font-weight: 600; margin: 0;">
                <i class="fas fa-info-circle"></i> Detail Transaksi
            </h3>
            <button onclick="closeDetailModal()" style="background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer; padding: 0; width: auto; height: auto;">×</button>
        </div>

        <!-- Content -->
        <div id="detailContent" style="padding: 1.5rem; max-height: calc(90vh - 150px); overflow-y: auto;">
            <!-- akan diisi oleh JavaScript -->
        </div>

        <!-- Footer/Close Button -->
        <div style="padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb; background-color: #f9fafb; display: flex; justify-content: flex-end;">
            <button onclick="closeDetailModal()"
                style="padding: 0.625rem 1.5rem; background-color: #e5e7eb; color: #111827; border: none; border-radius: 0.375rem; font-weight: 500; cursor: pointer; transition: all 0.2s;"
                onmouseover="this.style.backgroundColor='#d1d5db'"
                onmouseout="this.style.backgroundColor='#e5e7eb'">
                Tutup
            </button>
        </div>
    </div>
</div>