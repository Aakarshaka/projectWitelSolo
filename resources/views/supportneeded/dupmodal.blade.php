<style>
    /* CP (Copy) Modal Base Styles - Ensure modal is hidden by default */
    .cp-modal {
        display: none !important;
        position: fixed;
        z-index: 10000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
        backdrop-filter: blur(2px);
    }

    /* When CP modal is shown */
    .cp-modal.show {
        display: block !important;
        opacity: 1;
        visibility: visible;
    }

    /* CP Modal Content - FIXED for seamless footer */
    .cp-modal-content {
        background-color: #ffffff;
        margin: 3% auto;
        padding: 0;
        border: none;
        border-radius: 12px;
        width: 90%;
        max-width: 750px;
        max-height: 90vh;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        transform: translateY(-50px) scale(0.95);
        transition: transform 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        display: flex;
        flex-direction: column;
    }

    .cp-modal.show .cp-modal-content {
        transform: translateY(0) scale(1);
    }

    /* CP Modal Header - Light burgundy theme - STICKY */
    .cp-modal-header {
        background: linear-gradient(135deg, #8b1538 0%, #6b1f47 100%);
        color: white;
        padding: 25px 30px;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        overflow: hidden;
        flex-shrink: 0;
        z-index: 10;
    }

    .cp-modal-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        animation: cp-shimmer 3s infinite;
    }

    .cp-modal-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        z-index: 1;
        position: relative;
    }

    /* CP Close Button */
    .cp-modal-close {
        color: white;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        padding: 8px 12px;
        border-radius: 50%;
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        z-index: 2;
        position: relative;
    }

    .cp-modal-close:hover,
    .cp-modal-close:focus {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg) scale(1.1);
        outline: none;
    }

    /* CP Modal Body - FIXED for seamless connection */
    .cp-modal-body {
        padding: 30px 30px 0 30px;
        /* Removed bottom padding */
        background: linear-gradient(135deg, #fafbfc 0%, #f5f6f8 100%);
        color: #2c3e50;
        flex: 1;
        overflow-y: auto;
        min-height: 0;
    }

    .cp-modal-body::-webkit-scrollbar {
        width: 6px;
    }

    .cp-modal-body::-webkit-scrollbar-track {
        background: #e9ecef;
        border-radius: 3px;
    }

    .cp-modal-body::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #8b1538 0%, #6b1f47 100%);
        border-radius: 3px;
    }

    /* CP Form Styling - IMPROVED */
    .cp-form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .cp-form-group {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .cp-form-group.half-width {
        flex: 0 0 calc(50% - 10px);
    }

    /* IMPROVED: Better label styling with high contrast */
    .cp-form-label {
        margin-bottom: 8px;
        font-weight: 600;
        color: #1a252f;
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 5px;
        text-shadow: 0 1px 2px rgba(255, 255, 255, 0.8);
    }

    .cp-form-required {
        color: #dc3545;
        font-weight: bold;
        font-size: 16px;
    }

    /* IMPROVED: Better form control styling */
    .cp-form-control {
        padding: 14px 18px;
        border: 2px solid #c8d1db;
        border-radius: 8px;
        font-size: 15px;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        transition: all 0.3s ease;
        background: #ffffff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        color: #2c3e50;
        font-weight: 500;
    }

    .cp-form-control:focus {
        outline: none;
        border-color: #8b1538;
        box-shadow: 0 0 0 3px rgba(139, 21, 56, 0.15);
        transform: translateY(-1px);
        background: #ffffff;
    }

    .cp-form-control::placeholder {
        color: #6c757d;
        font-weight: 400;
        opacity: 0.7;
    }

    /* IMPROVED: Better readonly field styling */
    .cp-form-control.readonly-field {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
        border-color: #adb5bd !important;
        color: #495057 !important;
        cursor: not-allowed !important;
        font-style: normal;
        font-weight: 500;
    }

    .cp-form-control.editable-field {
        background: linear-gradient(135deg, #ffffff 0%, #fef8f8 100%) !important;
        border-color: #8b1538 !important;
        box-shadow: 0 0 0 2px rgba(139, 21, 56, 0.1) !important;
        animation: cp-pulse-burgundy 2s infinite;
        color: #2c3e50 !important;
        font-weight: 500;
    }

    .cp-form-control.editable-field:focus {
        border-color: #6b1f47 !important;
        box-shadow: 0 0 0 4px rgba(139, 21, 56, 0.25) !important;
        animation: none;
    }

    .cp-form-control:disabled {
        background: #f8f9fa !important;
        border-color: #ced4da !important;
        color: #6c757d !important;
        cursor: not-allowed !important;
    }

    /* IMPROVED: Better help text */
    .cp-form-help {
        font-size: 13px;
        color: #6c757d;
        margin-top: 6px;
        font-style: italic;
        line-height: 1.4;
        font-weight: 400;
    }

    /* IMPROVED: Better checkbox styling */
    .cp-checkbox-group {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 15px;
        padding: 15px 20px;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border: 2px solid #e9ecef;
        border-radius: 10px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .cp-checkbox-group:hover {
        border-color: #8b1538;
        background: linear-gradient(135deg, #fff5f5 0%, #fef2f2 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(139, 21, 56, 0.1);
    }

    .cp-checkbox-input {
        margin: 0;
        width: 18px;
        height: 18px;
        accent-color: #8b1538;
        cursor: pointer;
    }

    .cp-checkbox-label {
        margin: 0;
        font-weight: 500;
        cursor: pointer;
        user-select: none;
        color: #2c3e50;
        transition: color 0.3s ease;
    }

    .cp-checkbox-input:checked+.cp-checkbox-label {
        color: #8b1538;
        font-weight: 600;
    }

    /* CP Modal Footer - FIXED for seamless connection */
    .cp-modal-footer {
        background: linear-gradient(135deg, #fafbfc 0%, #f5f6f8 100%);
        /* Same as body background */
        padding: 20px 30px;
        border-top: 1px solid #e6e1e8;
        /* Remove border */
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        border-radius: 0 0 12px 12px;
        flex-shrink: 0;
        position: sticky;
        bottom: 0;
        z-index: 10;
        /* Remove shadow for seamless look */
    }

    /* CP Button Styling - IMPROVED */
    .cp-btn-cancel {
        background: linear-gradient(135deg, #6c757d 0%, #545b62 100%);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        font-family: inherit;
    }

    .cp-btn-cancel::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .cp-btn-cancel:hover::before {
        left: 100%;
    }

    .cp-btn-cancel:hover {
        background: linear-gradient(135deg, #5a6268 0%, #495057 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(108, 117, 125, 0.3);
    }

    .cp-btn-save {
        background: linear-gradient(135deg, #8b1538 0%, #6b1f47 100%);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        font-family: inherit;
    }

    .cp-btn-save::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .cp-btn-save:hover::before {
        left: 100%;
    }

    .cp-btn-save:hover {
        background: linear-gradient(135deg, #a01742 0%, #7d2451 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(139, 21, 56, 0.4);
    }

    .cp-btn-save:active,
    .cp-btn-cancel:active {
        transform: translateY(0);
    }

    /* CP Copy Button in Table */
    .cp-copy-btn {
        background: linear-gradient(135deg, #8b1538 0%, #6b1f47 100%);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        font-family: inherit;
    }

    .cp-copy-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .cp-copy-btn:hover::before {
        left: 100%;
    }

    .cp-copy-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 21, 56, 0.4);
    }

    .cp-copy-btn:active {
        transform: translateY(0);
    }

    /* CP Enhanced Notification Styles */
    .cp-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 11000;
        min-width: 320px;
        max-width: 500px;
        border-radius: 10px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        animation: cp-slideInRight 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        overflow: hidden;
        backdrop-filter: blur(10px);
    }

    .cp-notification-info,
    .cp-notification-success,
    .cp-notification-error,
    .cp-notification-warning {
        background: linear-gradient(135deg, #8b1538 0%, #6b1f47 100%);
        color: white;
    }

    .cp-notification-content {
        padding: 18px 22px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .cp-notification-icon {
        font-size: 20px;
        font-weight: bold;
        min-width: 24px;
        text-align: center;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .cp-notification-message {
        flex: 1;
        font-size: 14px;
        font-weight: 500;
        line-height: 1.5;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .cp-notification-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        font-size: 18px;
        font-weight: bold;
        cursor: pointer;
        padding: 6px 10px;
        border-radius: 6px;
        transition: all 0.2s ease;
        min-width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cp-notification-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }

    .cp-notification.fade-out {
        animation: cp-fadeOut 0.3s ease forwards;
    }

    /* CP Body scroll lock */
    .cp-modal-open {
        overflow: hidden !important;
        padding-right: 15px;
    }

    /* CP Animations */
    @keyframes cp-slideInRight {
        from {
            transform: translateX(100%) scale(0.8);
            opacity: 0;
        }

        to {
            transform: translateX(0) scale(1);
            opacity: 1;
        }
    }

    @keyframes cp-fadeOut {
        to {
            opacity: 0;
            transform: translateX(100%) scale(0.8);
        }
    }

    @keyframes cp-shimmer {
        0% {
            transform: translateX(-100%);
        }

        100% {
            transform: translateX(100%);
        }
    }

    @keyframes cp-pulse-burgundy {
        0% {
            box-shadow: 0 0 0 2px rgba(139, 21, 56, 0.1);
        }

        50% {
            box-shadow: 0 0 0 4px rgba(139, 21, 56, 0.2);
        }

        100% {
            box-shadow: 0 0 0 2px rgba(139, 21, 56, 0.1);
        }
    }

    /* CP Responsive Design */
    @media (max-width: 768px) {
        .cp-notification {
            left: 10px;
            right: 10px;
            min-width: auto;
            max-width: none;
        }

        .cp-modal-content {
            width: 95%;
            margin: 5px auto;
            max-height: 95vh;
        }

        .cp-modal-header,
        .cp-modal-body,
        .cp-modal-footer {
            padding: 20px;
        }

        .cp-modal-body {
            padding: 20px 20px 0 20px;
            /* Maintain no bottom padding */
        }

        .cp-form-row {
            flex-direction: column;
            gap: 15px;
        }

        .cp-form-group.half-width {
            flex: 1;
        }

        .cp-modal-footer {
            flex-direction: column;
            gap: 10px;
        }

        .cp-btn-cancel,
        .cp-btn-save {
            width: 100%;
            padding: 14px;
        }

        .cp-copy-btn {
            font-size: 12px;
            padding: 6px 12px;
        }
    }

    @media (max-width: 480px) {
        .cp-modal-content {
            width: 98%;
            border-radius: 8px;
        }

        .cp-modal-header h2 {
            font-size: 20px;
        }

        .cp-modal-body {
            padding: 15px 15px 0 15px;
            /* Maintain no bottom padding */
        }

        .cp-modal-footer {
            padding: 15px;
        }

        .cp-form-control {
            padding: 12px 16px;
        }
    }
</style>
<!-- Modal Copy Support Needed dengan CP prefix -->
<div id="copySupportModal" class="cp-modal">
    <div class="cp-modal-content">
        <div class="cp-modal-header">
            <h2>Copy Support Needed</h2>
            <button class="cp-modal-close" onclick="closeModal('copySupportModal')" type="button">&times;</button>
        </div>
        <div class="cp-modal-body">
            <form id="copySupportForm" method="POST" action="{{ route('supportneeded.store') }}">
                @csrf

                <!-- Hidden inputs untuk filter -->
                <input type="hidden" name="filter_progress" value="{{ request('progress') }}">
                <input type="hidden" name="filter_status" value="{{ request('status') }}">
                <input type="hidden" name="filter_unit_or_telda" value="{{ request('unit_or_telda') }}">
                <input type="hidden" name="filter_uic" value="{{ request('uic') }}">
                <input type="hidden" name="filter_search" value="{{ request('search') }}">

                <div class="cp-form-row">
                    <div class="cp-form-group">
                        <label for="copy_agenda" class="cp-form-label">
                            Agenda <span class="cp-form-required">*</span>
                        </label>
                        <input type="text" id="copy_agenda" name="agenda" class="cp-form-control readonly-field"
                            required readonly>
                        <small class="cp-form-help">Data akan dicopy, klik "Enable Edit" untuk mengubah</small>
                    </div>
                </div>

                <div class="cp-form-row">
                    <div class="cp-form-group half-width">
                        <label for="copy_unit_or_telda" class="cp-form-label">Witel or Unit</label>
                        <select id="copy_unit_or_telda" name="unit_or_telda" class="cp-form-control readonly-field"
                            disabled>
                            <option value="">Pilih Witel or Unit</option>
                            <option value="TELDA BLORA">TELDA BLORA</option>
                            <option value="TELDA BOYOLALI">TELDA BOYOLALI</option>
                            <option value="TELDA JEPARA">TELDA JEPARA</option>
                            <option value="TELDA KLATEN">TELDA KLATEN</option>
                            <option value="TELDA KUDUS">TELDA KUDUS</option>
                            <option value="MEA SOLO">MEA SOLO</option>
                            <option value="TELDA PATI">TELDA PATI</option>
                            <option value="TELDA PURWODADI">TELDA PURWODADI</option>
                            <option value="TELDA REMBANG">TELDA REMBANG</option>
                            <option value="TELDA SRAGEN">TELDA SRAGEN</option>
                            <option value="TELDA WONOGIRI">TELDA WONOGIRI</option>
                            <option value="BS">BS</option>
                            <option value="GS">GS</option>
                            <option value="PRQ">PRQ</option>
                            <option value="SSGS">SSGS</option>
                            <option value="RSO WITEL">RSO WITEL</option>
                        </select>
                    </div>

                    <div class="cp-form-group half-width">
                        <label for="copy_start_date" class="cp-form-label">Start Date</label>
                        <input type="date" id="copy_start_date" name="start_date"
                            class="cp-form-control editable-field">
                        <small class="cp-form-help">Kosong - silakan atur tanggal baru</small>
                    </div>
                </div>

                <div class="cp-form-row">
                    <div class="cp-form-group">
                        <label for="copy_notes_to_follow_up" class="cp-form-label">Notes to Follow Up</label>
                        <textarea id="copy_notes_to_follow_up" name="notes_to_follow_up"
                            class="cp-form-control readonly-field" rows="3" readonly></textarea>
                    </div>
                </div>

                <div class="cp-form-row">
                    <div class="cp-form-group half-width">
                        <label for="copy_uic" class="cp-form-label">UIC</label>
                        <select id="copy_uic" name="uic" class="cp-form-control readonly-field" disabled>
                            <option value="">Pilih UIC</option>
                            <option value="TELDA BLORA">TELDA BLORA</option>
                            <option value="TELDA BOYOLALI">TELDA BOYOLALI</option>
                            <option value="TELDA JEPARA">TELDA JEPARA</option>
                            <option value="TELDA KLATEN">TELDA KLATEN</option>
                            <option value="TELDA KUDUS">TELDA KUDUS</option>
                            <option value="MEA SOLO">MEA SOLO</option>
                            <option value="TELDA PATI">TELDA PATI</option>
                            <option value="TELDA PURWODADI">TELDA PURWODADI</option>
                            <option value="TELDA REMBANG">TELDA REMBANG</option>
                            <option value="TELDA SRAGEN">TELDA SRAGEN</option>
                            <option value="TELDA WONOGIRI">TELDA WONOGIRI</option>
                            <option value="BS">BS</option>
                            <option value="GS">GS</option>
                            <option value="RLEGS">RLEGS</option>
                            <option value="RSO REGIONAL">RSO REGIONAL</option>
                            <option value="RSO WITEL">RSO WITEL</option>
                            <option value="ED">ED</option>
                            <option value="TIF">TIF</option>
                            <option value="TSEL">TSEL</option>
                            <option value="GSD">GSD</option>
                            <option value="SSGS">SSGS</option>
                            <option value="PRQ">PRQ</option>
                            <option value="RSMES">RSMES</option>
                            <option value="BPPLP">BPPLP</option>
                            <option value="SSS">SSS</option>
                            <option value="RWS">RWS</option>
                            <option value="LESA V">LESA V</option>
                        </select>
                    </div>

                    <div class="cp-form-group half-width">
                        <label for="copy_progress" class="cp-form-label">Progress</label>
                        <select id="copy_progress" name="progress" class="cp-form-control readonly-field" disabled>
                            <option value="Open">Open</option>
                            <option value="Need Discuss">Need Discuss</option>
                            <option value="On Progress">On Progress</option>
                            <option value="Done">Done</option>
                        </select>
                    </div>
                </div>

                <div class="cp-form-row">
                    <div class="cp-form-group">
                        <label for="copy_response_uic" class="cp-form-label">Response UIC</label>
                        <textarea id="copy_response_uic" name="response_uic" class="cp-form-control readonly-field"
                            rows="3" readonly></textarea>
                    </div>
                </div>

                <div class="cp-form-row">
                    <div class="cp-form-group">
                        <div class="cp-checkbox-group">
                            <input type="checkbox" id="enableEditCopy" class="cp-checkbox-input"
                                onchange="toggleCopyFields(this.checked)">
                            <label for="enableEditCopy" class="cp-checkbox-label">Enable Edit - Centang untuk mengedit
                                data yang dicopy</label>
                        </div>
                    </div>
                </div>

                <div class="cp-modal-footer">
                    <button type="button" class="cp-btn-cancel" onclick="closeModal('copySupportModal')">Cancel</button>
                    <button type="submit" class="cp-btn-save">Save Copy</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // JavaScript untuk mengelola modal dengan benar
    document.addEventListener('DOMContentLoaded', function () {
        // Pastikan semua modal tersembunyi saat halaman dimuat
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.style.display = 'none';
            modal.classList.remove('show');
        });

        // Initialize copy modal specifically
        const copyModal = document.getElementById('copySupportModal');
        if (copyModal) {
            copyModal.style.display = 'none';
            copyModal.classList.remove('show');
        }
    });

    // Function untuk membuka modal
    function openModal(modalId) {
        console.log('Opening modal:', modalId);
        const modal = document.getElementById(modalId);
        if (modal) {
            // Hide all other modals first
            const allModals = document.querySelectorAll('.modal');
            allModals.forEach(m => {
                m.style.display = 'none';
                m.classList.remove('show');
            });

            // Show the requested modal
            modal.style.display = 'block';
            // Small delay to ensure display is set before adding show class
            setTimeout(() => {
                modal.classList.add('show');
            }, 10);

            // Add body class to prevent scrolling
            document.body.classList.add('modal-open');

            // Focus on first input if available
            const firstInput = modal.querySelector('input, select, textarea');
            if (firstInput && !firstInput.hasAttribute('readonly') && !firstInput.hasAttribute('disabled')) {
                setTimeout(() => firstInput.focus(), 100);
            }
        } else {
            console.error('Modal not found:', modalId);
        }
    }

    // Function untuk menutup modal
    function closeModal(modalId) {
        console.log('Closing modal:', modalId);
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('show');
            // Wait for animation to complete before hiding
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);

            // Remove body class
            document.body.classList.remove('modal-open');

            // Reset copy modal when closed
            if (modalId === 'copySupportModal') {
                resetCopyModal();
            }
        }
    }

    // Function untuk copy row data ke modal baru
    function copyRowData(data) {
        console.log('Copying data:', data);

        // Set nilai ke form copy dengan data yang sudah ada
        const fields = {
            'copy_agenda': data.agenda || '',
            'copy_unit_or_telda': data.unit_or_telda || '',
            'copy_start_date': '', // Kosongkan untuk tanggal baru
            'copy_notes_to_follow_up': data.notes_to_follow_up || '',
            'copy_uic': data.uic || '',
            'copy_progress': data.progress || 'Open',
            'copy_response_uic': data.response_uic || ''
        };

        // Set values to form fields
        Object.keys(fields).forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.value = fields[fieldId];
            }
        });

        // Reset checkbox edit ke unchecked
        const checkbox = document.getElementById('enableEditCopy');
        if (checkbox) {
            checkbox.checked = false;
        }

        // Set semua field ke readonly/disabled (default state)
        toggleCopyFields(false);

        // Buka modal copy
        openModal('copySupportModal');
    }

    // Function untuk toggle enable/disable fields di copy modal
    function toggleCopyFields(enabled) {
        const fields = [
            'copy_agenda',
            'copy_unit_or_telda',
            'copy_notes_to_follow_up',
            'copy_uic',
            'copy_progress',
            'copy_response_uic'
        ];

        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                if (field.tagName.toLowerCase() === 'select') {
                    field.disabled = !enabled;
                } else {
                    field.readOnly = !enabled;
                }

                // Update visual styling
                if (enabled) {
                    field.classList.remove('readonly-field');
                    field.classList.add('editable-field');
                } else {
                    field.classList.remove('editable-field');
                    field.classList.add('readonly-field');
                }
            }
        });

        // Start date selalu bisa diedit
        const startDateField = document.getElementById('copy_start_date');
        if (startDateField) {
            startDateField.readOnly = false;
            startDateField.classList.remove('readonly-field');
            startDateField.classList.add('editable-field');
        }

        // Update label checkbox
        const checkboxLabel = document.querySelector('label[for="enableEditCopy"]');
        if (checkboxLabel) {
            if (enabled) {
                checkboxLabel.innerHTML = 'Edit Mode Active - Data dapat diubah sebelum disimpan';
                checkboxLabel.style.color = '#28a745';
            } else {
                checkboxLabel.innerHTML = 'Enable Edit - Centang untuk mengedit data yang dicopy';
                checkboxLabel.style.color = '#495057';
            }
        }
    }

    // Function untuk reset copy modal
    function resetCopyModal() {
        const form = document.getElementById('copySupportForm');
        if (form) {
            form.reset();
        }

        // Reset checkbox
        const checkbox = document.getElementById('enableEditCopy');
        if (checkbox) {
            checkbox.checked = false;
        }

        // Set semua field ke readonly/disabled
        toggleCopyFields(false);
    }

    // Enhanced showNotification dengan berbagai tipe
    function showNotification(message, type = 'info', duration = 4000) {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.notification');
        existingNotifications.forEach(notification => notification.remove());

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;

        // Set icon based on type
        let icon = '';
        switch (type) {
            case 'success':
                icon = '✓';
                break;
            case 'error':
                icon = '✕';
                break;
            case 'warning':
                icon = '⚠';
                break;
            case 'info':
            default:
                icon = 'ℹ';
                break;
        }

        notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-icon">${icon}</span>
            <span class="notification-message">${message}</span>
            <button class="notification-close" onclick="this.parentElement.parentElement.remove()">×</button>
        </div>
    `;

        // Add styles for notification
        notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        padding: 15px;
        min-width: 300px;
        max-width: 500px;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
    `;

        // Add type-specific styling
        const colors = {
            success: '#4a0e4e',
            error: '#4a0e4e',
            warning: '#4a0e4e',
            info: '#4a0e4e'
        };

        notification.querySelector('.notification-icon').style.color = colors[type] || colors.info;

        // Add to body
        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.style.opacity = '1';
            notification.style.transform = 'translateX(0)';
        }, 10);

        // Auto remove after duration
        setTimeout(() => {
            if (notification.parentElement) {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }
        }, duration);
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function () {
        // Handle form submission copy
        const copyForm = document.getElementById('copySupportForm');
        if (copyForm) {
            copyForm.addEventListener('submit', function (e) {
                // Validasi sederhana sebelum submit
                const agenda = document.getElementById('copy_agenda').value.trim();
                if (!agenda) {
                    e.preventDefault();
                    showNotification('Agenda harus diisi!', 'error');
                    return false;
                }

                // Tampilkan notifikasi loading
                showNotification('Menyimpan copy data...', 'info', 2000);
            });
        }

        // Handle enable edit checkbox
        const enableEditCheckbox = document.getElementById('enableEditCopy');
        if (enableEditCheckbox) {
            enableEditCheckbox.addEventListener('change', function () {
                toggleCopyFields(this.checked);
            });
        }
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function (e) {
        // Escape untuk close modal
        if (e.key === 'Escape') {
            const openModals = document.querySelectorAll('.modal.show');
            openModals.forEach(modal => {
                closeModal(modal.id);
            });
        }

        // Ctrl/Cmd + D untuk duplicate/copy (jika ada item yang selected)
        if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
            e.preventDefault();
            // Logic untuk copy item yang sedang di-highlight bisa ditambahkan di sini
        }
    });

    // Click outside modal to close
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('modal') && e.target.classList.contains('show')) {
            closeModal(e.target.id);
        }
    });

    // Prevent body scroll when modal is open
    const style = document.createElement('style');
    style.textContent = `
    .modal-open {
        overflow: hidden;
    }
`;
    document.head.appendChild(style);
</script>