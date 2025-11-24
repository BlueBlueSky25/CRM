@extends('layout.main')
@section('title','Pipeline Management')

@section('content')
<div class="container-expanded mx-auto px-6 lg:px-8 py-8 pt-[60px] mt-4">
    
    <!-- Card -->
    <div style="background-color: #ffffff; border-radius: 0.5rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; overflow: hidden;">
        
        <!-- Header -->
        <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin: 0;">Pipeline Management</h3>
        </div>

        <!-- Table Container -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0;">
            
            <!-- LEADS COLUMN -->
            <div style="border-right: 1px solid #e5e7eb;">
                <!-- Header -->
                <div style="padding: 1rem; font-weight: 600; color: #374151; background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">Leads</div>
                
                <!-- Data Items -->
                <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb;">
                    <h4 style="margin: 0; font-weight: 600; color: #111827; font-size: 0.85rem;">PT Maju Jaya</h4>
                    <small style="color: #6b7280; display: block; margin-top: 0.25rem;">📧 contact@majujaya.com</small>
                    <small style="color: #6b7280; display: block;">📱 081234567890</small>
                    <div style="margin-top: 0.5rem; font-weight: 600; color: #059669; font-size: 0.85rem;">Rp 500.000.000</div>
                </div>

                <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb;">
                    <h4 style="margin: 0; font-weight: 600; color: #111827; font-size: 0.85rem;">PT ABC Corporation</h4>
                    <small style="color: #6b7280; display: block; margin-top: 0.25rem;">📧 info@abc.com</small>
                    <small style="color: #6b7280; display: block;">📱 081234567891</small>
                    <div style="margin-top: 0.5rem; font-weight: 600; color: #059669; font-size: 0.85rem;">Rp 300.000.000</div>
                </div>

                <div style="padding: 1rem;">
                    <h4 style="margin: 0; font-weight: 600; color: #111827; font-size: 0.85rem;">Toko Elektronik</h4>
                    <small style="color: #6b7280; display: block; margin-top: 0.25rem;">📧 sales@elektronik.com</small>
                    <small style="color: #6b7280; display: block;">📱 081234567892</small>
                    <div style="margin-top: 0.5rem; font-weight: 600; color: #059669; font-size: 0.85rem;">Rp 150.000.000</div>
                </div>
            </div>

            <!-- VISIT COLUMN -->
            <div style="border-right: 1px solid #e5e7eb;">
                <!-- Header -->
                <div style="padding: 1rem; font-weight: 600; color: #374151; background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">Visit</div>
                
                <!-- Data Items -->
                <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb;">
                    <h4 style="margin: 0; font-weight: 600; color: #111827; font-size: 0.85rem;">CV Sukses Bersama</h4>
                    <small style="color: #6b7280; display: block; margin-top: 0.25rem;">📧 info@suksesbersama.com</small>
                    <small style="color: #6b7280; display: block;">📱 082345678901</small>
                    <div style="margin-top: 0.5rem; font-weight: 600; color: #059669; font-size: 0.85rem;">Rp 750.000.000</div>
                </div>

                <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb;">
                    <h4 style="margin: 0; font-weight: 600; color: #111827; font-size: 0.85rem;">PT Jaya Mandiri</h4>
                    <small style="color: #6b7280; display: block; margin-top: 0.25rem;">📧 contact@jayamandiri.com</small>
                    <small style="color: #6b7280; display: block;">📱 082345678902</small>
                    <div style="margin-top: 0.5rem; font-weight: 600; color: #059669; font-size: 0.85rem;">Rp 600.000.000</div>
                </div>

                <div style="padding: 1rem;">
                    <h4 style="margin: 0; font-weight: 600; color: #111827; font-size: 0.85rem;">Distributor Barang</h4>
                    <small style="color: #6b7280; display: block; margin-top: 0.25rem;">📧 sales@distributor.com</small>
                    <small style="color: #6b7280; display: block;">📱 082345678903</small>
                    <div style="margin-top: 0.5rem; font-weight: 600; color: #059669; font-size: 0.85rem;">Rp 450.000.000</div>
                </div>
            </div>

            <!-- PENAWARAN COLUMN -->
            <div style="border-right: 1px solid #e5e7eb;">
                <!-- Header -->
                <div style="padding: 1rem; font-weight: 600; color: #374151; background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">Penawaran</div>
                
                <!-- Data Items -->
                <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb;">
                    <h4 style="margin: 0; font-weight: 600; color: #111827; font-size: 0.85rem;">PT Teknologi Indonesia</h4>
                    <small style="color: #6b7280; display: block; margin-top: 0.25rem;">📧 sales@tekindo.com</small>
                    <small style="color: #6b7280; display: block;">📱 083456789012</small>
                    <div style="margin-top: 0.5rem; font-weight: 600; color: #059669; font-size: 0.85rem;">Rp 1.000.000.000</div>
                </div>

                <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb;">
                    <h4 style="margin: 0; font-weight: 600; color: #111827; font-size: 0.85rem;">CV Digital Solutions</h4>
                    <small style="color: #6b7280; display: block; margin-top: 0.25rem;">📧 info@digital.com</small>
                    <small style="color: #6b7280; display: block;">📱 083456789013</small>
                    <div style="margin-top: 0.5rem; font-weight: 600; color: #059669; font-size: 0.85rem;">Rp 850.000.000</div>
                </div>

                <div style="padding: 1rem;">
                    <h4 style="margin: 0; font-weight: 600; color: #111827; font-size: 0.85rem;">Perusahaan Logistik</h4>
                    <small style="color: #6b7280; display: block; margin-top: 0.25rem;">📧 contact@logistik.com</small>
                    <small style="color: #6b7280; display: block;">📱 083456789014</small>
                    <div style="margin-top: 0.5rem; font-weight: 600; color: #059669; font-size: 0.85rem;">Rp 500.000.000</div>
                </div>
            </div>

            <!-- FOLLOW UP COLUMN -->
            <div>
                <!-- Header -->
                <div style="padding: 1rem; font-weight: 600; color: #374151; background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">Follow Up</div>
                
                <!-- Data Items -->
                <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb;">
                    <h4 style="margin: 0; font-weight: 600; color: #111827; font-size: 0.85rem;">Toko Barokah</h4>
                    <small style="color: #6b7280; display: block; margin-top: 0.25rem;">📧 toko@barokah.com</small>
                    <small style="color: #6b7280; display: block;">📱 084567890123</small>
                    <div style="margin-top: 0.5rem; font-weight: 600; color: #059669; font-size: 0.85rem;">Rp 250.000.000</div>
                </div>

                <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb;">
                    <h4 style="margin: 0; font-weight: 600; color: #111827; font-size: 0.85rem;">PT Konsultan Bisnis</h4>
                    <small style="color: #6b7280; display: block; margin-top: 0.25rem;">📧 info@konsultan.com</small>
                    <small style="color: #6b7280; display: block;">📱 084567890124</small>
                    <div style="margin-top: 0.5rem; font-weight: 600; color: #059669; font-size: 0.85rem;">Rp 700.000.000</div>
                </div>

                <div style="padding: 1rem;">
                    <h4 style="margin: 0; font-weight: 600; color: #111827; font-size: 0.85rem;">Bengkel Otomotif</h4>
                    <small style="color: #6b7280; display: block; margin-top: 0.25rem;">📧 bengkel@auto.com</small>
                    <small style="color: #6b7280; display: block;">📱 084567890125</small>
                    <div style="margin-top: 0.5rem; font-weight: 600; color: #059669; font-size: 0.85rem;">Rp 400.000.000</div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection