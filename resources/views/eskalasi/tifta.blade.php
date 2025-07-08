@extends('layouts.app')

@section('title', 'ESKALASI TO TIF_TA')

@section('content')
<div class="collab-container">
    <!-- Header Section -->
    <div class="collab-header">
        <div class="header-left">
            <h1 class="page-title">ESKALASI TO TIF_TA</h1>
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
                @forelse($alltifta as $index => $item)
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
                            data-route="tifta"
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

                        <form action="{{ route('tifta.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-button action-delete" onclick="return confirm('Yakin ingin hapus data ini?')">DELETE</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="12" style="text-align: center; font-weight: normal;">Belum ada data Eskalasi to TIF-TA</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pop Up Edit -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header" style="background-color: #4A0E4E; color: #fff;">
                            <h5 class="modal-title">Edit Data Eskalasi To TIF_TA</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">

                            <div class="mb-3">
                                <label class="form-label">Event</label>
                                <input type="text" name="event" id="editEvent" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Unit/Telda</label>
                                <input type="text" name="unit" id="editUnit" class="form-control">
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" name="start_date" id="editStartDate" class="form-control">
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
                                    <input type="text" name="uic" id="editUIC" class="form-control">
                                </div>
                                <div class="col">
                                    <label class="form-label">Unit Collaborator</label>
                                    <input type="text" name="unit_collab" id="editUnitCollab" class="form-control">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" id="editStatus" class="form-select" onchange="checkStatus(this, 'editComplete')">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Done">Done</option>
                                    <option value="Eskalasi">Eskalasi</option>
                                    <option value="Progress">Progress</option>
                                    <option value="Need Discuss">Need Discuss</option>
                                    <option value="Open">Open</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">% Complete</label>
                                <input type="number" name="complete" id="editComplete" class="form-control" value ="0" min="0" max="100" oninput="checkComplete(this, 'editStatus')" onblur="setZeroIfEmpty(this)">
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
                    <form action="{{ route('tifta.store') }}" method="POST" onsubmit="return validateAddForm()">
                        @csrf
                        <div class="modal-header" style="background-color: #4A0E4E; color: #fff;">
                            <h5 class="modal-title" id="addModalLabel">Tambah Data Eskalasi To TIF_TA</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="mb-3">
                                <label class="form-label">Event</label>
                                <input type="text" name="event" class="form-control" placeholder="Masukkan nama event" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Unit/Telda</label>
                                <input type="text" name="unit" class="form-control" placeholder="Masukkan unit atau Telda">
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" name="start_date" class="form-control">
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
                                    <input type="text" name="uic" class="form-control" placeholder="Masukkan UIC">
                                </div>
                                <div class="col">
                                    <label class="form-label">Unit Collaborator</label>
                                    <input type="text" name="unit_collab" class="form-control" placeholder="Masukkan Unit Collaborator">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" id="addStatus" onchange="checkStatus(this, 'addComplete')">
                                    <option value="" selected disabled>-- Pilih Status --</option>
                                    <option value="Done">Done</option>
                                    <option value="Eskalasi">Eskalasi</option>
                                    <option value="Progress">Progress</option>
                                    <option value="Need Discuss">Need Discuss</option>
                                    <option value="Open">Open</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">% Complete</label>
                                <input type="number" name="complete" class="form-control" id="addComplete" value ="0" min="0" max="100" placeholder="Masukkan progress" oninput="checkComplete(this, 'addStatus')" onblur="setZeroIfEmpty(this)">
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
<script src="{{ asset('js/tablejs.js') }}"></script>
@endsection