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
                <div class="stat-value total-stat">12</div>
            </div>
            <div class="stat-item">
                <span class="stat-label">Close</span>
                <div class="stat-value close-stat">2 (6.7%)</div>
            </div>
            <div class="stat-item">
                <span class="stat-label">Actual Progress</span>
                <div class="stat-value progress-stat">40%</div>
            </div>
            <div class="stat-item">
                <a href="#" class="stat-value add-stat add-button" data-bs-toggle="modal" data-bs-target="#addModal">ADD +</a>
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
                    <td>{{ $item->notes }}</td>
                    <td>{{ $item->uic }}</td>
                    <td>{{ $item->unit_collab }}</td>
                    <td>{{ $item->complete }}%</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->respond }}</td>
                    <td>
                        <a href="{{ route('sntelda.edit', $item->id) }}" class="action-button action-edit">EDIT</a>

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
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <form action="{{ route('sntelda.store') }}" method="POST">
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
                                <input type="text" name="unit" class="form-control" placeholder="Masukkan unit atau Telda" required>
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" name="start_date" class="form-control" required>
                                </div>
                                <div class="col">
                                    <label class="form-label">End Date</label>
                                    <input type="date" name="end_date" class="form-control">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Notes to Follow Up</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Masukkan catatan atau tindak lanjut"></textarea>
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label">UIC</label>
                                    <input type="text" name="uic" class="form-control" placeholder="Masukkan UIC" required>
                                </div>
                                <div class="col">
                                    <label class="form-label">Unit Collaborator</label>
                                    <input type="text" name="unit_collab" class="form-control" placeholder="Masukkan Unit Collaborator">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">% Complete</label>
                                <input type="number" name="complete" class="form-control" min="0" max="100" placeholder="Masukkan progress" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                    <option selected disabled>-- Pilih Status --</option>
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
@endsection