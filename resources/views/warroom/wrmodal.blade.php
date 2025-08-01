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
        max-height: 95vh;
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
        flex-shrink: 0;
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
        flex-shrink: 0;
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
    }

    /* Action Plan Input Container */
    .action-plan-input-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 15px;
        align-items: end;
    }

    .edit-action-controls {
        display: flex;
        gap: 10px;
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
        justify-content: space-between;
        margin-bottom: 15px;
        padding: 12px 15px;
        border-radius: 8px;
        background: linear-gradient(135deg, #8b1538 0%, #4a0e4e 100%);
        color: white;
    }

    .action-plan-number {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
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
        color: white;
        margin: 0;
        flex-grow: 1;
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
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #8b1538 0%, #4a0e4e 100%);
        color: white;
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

    /* Button Styles */
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

    /* Responsive Design */
    @media (max-width: 768px) {
        .wr-modal-content {
            width: 95%;
            margin: 1% auto;
            max-height: 98vh;
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

        .action-plan-input-container {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .edit-action-controls {
            justify-content: flex-start;
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

        .jumlah-action-highlight {
            flex-direction: column;
            gap: 10px;
            text-align: center;
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
                            @php
                            $agenda_options = [
                            '1 ON 1 AM', '1 ON 1 TELDA', 'WAR', 'FORUM TIF',
                            'FORUM TSEL', 'FORUM GSD', 'REVIEW KPI', 'OTHERS'
                            ];
                            @endphp
                            @foreach($agenda_options as $agenda)
                            <option value="{{ $agenda }}">{{ $agenda }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="wr-form-group">
                        <label class="wr-form-label">UIC</label>
                        <select class="wr-form-select" name="uic">
                            <option value="">Select UIC</option>
                            @php
                            $uic_options = [
                            'TELDA BLORA', 'TELDA BOYOLALI', 'TELDA JEPARA', 'TELDA KLATEN',
                            'TELDA KUDUS', 'TELDA MEA SOLO', 'TELDA PATI', 'TELDA PURWODADI',
                            'TELDA REMBANG', 'TELDA SRAGEN', 'TELDA WONOGIRI', 'BS', 'GS',
                            'RLEGS', 'RSO REGIONAL', 'RSO WITEL', 'ED', 'TIF', 'TSEL',
                            'GSD', 'SSGS', 'PRQ', 'RSMES', 'BPPLP', 'SSS', 'LESA V', 'RWS'
                            ];
                            @endphp
                            @foreach($uic_options as $uic)
                            <option value="{{ $uic }}">{{ $uic }}</option>
                            @endforeach
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

                    <div class="wr-form-group full-width">
                        <div class="action-plan-input-container">
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
                            @foreach($agenda_options as $agenda)
                            <option value="{{ $agenda }}" {{ $item->agenda == $agenda ? 'selected' : '' }}>{{ $agenda }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="wr-form-group">
                        <label class="wr-form-label">UIC</label>
                        <select class="wr-form-select" name="uic">
                            <option value="">Select UIC</option>
                            @foreach($uic_options as $uic)
                            <option value="{{ $uic }}" {{ $item->uic == $uic ? 'selected' : '' }}>{{ $uic }}</option>
                            @endforeach
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

                    <div class="wr-form-group full-width">
                        <div class="action-plan-input-container">
                            <div>
                                <label class="wr-form-label">Jumlah Action Plan <span class="required">*</span></label>
                                <input type="number" class="wr-form-input" name="jumlah_action_plan" id="jumlahActionEdit{{ $item->id }}"
                                    min="1" placeholder="Masukkan jumlah..." value="{{ $item->jumlah_action_plan }}" required readonly>
                            </div>
                            <div class="edit-action-controls">
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
                <button type="submit" class="wr-btn wr-btn-primary">Update Warroom Data</button>
            </div>
        </form>
    </div>
</div>
@endforeach

<script>
    // Warroom Modal JavaScript - Optimized
    (function() {
        'use strict';

        // Global state
        let actionPlansData = {};

        // Constants
        const STATUS_OPTIONS = ['Open', 'Progress', 'Need Discuss', 'Eskalasi', 'Done'];
        const FILTER_KEYS = ['bulan', 'tahun', 'uic', 'search', 'status'];

        // Utility Functions
        const getModalInfo = (modalType) => {
            const isEdit = modalType.startsWith('edit');
            const itemId = isEdit ? modalType.replace('edit', '') : null;
            const containerSuffix = modalType.charAt(0).toUpperCase() + modalType.slice(1);
            return {
                isEdit,
                itemId,
                containerSuffix
            };
        };

        const getCurrentFilters = () => {
            const urlParams = new URLSearchParams(window.location.search);
            const filters = {};

            FILTER_KEYS.forEach(key => {
                const value = urlParams.get(key);
                if (value && value.trim() !== '' && value !== 'all') {
                    filters[key] = value;
                }
            });

            return filters;
        };

        const createFilterQueryString = () => {
            return new URLSearchParams(getCurrentFilters()).toString();
        };

        const addHiddenFiltersToForm = (form) => {
            // Remove existing hidden inputs
            const existingHiddenInputs = form.querySelectorAll('input[data-filter-param="true"]');
            existingHiddenInputs.forEach(input => input.remove());

            // Add current filter values
            FILTER_KEYS.forEach(name => {
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
        };

        // Modal Functions
        const openModal = (modalId) => {
            const modal = document.getElementById(modalId);
            if (!modal) return;

            modal.classList.add('show');
            document.body.style.overflow = 'hidden';

            // Generate action plans for edit modal if needed
            if (modalId.startsWith('editModal')) {
                const itemId = modalId.replace('editModal', '');
                loadActionPlansData(itemId).then(() => {
                    generateActionPlans('edit' + itemId);
                });
            }

            // Add filter parameters to form
            const form = modal.querySelector('form');
            if (form) {
                addHiddenFiltersToForm(form);
            }
        };

        const closeModal = (modalId) => {
            const modal = document.getElementById(modalId);
            if (!modal) return;

            modal.classList.remove('show');
            document.body.style.overflow = 'auto';
        };

        // Action Plans Data Loading
        const loadActionPlansData = async (itemId) => {
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
        };

        // Action Plan Management
        const enableEditActionPlan = (itemId) => {
            const input = document.getElementById(`jumlahActionEdit${itemId}`);
            const changeBtn = event.target;
            const generateBtn = document.getElementById(`generateBtn${itemId}`);

            if (!input || !changeBtn || !generateBtn) return;

            input.removeAttribute('readonly');
            input.readOnly = false;
            input.focus();
            changeBtn.style.display = 'none';
            generateBtn.style.display = 'block';
        };

        const autoResizeTextarea = (textarea) => {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        };

        const setupTextareaAutoResize = (container = document) => {
            const textareas = container.querySelectorAll('.wr-form-textarea');
            textareas.forEach(textarea => {
                autoResizeTextarea(textarea);
                textarea.addEventListener('input', function() {
                    autoResizeTextarea(this);
                });
            });
        };

        const createStatusOptions = (selectedStatus = 'Open') => {
            return STATUS_OPTIONS.map(status =>
                `<option value="${status}" ${status === selectedStatus ? 'selected' : ''}>${status}</option>`
            ).join('');
        };

        const createActionPlanHtml = (index, modalType, existingActionPlan = '', existingUpdatePlan = '', existingStatus = 'Open') => {
            return `
            <div class="action-plan-section">
                <div class="action-plan-header">
                    <div class="action-plan-number">${index}</div>
                    <h4 class="action-plan-title">Action Plan ${index}</h4>
                    <button type="button" class="remove-action-btn" onclick="removeActionPlan('${modalType}', ${index})" title="Hapus Action Plan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
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
                                ${createStatusOptions(existingStatus)}
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        `;
        };

        const generateActionPlans = (modalType) => {
            const {
                containerSuffix
            } = getModalInfo(modalType);
            const inputId = `jumlahAction${containerSuffix}`;
            const jumlahInput = document.getElementById(inputId);
            const jumlah = parseInt(jumlahInput.value);
            const container = document.getElementById(`actionPlansContainer${containerSuffix}`);

            if (!jumlahInput || !container) return;

            const isEditAutoLoad = modalType.startsWith('edit') && container.innerHTML.trim() === '';

            // Validation
            if (!jumlah || jumlah < 1) {
                alert('Masukkan jumlah action plan yang valid (minimal 1)');
                jumlahInput.focus();
                return;
            }

            // Confirmation for existing plans
            if (!isEditAutoLoad && container.innerHTML.trim() !== '') {
                if (!confirm(`Akan mengganti dengan ${jumlah} action plans. Data yang ada akan hilang. Lanjutkan?`)) {
                    return;
                }
            }

            // Reset buttons for edit modal
            if (modalType.startsWith('edit') && !isEditAutoLoad) {
                const itemId = modalType.replace('edit', '');
                const changeBtn = document.querySelector(`#editModal${itemId} .wr-btn-secondary`);
                const generateBtn = document.getElementById(`generateBtn${itemId}`);

                if (changeBtn && generateBtn && jumlahInput) {
                    changeBtn.style.display = 'block';
                    generateBtn.style.display = 'none';
                    jumlahInput.setAttribute('readonly', 'readonly');
                    jumlahInput.readOnly = true;
                }
            }

            // Generate header
            let html = `
            <div class="jumlah-action-highlight">
                <div>
                    <i class="fas fa-tasks"></i> 
                    <strong>Total Action Plans: ${jumlah}</strong>
                </div>
                <button type="button" class="add-action-btn" onclick="addActionPlan('${modalType}')" title="Tambah Action Plan">
                    <i class="fas fa-plus"></i> Add More
                </button>
            </div>
        `;

            // Generate action plan sections
            for (let i = 1; i <= jumlah; i++) {
                let existingActionPlan = '';
                let existingUpdatePlan = '';
                let existingStatus = 'Open';

                // Load existing data for edit modal
                if (modalType.startsWith('edit') && actionPlansData[modalType]) {
                    const data = actionPlansData[modalType];
                    existingActionPlan = data.actionPlans[i - 1] || '';
                    existingUpdatePlan = data.updateActionPlans[i - 1] || '';
                    existingStatus = data.statusActionPlans[i - 1] || 'Open';
                }

                html += createActionPlanHtml(i, modalType, existingActionPlan, existingUpdatePlan, existingStatus);
            }

            container.innerHTML = html;
            setupTextareaAutoResize(container);
        };

        const addActionPlan = (modalType) => {
            const {
                containerSuffix
            } = getModalInfo(modalType);
            const container = document.getElementById(`actionPlansContainer${containerSuffix}`);
            const jumlahInput = document.getElementById(`jumlahAction${containerSuffix}`);

            if (!container || !jumlahInput) return;

            const currentPlans = container.querySelectorAll('.action-plan-section').length;
            const newPlanNumber = currentPlans + 1;

            // Update jumlah input
            jumlahInput.value = newPlanNumber;

            // Create new action plan HTML
            const newPlanHtml = createActionPlanHtml(newPlanNumber, modalType);

            // Insert before the last closing div
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = newPlanHtml;
            container.appendChild(tempDiv.firstElementChild);

            // Update header
            const headerDiv = container.querySelector('.jumlah-action-highlight strong');
            if (headerDiv) {
                headerDiv.textContent = `Total Action Plans: ${newPlanNumber}`;
            }

            // Setup textarea auto-resize for new elements
            setupTextareaAutoResize(container);

            // Scroll to new action plan
            const newSection = container.lastElementChild;
            if (newSection) {
                newSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        };

        const removeActionPlan = (modalType, planNumber) => {
            const {
                containerSuffix
            } = getModalInfo(modalType);
            const container = document.getElementById(`actionPlansContainer${containerSuffix}`);
            const jumlahInput = document.getElementById(`jumlahAction${containerSuffix}`);

            if (!container || !jumlahInput) return;

            const actionPlanSections = container.querySelectorAll('.action-plan-section');

            if (actionPlanSections.length <= 1) {
                alert('Minimal harus ada 1 action plan');
                return;
            }

            if (!confirm(`Hapus Action Plan ${planNumber}?`)) {
                return;
            }

            // Find and remove the specific section
            const sectionToRemove = Array.from(actionPlanSections).find(section => {
                const numberElement = section.querySelector('.action-plan-number');
                return numberElement && parseInt(numberElement.textContent) === planNumber;
            });

            if (sectionToRemove) {
                sectionToRemove.remove();
            }

            // Renumber remaining sections
            const remainingSections = container.querySelectorAll('.action-plan-section');
            remainingSections.forEach((section, index) => {
                const newNumber = index + 1;

                // Update visual number
                const numberElement = section.querySelector('.action-plan-number');
                if (numberElement) {
                    numberElement.textContent = newNumber;
                }

                // Update title
                const titleElement = section.querySelector('.action-plan-title');
                if (titleElement) {
                    titleElement.textContent = `Action Plan ${newNumber}`;
                }

                // Update form field names and labels
                const formElements = section.querySelectorAll('[name^="action_plan_"], [name^="update_action_plan_"], [name^="status_action_plan_"]');
                formElements.forEach(element => {
                    const currentName = element.name;
                    if (currentName.includes('action_plan_')) {
                        element.name = currentName.replace(/\d+$/, newNumber);
                    }
                });

                // Update labels
                const labels = section.querySelectorAll('.wr-form-label');
                labels.forEach(label => {
                    const text = label.textContent;
                    if (text.includes('Action Plan')) {
                        label.textContent = text.replace(/\d+/, newNumber);
                    }
                });

                // Update placeholders
                const textareas = section.querySelectorAll('.wr-form-textarea');
                textareas.forEach(textarea => {
                    const placeholder = textarea.placeholder;
                    if (placeholder.includes('action plan')) {
                        textarea.placeholder = placeholder.replace(/\d+/g, newNumber);
                    }
                });

                // Update remove button onclick
                const removeBtn = section.querySelector('.remove-action-btn');
                if (removeBtn) {
                    removeBtn.onclick = () => removeActionPlan(modalType, newNumber);
                }
            });

            // Update jumlah input and header
            const newTotal = remainingSections.length;
            jumlahInput.value = newTotal;

            const headerDiv = container.querySelector('.jumlah-action-highlight strong');
            if (headerDiv) {
                headerDiv.textContent = `Total Action Plans: ${newTotal}`;
            }
        };

        // Form Submission Handler
        const handleFormSubmission = () => {
            // Add event listeners to all forms
            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (form.id && (form.id.startsWith('addForm') || form.id.startsWith('editForm'))) {
                    addHiddenFiltersToForm(form);
                }
            });
        };

        // Event Listeners
        const setupEventListeners = () => {
            // Close modal when clicking outside
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('wr-modal')) {
                    const modalId = e.target.id;
                    closeModal(modalId);
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const activeModal = document.querySelector('.wr-modal.show');
                    if (activeModal) {
                        closeModal(activeModal.id);
                    }
                }
            });

            // Handle form submissions
            handleFormSubmission();

            // Auto-resize textareas on page load
            setupTextareaAutoResize();
        };

        // Initialize
        const init = () => {
            setupEventListeners();
            console.log('Warroom Modal JavaScript initialized');
        };

        // Export functions to global scope
        window.openModal = openModal;
        window.closeModal = closeModal;
        window.generateActionPlans = generateActionPlans;
        window.enableEditActionPlan = enableEditActionPlan;
        window.addActionPlan = addActionPlan;
        window.removeActionPlan = removeActionPlan;

        // Initialize when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }

    })();
</script>