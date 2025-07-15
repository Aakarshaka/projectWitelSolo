<!-- Modal Edit untuk setiap item -->
<style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap");

    /* Modal Styles - Sesuaikan dengan sn-modal */
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
        margin: 3% auto;
        padding: 0;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        width: 90%;
        max-width: 900px;
        max-height: 90vh;
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
        max-height: calc(90vh - 160px);
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
        min-height: 100px;
        font-family: 'Poppins', sans-serif;
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

    /* Responsive */
    @media (max-width: 768px) {
        .wr-modal-content {
            width: 95%;
            margin: 5% auto;
        }

        .wr-modal-header,
        .wr-modal-body,
        .wr-modal-footer {
            padding: 20px;
        }

        .wr-form-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .wr-form-group.full-width {
            grid-column: span 1;
        }

        .wr-modal-footer {
            flex-direction: column;
        }

        .wr-btn {
            width: 100%;
        }
    }

    body {
        font-family: "Poppins", sans-serif;
    }
</style>

@foreach($warroomData as $item)
<div class="wr-modal" id="editModal{{ $item->id }}">
    <div class="wr-modal-content">
        <div class="wr-modal-header">
            <h2 class="wr-modal-title">Edit Data Warroom</h2>
            <button class="wr-modal-close" onclick="closeModal('editModal{{ $item->id }}')">&times;</button>
        </div>
        <form action="{{ route('newwarroom.update', $item->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="wr-modal-body">
                <div class="wr-form-grid">
                    <div class="wr-form-group">
                        <label class="wr-form-label">Tanggal</label>
                        <input type="date" class="wr-form-input" name="tgl" value="{{ $item->tgl }}">
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
                        <label class="wr-form-label">Action Plan</label>
                        <textarea class="wr-form-textarea" name="action_plan" rows="3">{{ $item->action_plan }}</textarea>
                    </div>

                    <div class="wr-form-group full-width">
                        <label class="wr-form-label">Support Needed</label>
                        <textarea class="wr-form-textarea" name="support_needed" rows="3">{{ $item->support_needed }}</textarea>
                    </div>

                    <div class="wr-form-group full-width">
                        <label class="wr-form-label">Info Kompetitor</label>
                        <textarea class="wr-form-textarea" name="info_kompetitor" rows="3">{{ $item->info_kompetitor }}</textarea>
                    </div>

                    <div class="wr-form-group">
                        <label class="wr-form-label">Jumlah Action Plan</label>
                        <input type="number" class="wr-form-input" name="jumlah_action_plan" value="{{ $item->jumlah_action_plan }}" min="0">
                    </div>

                    <div class="wr-form-group">
                        <label class="wr-form-label">Status Action Plan</label>
                        <select class="wr-form-select" name="status_action_plan">
                            <option value="Open" {{ $item->status_action_plan == 'Open' ? 'selected' : '' }}>Open</option>
                            <option value="Need Discuss" {{ $item->status_action_plan == 'Need Discuss' ? 'selected' : '' }}>Need Discuss</option>
                            <option value="Eskalasi" {{ $item->status_action_plan == 'Eskalasi' ? 'selected' : '' }}>Eskalasi</option>
                            <option value="Done" {{ $item->status_action_plan == 'Done' ? 'selected' : '' }}>Done</option>
                        </select>
                    </div>

                    <div class="wr-form-group full-width">
                        <label class="wr-form-label">Update Action Plan</label>
                        <textarea class="wr-form-textarea" name="update_action_plan" rows="3">{{ $item->update_action_plan }}</textarea>
                    </div>
                </div>
            </div>
            <div class="wr-modal-footer">
                <button type="submit" class="wr-btn wr-btn-primary">Update Warroom Data</button>
            </div>
        </form>
    </div>
</div>
@endforeach

<!-- Modal Tambah -->
<div class="wr-modal" id="addModal">
    <div class="wr-modal-content">
        <div class="wr-modal-header">
            <h2 class="wr-modal-title">Tambah Data Warroom</h2>
            <button class="wr-modal-close" onclick="closeModal('addModal')">&times;</button>
        </div>
        <form action="{{ route('newwarroom.store') }}" method="POST">
            @csrf
            <div class="wr-modal-body">
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
                        <label class="wr-form-label">Action Plan</label>
                        <textarea class="wr-form-textarea" name="action_plan" rows="3"></textarea>
                    </div>

                    <div class="wr-form-group full-width">
                        <label class="wr-form-label">Support Needed</label>
                        <textarea class="wr-form-textarea" name="support_needed" rows="3"></textarea>
                    </div>

                    <div class="wr-form-group full-width">
                        <label class="wr-form-label">Info Kompetitor</label>
                        <textarea class="wr-form-textarea" name="info_kompetitor" rows="3"></textarea>
                    </div>

                    <div class="wr-form-group">
                        <label class="wr-form-label">Jumlah Action Plan</label>
                        <input type="number" class="wr-form-input" name="jumlah_action_plan" min="0" value="0">
                    </div>

                    <div class="wr-form-group">
                        <label class="wr-form-label">Status Action Plan</label>
                        <select class="wr-form-select" name="status_action_plan">
                            <option value="Open" selected>Open</option>
                            <option value="Need Discuss">Need Discuss</option>
                            <option value="Eskalasi">Eskalasi</option>
                            <option value="Done">Done</option>
                        </select>
                    </div>

                    <div class="wr-form-group full-width">
                        <label class="wr-form-label">Update Action Plan</label>
                        <textarea class="wr-form-textarea" name="update_action_plan" rows="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="wr-modal-footer">
                <button type="submit" class="wr-btn wr-btn-primary">Add Warroom Data</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Modal functions
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('wr-modal')) {
            closeModal(event.target.id);
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            const openModal = document.querySelector('.wr-modal.show');
            if (openModal) {
                closeModal(openModal.id);
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Auto-resize textarea
        const textareas = document.querySelectorAll('.wr-form-textarea');
        textareas.forEach(textarea => {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });
        });

        // Form validation
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.style.borderColor = '#dc3545';
                        isValid = false;
                    } else {
                        field.style.borderColor = '#e6e1e8';
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('Mohon lengkapi field Agenda yang wajib diisi!');
                }
            });
        });

        // Auto dismiss alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.display = 'none';
            });
        }, 5000);
    });
</script>