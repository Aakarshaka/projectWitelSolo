<!-- Modal Edit untuk setiap item -->
<style>
    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal.show {
        display: block;
    }

    .modal-dialog {
        position: relative;
        width: 90%;
        max-width: 800px;
        margin: 50px auto;
        background: white;
        border-radius: 12px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        max-height: calc(100vh - 100px);
    }

    .modal-header {
        background: linear-gradient(135deg, #8b1538 0%, #4a0e4e 100%);
        color: white;
        padding: 20px 30px;
        border-bottom: 1px solid #e6e1e8;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 10;
        /* pastikan di atas isi konten */
    }

    .modal-header.bg-warning {
        background: #fbbf24;
        color: #92400e;
    }

    .modal-header.bg-success {
        background: #22c55e;
        color: white;
    }

    .modal-title {
        font-size: 20px;
        font-weight: 600;
        margin: 0;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: inherit;
        opacity: 0.7;
    }

    .modal-close:hover {
        opacity: 1;
    }

    .modal-body {
        padding: 30px;
        overflow-y: auto;
        /* ✅ scroll di sini */
        flex-grow: 1;
        /* ✅ biar body ambil sisa tinggi */
        max-height: calc(90vh - 160px);
        /* ✅ sesuaikan tinggi header + footer */
    }

    .modal-footer {
        padding: 20px 30px;
        border-top: 1px solid #e6e1e8;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        position: sticky;
        bottom: 0;
        background: white;
        z-index: 10;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .btn-primary {
        background: #4a0e4e;
        color: white;
    }

    .btn-primary:hover {
        background: #3a0b3e;
    }

    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-row .form-group {
        flex: 1;
        margin-bottom: 0;
    }

    .form-label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        color: #2a1b2d;
        font-size: 14px;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e6e1e8;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #4a0e4e;
        box-shadow: 0 0 0 3px rgba(74, 14, 78, 0.1);
    }

    .form-control.is-invalid {
        border-color: #dc3545;
    }

    .required {
        color: #dc3545;
    }

    .btn-group-vertical {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .text-muted {
        color: #6b7280;
    }

    .fas {
        margin-right: 5px;
    }
</style>

@foreach($warroomData as $item)
    <div class="modal" id="editModal{{ $item->id }}">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('newwarroom.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-edit"></i> Edit Data Warroom
                        </h5>
                        <button type="button" class="modal-close"
                            onclick="closeModal('editModal{{ $item->id }}')">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="tgl_{{ $item->id }}" class="form-label">Tanggal <span
                                        class="required">*</span></label>
                                <input type="date" class="form-control" id="tgl_{{ $item->id }}" name="tgl"
                                    value="{{ $item->tgl }}" required>
                            </div>
                            <div class="form-group">
                                <label for="agenda_{{ $item->id }}" class="form-label">Agenda <span
                                        class="required">*</span></label>
                                <input type="text" class="form-control" id="agenda_{{ $item->id }}" name="agenda"
                                    value="{{ $item->agenda }}" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="uic_{{ $item->id }}" class="form-label">UIC <span
                                        class="required">*</span></label>
                                <input type="text" class="form-control" id="uic_{{ $item->id }}" name="uic"
                                    value="{{ $item->uic }}" required>
                            </div>
                            <div class="form-group">
                                <label for="peserta_{{ $item->id }}" class="form-label">Peserta <span
                                        class="required">*</span></label>
                                <input type="text" class="form-control" id="peserta_{{ $item->id }}" name="peserta"
                                    value="{{ $item->peserta }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pembahasan_{{ $item->id }}" class="form-label">Pembahasan <span
                                    class="required">*</span></label>
                            <textarea class="form-control" id="pembahasan_{{ $item->id }}" name="pembahasan" rows="3"
                                required>{{ $item->pembahasan }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="action_plan_{{ $item->id }}" class="form-label">Action Plan <span
                                    class="required">*</span></label>
                            <textarea class="form-control" id="action_plan_{{ $item->id }}" name="action_plan" rows="3"
                                required>{{ $item->action_plan }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="support_needed_{{ $item->id }}" class="form-label">Support Needed</label>
                            <textarea class="form-control" id="support_needed_{{ $item->id }}" name="support_needed"
                                rows="3">{{ $item->support_needed }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="info_kompetitor_{{ $item->id }}" class="form-label">Info Kompetitor</label>
                            <textarea class="form-control" id="info_kompetitor_{{ $item->id }}" name="info_kompetitor"
                                rows="3">{{ $item->info_kompetitor }}</textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="jumlah_action_plan_{{ $item->id }}" class="form-label">Jumlah Action
                                    Plan</label>
                                <input type="number" class="form-control" id="jumlah_action_plan_{{ $item->id }}"
                                    name="jumlah_action_plan" value="{{ $item->jumlah_action_plan }}" min="0">
                            </div>
                            <div class="form-group">
                                <label for="status_action_plan_{{ $item->id }}" class="form-label">Status Action
                                    Plan</label>
                                <select class="form-control" id="status_action_plan_{{ $item->id }}"
                                    name="status_action_plan">
                                    <option value="Open" {{ $item->status_action_plan == 'Open' ? 'selected' : '' }}>Open
                                    </option>
                                    <option value="Progress" {{ $item->status_action_plan == 'Progress' ? 'selected' : '' }}>
                                        Progress</option>
                                    <option value="Closed" {{ $item->status_action_plan == 'Closed' ? 'selected' : '' }}>
                                        Closed</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="update_action_plan_{{ $item->id }}" class="form-label">Update Action Plan</label>
                            <textarea class="form-control" id="update_action_plan_{{ $item->id }}" name="update_action_plan"
                                rows="3">{{ $item->update_action_plan }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- Modal Tambah -->
<div class="modal" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('newwarroom.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus"></i> Tambah Data Warroom
                    </h5>
                    <button type="button" class="modal-close" onclick="closeModal('addModal')">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tgl" class="form-label">Tanggal <span class="required">*</span></label>
                            <input type="date" class="form-control" id="tgl" name="tgl" required>
                        </div>
                        <div class="form-group">
                            <label for="agenda" class="form-label">Agenda <span class="required">*</span></label>
                            <input type="text" class="form-control" id="agenda" name="agenda" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="uic" class="form-label">UIC <span class="required">*</span></label>
                            <input type="text" class="form-control" id="uic" name="uic" required>
                        </div>
                        <div class="form-group">
                            <label for="peserta" class="form-label">Peserta <span class="required">*</span></label>
                            <input type="text" class="form-control" id="peserta" name="peserta" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pembahasan" class="form-label">Pembahasan <span class="required">*</span></label>
                        <textarea class="form-control" id="pembahasan" name="pembahasan" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="action_plan" class="form-label">Action Plan <span class="required">*</span></label>
                        <textarea class="form-control" id="action_plan" name="action_plan" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="support_needed" class="form-label">Support Needed</label>
                        <textarea class="form-control" id="support_needed" name="support_needed" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="info_kompetitor" class="form-label">Info Kompetitor</label>
                        <textarea class="form-control" id="info_kompetitor" name="info_kompetitor" rows="3"></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="jumlah_action_plan" class="form-label">Jumlah Action Plan</label>
                            <input type="number" class="form-control" id="jumlah_action_plan" name="jumlah_action_plan"
                                min="0" value="0">
                        </div>
                        <div class="form-group">
                            <label for="status_action_plan" class="form-label">Status Action Plan</label>
                            <select class="form-control" id="status_action_plan" name="status_action_plan">
                                <option value="Open" selected>Open</option>
                                <option value="Progress">Progress</option>
                                <option value="Closed">Closed</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="update_action_plan" class="form-label">Update Action Plan</label>
                        <textarea class="form-control" id="update_action_plan" name="update_action_plan"
                            rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="sn-footer-wr">Powered by <strong>GIAT CORE</strong></div>
</div>


<script>
    // Modal functions
    function openModal(modalId) {
        document.getElementById(modalId).classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('show');
        document.body.style.overflow = 'auto';

        // Reset form if it's add modal
        if (modalId === 'addModal') {
            const form = document.querySelector('#addModal form');
            if (form) {
                form.reset();
                form.querySelectorAll('.is-invalid').forEach(field => {
                    field.classList.remove('is-invalid');
                });
            }
        }
    }

    // Close modal when clicking outside
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('modal')) {
            closeModal(e.target.id);
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const activeModal = document.querySelector('.modal.show');
            if (activeModal) {
                closeModal(activeModal.id);
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Auto-resize textarea
        const textareas = document.querySelectorAll('textarea');
        textareas.forEach(textarea => {
            textarea.addEventListener('input', function () {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });
        });

        // Form validation
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function (e) {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('Mohon lengkapi semua field yang wajib diisi!');
                }
            });
        });

        // Auto dismiss alerts
        setTimeout(function () {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.display = 'none';
            });
        }, 5000);
    });
</script>