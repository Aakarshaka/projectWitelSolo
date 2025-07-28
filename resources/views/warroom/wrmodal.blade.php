<style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap");

    body {
        font-family: "Poppins", sans-serif;
        background: #f5f5f5;
    }

    /* Modal Styles */
    .wr-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(3px);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .wr-modal.show {
        display: block;
        opacity: 1;
    }

    .wr-modal-content {
        background-color: white;
        margin: 2% auto;
        padding: 0;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        width: 90%;
        max-width: 900px;
        max-height: 100vh;
        overflow: hidden;
        transform: scale(0.7);
        transition: transform 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .wr-modal.show .wr-modal-content {
        transform: scale(1);
    }

    .wr-modal-header {
        background: linear-gradient(135deg, #8b1538 0%, #4a0e4e 100%);
        color: white;
        padding: 20px 30px;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .wr-modal-title {
        font-size: 20px;
        font-weight: 600;
        margin: 0;
    }

    .wr-modal-close {
        background: none;
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background-color 0.3s ease;
    }

    .wr-modal-close:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }

    .wr-modal-body {
        padding: 30px;
        overflow-y: auto;
        flex-grow: 1;
        max-height: calc(95vh - 160px);
    }

    .wr-modal-footer {
        padding: 20px 30px;
        border-top: 1px solid #e6e1e8;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }

    /* Form Styles */
    .wr-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .wr-form-group {
        display: flex;
        flex-direction: column;
    }

    .wr-form-group.full-width {
        grid-column: span 2;
    }

    .wr-form-label {
        font-size: 14px;
        font-weight: 500;
        color: #2a1b2d;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .wr-form-input,
    .wr-form-select,
    .wr-form-textarea {
        padding: 12px 16px;
        border: 1px solid #e6e1e8;
        border-radius: 8px;
        font-size: 14px;
        color: #2a1b2d;
        background: white;
        transition: all 0.3s ease;
        font-family: 'Poppins', sans-serif;
        width: 100%;
        box-sizing: border-box;
    }

    .wr-form-input:focus,
    .wr-form-select:focus,
    .wr-form-textarea:focus {
        outline: none;
        border-color: #4a0e4e;
        box-shadow: 0 0 0 3px rgba(74, 14, 78, 0.1);
    }

    .wr-form-textarea {
        resize: vertical;
        min-height: 80px;
        font-family: 'Poppins', sans-serif;
    }

    /* Dynamic Action Plan Styles */
    .action-plan-section {
        border: 2px solid #e6e1e8;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        background: #fafafa;
    }

    .action-plan-header {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e6e1e8;
    }

    .action-plan-number {
        background: linear-gradient(135deg, #8b1538 0%, #4a0e4e 100%);
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-right: 10px;
    }

    .action-plan-title {
        font-size: 16px;
        font-weight: 600;
        color: #2a1b2d;
        margin: 0;
    }

    .action-plan-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .status-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .jumlah-action-highlight {
        background: linear-gradient(135deg, #8b1538 0%, #4a0e4e 100%);
        color: white;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .wr-btn {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: 'Poppins', sans-serif;
    }

    .wr-btn-secondary {
        background: #6b7280;
        color: white;
    }

    .wr-btn-secondary:hover {
        background: #4b5563;
    }

    .wr-btn-primary {
        background: #4a0e4e;
        color: white;
    }

    .wr-btn-primary:hover {
        background: #3a0b3e;
    }

    .required {
        color: #dc3545;
    }

    .jumlah-action-highlight {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #8b1538 0%, #4a0e4e 100%);
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .add-action-btn {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .add-action-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-1px);
    }

    .action-plan-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 15px;
        padding: 12px 15px;
        border-radius: 8px;
        background: linear-gradient(135deg, #8b1538 0%, #4a0e4e 100%);
        color: white;
        border-left: none;
    }

    .remove-action-btn {
        background: #dc3545;
        border: none;
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    .remove-action-btn:hover {
        background: #c82333;
        transform: scale(1.1);
    }

    .action-plan-header .action-plan-number {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .action-plan-header .action-plan-title {
        color: white;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .wr-modal-content {
            width: 90%;
            margin: 1% auto;
        }

        .wr-modal-header,
        .wr-modal-body,
        .wr-modal-footer {
            padding: 15px;
        }

        .wr-form-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .wr-form-group.full-width {
            grid-column: span 1;
        }

        .status-grid {
            grid-template-columns: 1fr;
        }

        .wr-modal-footer {
            flex-direction: column;
        }

        .wr-btn {
            width: 100%;
        }
    }
</style>

<!-- Modal Tambah -->
<div class="wr-modal" id="addModal">
    <div class="wr-modal-content">
        <div class="wr-modal-header">
            <h2 class="wr-modal-title">Tambah Data Warroom</h2>
            <button class="wr-modal-close" onclick="closeModal('addModal')">&times;</button>
        </div>
        <form id="addForm" action="{{ route('newwarroom.store') }}" method="POST">
            @csrf
            <div class="wr-modal-body">
                <!-- Basic Info -->
                <div class="wr-form-grid">
                    <div class="wr-form-group">
                        <label class="wr-form-label">Tanggal</label>
                        <input type="date" class="wr-form-input" name="tgl">
                    </div>

                    <div class="wr-form-group">
                        <label class="wr-form-label">Agenda <span class="required">*</span></label>
                        <select class="wr-form-select" name="agenda" required>
                            <option value="">Select Agenda</option>
                            <option value="1 ON 1 AM">1 ON 1 AM</option>
                            <option value="1 ON 1 TELDA">1 ON 1 TELDA</option>
                            <option value="WAR">WAR</option>
                            <option value="FORUM TIF">FORUM TIF</option>
                            <option value="FORUM TSEL">FORUM TSEL</option>
                            <option value="FORUM GSD">FORUM GSD</option>
                            <option value="REVIEW KPI">REVIEW KPI</option>
                            <option value="OTHERS">OTHERS</option>
                        </select>
                    </div>

                    <div class="wr-form-group">
                        <label class="wr-form-label">UIC</label>
                        <select class="wr-form-select" name="uic">
                            <option value="">Select UIC</option>
                            <option value="TELDA BLORA">TELDA BLORA</option>
                            <option value="TELDA BOYOLALI">TELDA BOYOLALI</option>
                            <option value="TELDA JEPARA">TELDA JEPARA</option>
                            <option value="TELDA KLATEN">TELDA KLATEN</option>
                            <option value="TELDA KUDUS">TELDA KUDUS</option>
                            <option value="TELDA MEA SOLO">MEA SOLO</option>
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
                            <option value="LESA V">LESA V</option>
                        </select>
                    </div>

                    <div class="wr-form-group">
                        <label class="wr-form-label">Peserta</label>
                        <input type="text" class="wr-form-input" name="peserta">
                    </div>

                    <div class="wr-form-group full-width">
                        <label class="wr-form-label">Pembahasan</label>
                        <textarea class="wr-form-textarea" name="pembahasan" rows="3"></textarea>
                    </div>

                    <div class="wr-form-group full-width">
                        <label class="wr-form-label">Support Needed</label>
                        <textarea class="wr-form-textarea" name="support_needed" rows="3"></textarea>
                    </div>

                    <div class="wr-form-group full-width">
                        <label class="wr-form-label">Info Kompetitor</label>
                        <textarea class="wr-form-textarea" name="info_kompetitor" rows="3"></textarea>
                    </div>

                    <div class="wr-form-group" style="grid-column: span 2;">
                        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 15px; align-items: end;">
                            <div>
                                <label class="wr-form-label">Jumlah Action Plan <span class="required">*</span></label>
                                <input type="number" class="wr-form-input" name="jumlah_action_plan" id="jumlahActionAdd"
                                    min="1" placeholder="Masukkan jumlah..." required>
                            </div>
                            <button type="button" class="wr-btn wr-btn-primary" onclick="generateActionPlans('add')">
                                Add Action Plans
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Action Plans Container -->
                <div id="actionPlansContainerAdd"></div>
            </div>
            <div class="wr-modal-footer">
                <button type="button" class="wr-btn wr-btn-secondary" onclick="closeModal('addModal')">Cancel</button>
                <button type="submit" class="wr-btn wr-btn-primary">Add Warroom Data</button>
            </div>
        </form>
    </div>
</div>

@foreach($warroomData as $item)
<!-- Modal Edit for ID {{ $item->id }} -->
<div class="wr-modal" id="editModal{{ $item->id }}">
    <div class="wr-modal-content">
        <div class="wr-modal-header">
            <h2 class="wr-modal-title">Edit Data Warroom</h2>
            <button class="wr-modal-close" onclick="closeModal('editModal{{ $item->id }}')">&times;</button>
        </div>
        <form id="editForm{{ $item->id }}" action="{{ route('newwarroom.update', $item->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="wr-modal-body">
                <!-- Basic Info -->
                <div class="wr-form-grid">
                    <div class="wr-form-group">
                        <label class="wr-form-label">Tanggal</label>
                        <input type="date" class="wr-form-input" name="tgl" value="{{ \Carbon\Carbon::parse($item->tgl)->format('Y-m-d') }}">
                    </div>

                    <div class="wr-form-group">
                        <label class="wr-form-label">Agenda <span class="required">*</span></label>
                        <select class="wr-form-select" name="agenda" required>
                            <option value="">Select Agenda</option>
                            <option value="1 ON 1 AM" {{ $item->agenda == '1 ON 1 AM' ? 'selected' : '' }}>1 ON 1 AM</option>
                            <option value="1 ON 1 TELDA" {{ $item->agenda == '1 ON 1 TELDA' ? 'selected' : '' }}>1 ON 1 TELDA</option>
                            <option value="WAR" {{ $item->agenda == 'WAR' ? 'selected' : '' }}>WAR</option>
                            <option value="FORUM TIF" {{ $item->agenda == 'FORUM TIF' ? 'selected' : '' }}>FORUM TIF</option>
                            <option value="FORUM TSEL" {{ $item->agenda == 'FORUM TSEL' ? 'selected' : '' }}>FORUM TSEL</option>
                            <option value="FORUM GSD" {{ $item->agenda == 'FORUM GSD' ? 'selected' : '' }}>FORUM GSD</option>
                            <option value="REVIEW KPI" {{ $item->agenda == 'REVIEW KPI' ? 'selected' : '' }}>REVIEW KPI</option>
                            <option value="OTHERS" {{ $item->agenda == 'OTHERS' ? 'selected' : '' }}>OTHERS</option>
                        </select>
                    </div>

                    <div class="wr-form-group">
                        <label class="wr-form-label">UIC</label>
                        <select class="wr-form-select" name="uic">
                            <option value="">Select UIC</option>
                            <option value="TELDA BLORA" {{ $item->uic == 'TELDA BLORA' ? 'selected' : '' }}>TELDA BLORA</option>
                            <option value="TELDA BOYOLALI" {{ $item->uic == 'TELDA BOYOLALI' ? 'selected' : '' }}>TELDA BOYOLALI</option>
                            <option value="TELDA JEPARA" {{ $item->uic == 'TELDA JEPARA' ? 'selected' : '' }}>TELDA JEPARA</option>
                            <option value="TELDA KLATEN" {{ $item->uic == 'TELDA KLATEN' ? 'selected' : '' }}>TELDA KLATEN</option>
                            <option value="TELDA KUDUS" {{ $item->uic == 'TELDA KUDUS' ? 'selected' : '' }}>TELDA KUDUS</option>
                            <option value="TELDA MEA SOLO" {{ $item->uic == 'MEA SOLO' ? 'selected' : '' }}>MEA SOLO</option>
                            <option value="TELDA PATI" {{ $item->uic == 'TELDA PATI' ? 'selected' : '' }}>TELDA PATI</option>
                            <option value="TELDA PURWODADI" {{ $item->uic == 'TELDA PURWODADI' ? 'selected' : '' }}>TELDA PURWODADI</option>
                            <option value="TELDA REMBANG" {{ $item->uic == 'TELDA REMBANG' ? 'selected' : '' }}>TELDA REMBANG</option>
                            <option value="TELDA SRAGEN" {{ $item->uic == 'TELDA SRAGEN' ? 'selected' : '' }}>TELDA SRAGEN</option>
                            <option value="TELDA WONOGIRI" {{ $item->uic == 'TELDA WONOGIRI' ? 'selected' : '' }}>TELDA WONOGIRI</option>
                            <option value="BS" {{ $item->uic == 'BS' ? 'selected' : '' }}>BS</option>
                            <option value="GS" {{ $item->uic == 'GS' ? 'selected' : '' }}>GS</option>
                            <option value="RLEGS" {{ $item->uic == 'RLEGS' ? 'selected' : '' }}>RLEGS</option>
                            <option value="RSO REGIONAL" {{ $item->uic == 'RSO REGIONAL' ? 'selected' : '' }}>RSO REGIONAL</option>
                            <option value="RSO WITEL" {{ $item->uic == 'RSO WITEL' ? 'selected' : '' }}>RSO WITEL</option>
                            <option value="ED" {{ $item->uic == 'ED' ? 'selected' : '' }}>ED</option>
                            <option value="TIF" {{ $item->uic == 'TIF' ? 'selected' : '' }}>TIF</option>
                            <option value="TSEL" {{ $item->uic == 'TSEL' ? 'selected' : '' }}>TSEL</option>
                            <option value="GSD" {{ $item->uic == 'GSD' ? 'selected' : '' }}>GSD</option>
                            <option value="SSGS" {{ $item->uic == 'SSGS' ? 'selected' : '' }}>SSGS</option>
                            <option value="PRQ" {{ $item->uic == 'PRQ' ? 'selected' : '' }}>PRQ</option>
                            <option value="RSMES" {{ $item->uic == 'RSMES' ? 'selected' : '' }}>RSMES</option>
                            <option value="BPPLP" {{ $item->uic == 'BPPLP' ? 'selected' : '' }}>BPPLP</option>
                            <option value="SSS" {{ $item->uic == 'SSS' ? 'selected' : '' }}>SSS</option>
                            <option value="LESA V" {{ $item->uic == 'LESA V' ? 'selected' : '' }}>LESA V</option>
                        </select>
                    </div>

                    <div class="wr-form-group">
                        <label class="wr-form-label">Peserta</label>
                        <input type="text" class="wr-form-input" name="peserta" value="{{ $item->peserta }}">
                    </div>

                    <div class="wr-form-group full-width">
                        <label class="wr-form-label">Pembahasan</label>
                        <textarea class="wr-form-textarea" name="pembahasan" rows="3">{{ $item->pembahasan }}</textarea>
                    </div>

                    <div class="wr-form-group full-width">
                        <label class="wr-form-label">Support Needed</label>
                        <textarea class="wr-form-textarea" name="support_needed" rows="3">{{ $item->support_needed }}</textarea>
                    </div>

                    <div class="wr-form-group full-width">
                        <label class="wr-form-label">Info Kompetitor</label>
                        <textarea class="wr-form-textarea" name="info_kompetitor" rows="3">{{ $item->info_kompetitor }}</textarea>
                    </div>

                    <div class="wr-form-group" style="grid-column: span 2;">
                        <div style="display: grid; grid-template-columns: 2fr auto; gap: 15px; align-items: end;">
                            <div>
                                <label class="wr-form-label">Jumlah Action Plan <span class="required">*</span></label>
                                <input type="number" class="wr-form-input" name="jumlah_action_plan" id="jumlahActionEdit{{ $item->id }}"
                                    min="1" placeholder="Masukkan jumlah..." value="{{ $item->jumlah_action_plan }}" required readonly>
                            </div>
                            <div style="display: flex; gap: 10px;">
                                <button type="button" class="wr-btn wr-btn-secondary" onclick="enableEditActionPlan('{{ $item->id }}')">
                                    Change
                                </button>
                                <button type="button" class="wr-btn wr-btn-primary" onclick="generateActionPlans('edit{{ $item->id }}')"
                                    style="display: none;" id="generateBtn{{ $item->id }}">
                                    Generate
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Action Plans Container -->
                <div id="actionPlansContainerEdit{{ $item->id }}"></div>
            </div>
            <div class="wr-modal-footer">
                <button type="button" class="wr-btn wr-btn-secondary" onclick="closeModal('editModal{{ $item->id }}')">Cancel</button>
                <button type="submit" class="wr-btn wr-btn-primary">Update Warroom Data</button>
            </div>
        </form>
    </div>
</div>
@endforeach

<script>
    // Global object untuk menyimpan data action plans
    let actionPlansData = {};

    // Helper function untuk mendapatkan info modal
    function getModalInfo(modalType) {
        const isEdit = modalType.startsWith('edit');
        const itemId = isEdit ? modalType.replace('edit', '') : null;
        const containerSuffix = modalType.charAt(0).toUpperCase() + modalType.slice(1);
        return {
            isEdit,
            itemId,
            containerSuffix
        };
    }

    /**
     * ✅ Ambil filter saat ini dari URL
     */
    function getCurrentFilters() {
        const urlParams = new URLSearchParams(window.location.search);
        const filters = {};
        const filterKeys = ['bulan', 'tahun', 'uic', 'search', 'status'];

        filterKeys.forEach(key => {
            const value = urlParams.get(key);
            if (value && value.trim() !== '' && value !== 'all') {
                filters[key] = value;
            }
        });

        return filters;
    }

    /**
     * ✅ Buat query string dari filter saat ini
     */
    function createFilterQueryString() {
        return new URLSearchParams(getCurrentFilters()).toString();
    }

    /**
     * ✅ (Opsional) Tambahkan hidden filter ke form
     */
    function addHiddenFiltersToForm(form) {
        // ✅ Hapus hidden lama
        const existingHiddenInputs = form.querySelectorAll('input[data-filter-param="true"]');
        existingHiddenInputs.forEach(input => input.remove());

        // ✅ Ambil langsung dari filter input form (bukan URL lama!)
        const filterFields = ['bulan', 'tahun', 'uic', 'search', 'status'];
        filterFields.forEach(name => {
            const field = document.querySelector(`[name="${name}"]`);
            if (field && field.value && field.value.trim() !== '' && field.value !== 'all') {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = name;
                hiddenInput.value = field.value;
                hiddenInput.setAttribute('data-filter-param', 'true');
                form.appendChild(hiddenInput);
            }
        });
    }


    // Modal functions
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';

        // Generate action plans for edit modal if it has existing data
        if (modalId.startsWith('editModal')) {
            const itemId = modalId.replace('editModal', '');
            loadActionPlansData(itemId).then(() => {
                generateActionPlans('edit' + itemId);
            });
        }

        // Add filter parameters to form when modal opens
        const form = modal.querySelector('form');
        if (form) {
            addHiddenFiltersToForm(form);
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    // Function untuk load data action plans dari server
    async function loadActionPlansData(itemId) {
        try {
            const response = await fetch(`/newwarroom/${itemId}/action-plans`);
            const data = await response.json();

            actionPlansData[`edit${itemId}`] = {
                actionPlans: [],
                updateActionPlans: [],
                statusActionPlans: []
            };

            data.forEach(actionPlan => {
                const index = actionPlan.plan_number - 1;
                actionPlansData[`edit${itemId}`].actionPlans[index] = actionPlan.action_plan || '';
                actionPlansData[`edit${itemId}`].updateActionPlans[index] = actionPlan.update_action_plan || '';
                actionPlansData[`edit${itemId}`].statusActionPlans[index] = actionPlan.status_action_plan || 'Open';
            });

        } catch (error) {
            console.error('Error loading action plans:', error);
            actionPlansData[`edit${itemId}`] = {
                actionPlans: [],
                updateActionPlans: [],
                statusActionPlans: []
            };
        }
    }

    // Enable edit untuk action plan
    function enableEditActionPlan(itemId) {
        const input = document.getElementById(`jumlahActionEdit${itemId}`);
        const changeBtn = event.target;
        const generateBtn = document.getElementById(`generateBtn${itemId}`);

        input.removeAttribute('readonly');
        input.readOnly = false;
        input.focus();
        changeBtn.style.display = 'none';
        generateBtn.style.display = 'block';
    }

    // Auto resize textarea function
    function autoResizeTextarea(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    }

    // Setup textarea auto-resize untuk container tertentu
    function setupTextareaAutoResize(container = document) {
        const textareas = container.querySelectorAll('.wr-form-textarea');
        textareas.forEach(textarea => {
            autoResizeTextarea(textarea);
            textarea.addEventListener('input', function() {
                autoResizeTextarea(this);
            });
        });
    }

    // Generate dynamic action plan fields
    function generateActionPlans(modalType) {
        const {
            containerSuffix
        } = getModalInfo(modalType);
        const inputId = `jumlahAction${containerSuffix}`;
        const jumlahInput = document.getElementById(inputId);
        const jumlah = parseInt(jumlahInput.value);
        const container = document.getElementById(`actionPlansContainer${containerSuffix}`);

        const isEditAutoLoad = modalType.startsWith('edit') && container.innerHTML.trim() === '';

        // Validasi input
        if (!jumlah || jumlah < 1) {
            alert('Masukkan jumlah action plan yang valid (minimal 1)');
            jumlahInput.focus();
            return;
        }

        // Konfirmasi jika sudah ada action plans (kecuali auto load)
        if (!isEditAutoLoad && container.innerHTML.trim() !== '') {
            if (!confirm(`Akan mengganti dengan ${jumlah} action plans. Data yang ada akan hilang. Lanjutkan?`)) {
                return;
            }
        }

        // Reset button untuk edit modal
        if (modalType.startsWith('edit') && !isEditAutoLoad) {
            const itemId = modalType.replace('edit', '');
            const changeBtn = document.querySelector(`#editModal${itemId} .wr-btn-secondary`);
            const generateBtn = document.getElementById(`generateBtn${itemId}`);

            changeBtn.style.display = 'block';
            generateBtn.style.display = 'none';
            jumlahInput.setAttribute('readonly', 'readonly');
            jumlahInput.readOnly = true;
        }

        // Generate header dengan tombol add
        let html = `
        <div class="jumlah-action-highlight">
            <div>
                <i class="fas fa-tasks"></i> 
                <strong>Total Action Plans: ${jumlah}</strong>
            </div>
            <button type="button" class="add-action-btn" onclick="addActionPlan('${modalType}')" title="Tambah Action Plan">
                <i class="fas fa-plus"></i> Add Action Plan
            </button>
        </div>
    `;

        // Generate action plans
        for (let i = 1; i <= parseInt(jumlah); i++) {
            let existingActionPlan = '';
            let existingUpdatePlan = '';
            let existingStatus = 'Open';

            if (modalType.startsWith('edit') && actionPlansData[modalType]) {
                existingActionPlan = actionPlansData[modalType].actionPlans[i - 1] || '';
                existingUpdatePlan = actionPlansData[modalType].updateActionPlans[i - 1] || '';
                existingStatus = actionPlansData[modalType].statusActionPlans[i - 1] || 'Open';
            }

            html += createActionPlanHtml(i, modalType, existingActionPlan, existingUpdatePlan, existingStatus);
        }

        container.innerHTML = html;
        setupTextareaAutoResize(container);
    }

    // Fungsi untuk membuat HTML action plan
    function createActionPlanHtml(index, modalType, existingActionPlan = '', existingUpdatePlan = '', existingStatus = 'Open') {
        return `
        <div class="action-plan-section">
            <div class="action-plan-header">
                <div class="action-plan-number">${index}</div>
                <h4 class="action-plan-title">Action Plan ${index}</h4>
                <button type="button" class="remove-action-btn" onclick="removeActionPlan('${modalType}', ${index})" title="Hapus Action Plan">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="action-plan-grid">
                <div class="wr-form-group">
                    <label class="wr-form-label">Action Plan ${index}</label>
                    <textarea class="wr-form-textarea" name="action_plan_${index}" rows="3"  
                        placeholder="Masukkan detail action plan ${index}...">${existingActionPlan}</textarea>
                </div>
                
                <div class="wr-form-group">
                    <label class="wr-form-label">Update Action Plan ${index}</label>
                    <textarea class="wr-form-textarea" name="update_action_plan_${index}" rows="3"
                        placeholder="Update progress untuk action plan ${index}...">${existingUpdatePlan}</textarea>
                </div>
                
                <div class="status-grid">
                    <div class="wr-form-group">
                        <label class="wr-form-label">Status Action Plan ${index}</label>
                        <select class="wr-form-select" name="status_action_plan_${index}">
                            <option value="Open" ${existingStatus === 'Open' ? 'selected' : ''}>Open</option>
                            <option value="Progress" ${existingStatus === 'Progress' ? 'selected' : ''}>Progress</option>
                            <option value="Need Discuss" ${existingStatus === 'Need Discuss' ? 'selected' : ''}>Need Discuss</option>
                            <option value="Eskalasi" ${existingStatus === 'Eskalasi' ? 'selected' : ''}>Eskalasi</option>
                            <option value="Done" ${existingStatus === 'Done' ? 'selected' : ''}>Done</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    `;
    }

    // Fungsi untuk menambah action plan baru
    function addActionPlan(modalType) {
        const {
            containerSuffix
        } = getModalInfo(modalType);
        const container = document.getElementById(`actionPlansContainer${containerSuffix}`);
        const existingPlans = container.querySelectorAll('.action-plan-section');
        const newIndex = existingPlans.length + 1;

        // Update jumlah di input
        const inputId = `jumlahAction${containerSuffix}`;
        const jumlahInput = document.getElementById(inputId);
        jumlahInput.value = newIndex;

        // Buat action plan baru
        const newActionPlanHtml = createActionPlanHtml(newIndex, modalType, '', '', 'Open');
        container.insertAdjacentHTML('beforeend', newActionPlanHtml);

        // Update header total
        updateTotalActionPlansHeader(container, newIndex);

        // Setup textarea auto-resize untuk element baru
        const newSection = container.querySelector('.action-plan-section:last-child');
        setupTextareaAutoResize(newSection);
    }

    // Fungsi untuk menghapus action plan
    function removeActionPlan(modalType, planIndex) {
        if (!confirm('Apakah Anda yakin ingin menghapus action plan ini?')) {
            return;
        }

        const {
            containerSuffix
        } = getModalInfo(modalType);
        const container = document.getElementById(`actionPlansContainer${containerSuffix}`);
        const actionPlanSections = container.querySelectorAll('.action-plan-section');

        if (actionPlanSections.length <= 1) {
            alert('Minimal harus ada 1 action plan');
            return;
        }

        // Hapus section
        actionPlanSections[planIndex - 1].remove();

        // Reindex semua action plans
        reindexActionPlans(modalType);

        // Update jumlah di input
        const inputId = `jumlahAction${containerSuffix}`;
        const jumlahInput = document.getElementById(inputId);
        jumlahInput.value = actionPlanSections.length - 1;
    }

    // Fungsi untuk reindex action plans setelah penghapusan
    function reindexActionPlans(modalType) {
        const {
            containerSuffix
        } = getModalInfo(modalType);
        const container = document.getElementById(`actionPlansContainer${containerSuffix}`);
        const actionPlanSections = container.querySelectorAll('.action-plan-section');

        actionPlanSections.forEach((section, index) => {
            const newIndex = index + 1;

            // Update nomor dan judul
            section.querySelector('.action-plan-number').textContent = newIndex;
            section.querySelector('.action-plan-title').textContent = `Action Plan ${newIndex}`;

            // Update semua name attributes
            const textarea1 = section.querySelector('textarea[name^="action_plan_"]');
            const textarea2 = section.querySelector('textarea[name^="update_action_plan_"]');
            const select = section.querySelector('select[name^="status_action_plan_"]');
            const removeBtn = section.querySelector('.remove-action-btn');

            if (textarea1) {
                textarea1.name = `action_plan_${newIndex}`;
                textarea1.placeholder = `Masukkan detail action plan ${newIndex}...`;
            }
            if (textarea2) {
                textarea2.name = `update_action_plan_${newIndex}`;
                textarea2.placeholder = `Update progress untuk action plan ${newIndex}...`;
            }
            if (select) {
                select.name = `status_action_plan_${newIndex}`;
            }
            if (removeBtn) {
                removeBtn.setAttribute('onclick', `removeActionPlan('${modalType}', ${newIndex})`);
            }

            // Update labels
            const labels = section.querySelectorAll('label');
            labels.forEach(label => {
                const text = label.textContent;
                if (text.includes('Action Plan')) {
                    label.innerHTML = text.replace(/Action Plan \d+/, `Action Plan ${newIndex}`);
                }
            });
        });

        // Update header total
        updateTotalActionPlansHeader(container, actionPlanSections.length);
    }

    // Fungsi untuk update header total
    function updateTotalActionPlansHeader(container, total) {
        const header = container.querySelector('.jumlah-action-highlight strong');
        if (header) {
            header.textContent = `Total Action Plans: ${total}`;
        }
    }

    // Form validation dan submission
    function handleFormSubmit(e) {
        e.preventDefault();
        const form = e.target;

        // Tambahkan filter via hidden inputs (optional jika Anda mau aman)
        addHiddenFiltersToForm(form);

        // Atau langsung tambahkan query string ke action form (tanpa hidden)
        const filterQuery = createFilterQueryString();
        if (filterQuery) {
            const separator = form.action.includes('?') ? '&' : '?';
        }

        form.submit();
    }

    // Function untuk handle delete dengan filter parameters
    function handleDelete(deleteUrl, itemId) {
        if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) return;

        const filterQuery = createFilterQueryString();
        const finalUrl = filterQuery ? `${deleteUrl}?${filterQuery}` : deleteUrl;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = finalUrl;

        // Tambahkan CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken.getAttribute('content');
            form.appendChild(csrfInput);
        }

        // Method spoofing DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }

    // Function untuk setup delete buttons
    function setupDeleteButtons() {
        const deleteButtons = document.querySelectorAll('[data-delete-url]');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const deleteUrl = this.getAttribute('data-delete-url');
                const itemId = this.getAttribute('data-item-id');
                handleDelete(deleteUrl, itemId);
            });
        });
    }

    // Function untuk handle navigation dengan filter parameters
    function navigateWithFilters(baseUrl) {
        const queryString = createFilterQueryString();
        const separator = baseUrl.includes('?') ? '&' : '?';
        const finalUrl = queryString ? `${baseUrl}${separator}${queryString}` : baseUrl;
        window.location.href = finalUrl;
    }

    // Event listeners
    function initializeEventListeners() {
        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('wr-modal')) {
                closeModal(event.target.id);
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const openModal = document.querySelector('.wr-modal.show');
                if (openModal) {
                    closeModal(openModal.id);
                }
            }
        });

        // Form handling
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', handleFormSubmit);
        });

        // Setup delete buttons
        setupDeleteButtons();

        // Setup textarea auto-resize
        setupTextareaAutoResize();

        // Setup navigation buttons with filters (jika ada)
        const navButtons = document.querySelectorAll('[data-nav-url]');
        navButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const navUrl = this.getAttribute('data-nav-url');
                navigateWithFilters(navUrl);
            });
        });
    }

    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', initializeEventListeners);

    // Expose functions globally untuk digunakan inline
    window.openModal = openModal;
    window.closeModal = closeModal;
    window.enableEditActionPlan = enableEditActionPlan;
    window.generateActionPlans = generateActionPlans;
    window.addActionPlan = addActionPlan;
    window.removeActionPlan = removeActionPlan;
    window.handleDelete = handleDelete;
    window.navigateWithFilters = navigateWithFilters;
</script>