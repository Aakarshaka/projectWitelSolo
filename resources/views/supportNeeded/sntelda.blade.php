@extends('layouts.app')

@section('title', 'TELDA')

@section('content')
<div class="collab-container">
    <!-- Header Section -->
    <div class="collab-header">
        <div class="header-left">
            <h1 class="page-title">SUPPORT NEEDED TELDA</h1>
        </div>
        <div class="collab-header-stats">
            <div class="stat-item">
                <span class="stat-label">Total</span>
                <div class="stat-value total-stat">{{ $total }}</div>
            </div>
            <div class="stat-item">
                <span class="stat-label">Close</span>
                <div class="stat-value close-stat">{{ $close }} ({{ $closePercentage }}%)</div>
            </div>
            <div class="stat-item">
                <span class="stat-label">Actual Progress</span>
                <div class="stat-value progress-stat">{{ round($actualProgress, 1) }}%</div>
            </div>
            <div class="stat-item">
                <a href="#" class="stat-value add-stat add-button" data-bs-toggle="modal" data-bs-target="#addModal">ADD+</a>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="collab-table-container">
        <table class="collab-table">
            <thead>
                <tr>
                    <th class="col-no">NO</th>
                    <th class="col-event">EVENT</th>
                    <th class="col-unit">UNIT/TELDA</th>
                    <th class="col-start">START DATE</th>
                    <th class="col-end">END DATE</th>
                    <th class="col-notes">NOTES TO FOLLOW UP</th>
                    <th class="col-pic">UIC</th>
                    <th class="col-unit-collab">UNIT COLLABORATOR</th>
                    <th class="col-complete">% COMPLETE</th>
                    <th class="col-status">STATUS</th>
                    <th class="col-respond">RESPOND UIC</th>
                    <th class="col-action">ACTION</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allsntelda as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->event }}</td>
                    <td>{{ $item->unit }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->start_date)->format('d-M-y') }}</td>
                    <td>{{ $item->end_date ? \Carbon\Carbon::parse($item->end_date)->format('d-M-y') : '-' }}</td>
                    <td>{!! nl2br(e($item->notes)) !!}</td>
                    <td>{{ $item->uic }}</td>
                    <td>{{ $item->unit_collab }}</td>
                    <td>{{ $item->complete }}%</td>
                    <td>{{ $item->status ?: '-'}}</td>
                    <td>{!! nl2br(e($item->respond)) !!}</td>
                    <td>
                        <a href="#" class="action-button action-edit"
                            data-id="{{ $item->id }}"
                            data-event="{{ $item->event }}"
                            data-unit="{{ $item->unit }}"
                            data-start="{{ $item->start_date }}"
                            data-end="{{ $item->end_date }}"
                            data-notes="{{ $item->notes }}"
                            data-uic="{{ $item->uic }}"
                            data-unitcollab="{{ $item->unit_collab }}"
                            data-complete="{{ $item->complete }}"
                            data-status="{{ $item->status }}"
                            data-respond="{{ $item->respond }}"
                            onclick="openEditModal(this)">EDIT
                        </a>

                        <form action="{{ route('sntelda.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-button action-delete" onclick="return confirm('Yakin ingin hapus data ini?')">DELETE</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pop Up Edit -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <form id="editForm" method="POST" onsubmit="return validateEditForm()">
                        @csrf
                        @method('PUT')
                        <div class="modal-header" style="background-color: #4A0E4E; color: #fff;">
                            <h5 class="modal-title">Edit Data Support Needed TELDA</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                            <div class="mb-3">
                                <label class="form-label">Event</label>
                                <input type="text" name="event" id="editEvent" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Unit/Telda</label>
                                <input type="text" name="unit" id="editUnit" class="form-control" required>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" name="start_date" id="editStartDate" class="form-control" required>
                                </div>
                                <div class="col">
                                    <label class="form-label">End Date</label>
                                    <input type="date" name="end_date" id="editEndDate" class="form-control" onchange="validateDate(this)">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" id="editNotes" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label">UIC</label>
                                    <input type="text" name="uic" id="editUIC" class="form-control" required>
                                </div>
                                <div class="col">
                                    <label class="form-label">Unit Collaborator</label>
                                    <input type="text" name="unit_collab" id="editUnitCollab" class="form-control">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">% Complete</label>
                                <input type="number" name="complete" id="editComplete" class="form-control" min="0" max="100" oninput="checkComplete(this, 'editStatus')">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" id="editStatus" class="form-select" onchange="checkStatus(this, 'editComplete')">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Done">Done</option>
                                    <option value="Eskalasi">Eskalasi</option>
                                    <option value="Progress">Progress</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Respond UIC</label>
                                <textarea name="respond" id="editRespond" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add Pop Up -->
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <form action="{{ route('sntelda.store') }}" method="POST" onsubmit="return validateAddForm()">
                        @csrf
                        <div class="modal-header" style="background-color: #4A0E4E; color: #fff;">
                            <h5 class="modal-title" id="addModalLabel">Tambah Data Support Needed TELDA</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="mb-3">
                                <label class="form-label">Event</label>
                                <input type="text" name="event" class="form-control" placeholder="Masukkan nama event" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Unit/Telda</label>
                                <input type="text" name="unit" class="form-control" placeholder="Masukkan unit atau Telda" >
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" name="start_date" class="form-control" >
                                </div>
                                <div class="col">
                                    <label class="form-label">End Date</label>
                                    <input type="date" name="end_date" class="form-control" onchange="validateDate(this)">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Notes to Follow Up</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Masukkan catatan atau tindak lanjut"></textarea>
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label">UIC</label>
                                    <input type="text" name="uic" class="form-control" placeholder="Masukkan UIC" >
                                </div>
                                <div class="col">
                                    <label class="form-label">Unit Collaborator</label>
                                    <input type="text" name="unit_collab" class="form-control" placeholder="Masukkan Unit Collaborator">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">% Complete</label>
                                <input type="number" name="complete" class="form-control" id="addComplete" min="0" max="100" placeholder="Masukkan progress" oninput="checkComplete(this, 'addStatus')">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" id="addStatus" onchange="checkStatus(this, 'addComplete')">
                                    <option value="" selected disabled>-- Pilih Status --</option>
                                    <option value="Done">Done</option>
                                    <option value="Eskalasi">Eskalasi</option>
                                    <option value="Progress">Progress</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Respond UIC</label>
                                <textarea name="respond" class="form-control" rows="3" placeholder="Masukkan respon UIC"></textarea>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Buka Edit Modal + isi data
    function openEditModal(button) {
        let id = button.getAttribute('data-id');
        document.getElementById('editForm').action = "/sntelda/" + id;

        document.getElementById('editEvent').value = button.getAttribute('data-event');
        document.getElementById('editUnit').value = button.getAttribute('data-unit');
        document.getElementById('editStartDate').value = button.getAttribute('data-start');
        document.getElementById('editEndDate').value = button.getAttribute('data-end');
        document.getElementById('editNotes').value = button.getAttribute('data-notes');
        document.getElementById('editUIC').value = button.getAttribute('data-uic');
        document.getElementById('editUnitCollab').value = button.getAttribute('data-unitcollab');
        document.getElementById('editComplete').value = button.getAttribute('data-complete');
        document.getElementById('editStatus').value = button.getAttribute('data-status');
        document.getElementById('editRespond').value = button.getAttribute('data-respond');

        checkComplete(document.getElementById('editComplete'), 'editStatus');

        var modal = new bootstrap.Modal(document.getElementById('editModal'));
        modal.show();
    }

    // Validasi saat complete 100%, status wajib 'Done'
    function checkComplete(input, statusSelectorId) {
        const statusSelect = document.getElementById(statusSelectorId);
        const value = parseInt(input.value);

        if (value === 0) {
            statusSelect.value = "";
            for (let i = 0; i < statusSelect.options.length; i++) {
                statusSelect.options[i].disabled = false;
            }
        } else if (value === 100) {
            statusSelect.value = 'Done';
            for (let i = 0; i < statusSelect.options.length; i++) {
                if (statusSelect.options[i].value !== 'Done' && statusSelect.options[i].value !== "") {
                    statusSelect.options[i].disabled = true;
                }
            }
        } else {
            for (let i = 0; i < statusSelect.options.length; i++) {
                statusSelect.options[i].disabled = false;
            }
        }
    }


    // Validasi form Add Modal saat submit
    function validateAddForm() {
        const startDate = document.querySelector('#addModal input[name="start_date"]').value;
        const endDate = document.querySelector('#addModal input[name="end_date"]').value;
        const complete = parseInt(document.querySelector('#addModal input[name="complete"]').value);
        const status = document.getElementById('addStatus').value;

        if (endDate && startDate && endDate < startDate) {
            alert("End Date tidak boleh sebelum Start Date.");
            return false;
        }

        if (complete === 100 && status !== "Done") {
            alert("Jika progress 100%, status wajib 'Done'.");
            return false;
        }

        if (complete === 0 && status !== "") {
            alert("Jika progress 0%, status wajib kosong.");
            return false;
        }

        return true;
    }


    // Validasi form Edit Modal saat submit
    function validateEditForm() {
        const startDate = document.getElementById('editStartDate').value;
        const endDate = document.getElementById('editEndDate').value;
        const complete = parseInt(document.getElementById('editComplete').value);
        const status = document.getElementById('editStatus').value;

        if (endDate && startDate && endDate < startDate) {
            alert("End Date tidak boleh sebelum Start Date.");
            return false;
        }

        if (complete === 100 && status !== "Done") {
            alert("Jika progress 100%, status wajib 'Done'.");
            return false;
        }

        if (complete === 0 && status !== "") {
            alert("Jika progress 0%, status wajib kosong.");
            return false;
        }

        return true;
    }

    function checkStatus(selectElement, completeInputId) {
        const completeInput = document.getElementById(completeInputId);
        if (selectElement.value === 'Done') {
            completeInput.value = 100;
            completeInput.readOnly = true;
        } else {
            completeInput.readOnly = false;
        }
    }
</script>
@endsection