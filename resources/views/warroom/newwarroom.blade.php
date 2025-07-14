@extends('layouts.layout')

@section('title', 'War Room')

@section('content')
<style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap");

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: "Poppins", sans-serif;
        background-color: #f8f6f9;
    }

    .main-content-wr {
        margin-left: 60px;
        min-height: 100vh;
        transition: margin-left 0.3s ease;
    }

    .container-wr {
        padding: 20px;
        width: 100%;
    }

    .header-wr {
        background: linear-gradient(135deg, #8b1538 0%, #4a0e4e 100%);
        color: white;
        padding: 15px 30px;
        margin: -20px -20px 20px -20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 20px rgba(139, 21, 56, 0.3);
        flex-wrap: wrap;
    }

    .header h1 {
        font-size: 28px;
        font-weight: 700;
        letter-spacing: -0.5px;
        min-width: 200px;
    }

    .header p {
        font-size: 16px;
        opacity: 0.9;
        margin-top: 5px;
    }

    .controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .btn {
        padding: 20px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .btn-primary {
        background: #4a0e4e;
        color: white;
    }

    .btn-primary:hover {
        background: #3a0b3e;
    }

    .btn-success {
        background: #22c55e;
        color: white;
    }

    .btn-success:hover {
        background: #16a34a;
    }

    .btn-warning {
        background: #fbbf24;
        color: white;
    }

    .btn-warning:hover {
        background: #f59e0b;
    }

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
    }

    .btn-secondary {
        background: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background: #4b5563;
    }

    .btn-sm {
        padding: 8px 12px;
        font-size: 12px;
    }

    .table-container-wr {
        position: relative;
        width: 100%;
        background: white;
        border-radius: 0px;
        border: 1px solid #e6e1e8;
        overflow: hidden;
    }

    .table-wr th.col-no-wr {
        width: 30px;
    }

    .table-wr th.col-tgl-wr {
        width: 90px;
    }

    .table-wr th.col-agenda-wr {
        width: 30px;
    }

    .table-wr th.col-uic-wr {
        width: 30px;
    }

    .table-wr th.col-peserta-wr {
        width: 30px;
    }

    .table-wr th.col-pembahasan-wr {
        width: 300px;
    }

    .table-wr th.col-ac-wr {
        width: 300px;
    }

    .table-wr th.col-sn-wr {
        width: 300px;
    }

    .table-wr th.col-kompetitor-wr {
        width: 30px;
    }

    .table-wr th.col-jac-wr {
        width: 30px;
    }

    .table-wr th.col-uap-wr {
        width: 300px;
    }

    .table-wr th.col-sap-wr {
        width: 30px;
    }

    .table-wr th.col-action-wr {
        width: 150px;
    }

    /* PERBAIKAN: Alignment untuk kolom tertentu */
    .table-wr td:nth-child(1),
    .table-wr td:nth-child(2),
    .table-wr td:nth-child(3),
    .table-wr td:nth-child(4),
    .table-wr td:nth-child(9),
    .table-wr td:nth-child(10),
    .table-wr td:nth-child(12),
    .table-wr td:nth-child(13) {
        text-align: center;
    }

    .table-wr td:nth-child(5),
    .table-wr td:nth-child(6),
    .table-wr td:nth-child(7),
    .table-wr td:nth-child(8),
    .table-wr td:nth-child(11) {
        text-align: left;
    }

    .table-wrapper-wr {
        width: 100%;
        overflow-x: auto;
        overflow-y: auto;
        max-height: calc(100vh - 280px);
        position: relative;
    }

    .table-wrapper-wr::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .table-wrapper-wr::-webkit-scrollbar-track {
        background: #f8f6f9;
    }

    .table-wrapper-wr::-webkit-scrollbar-thumb {
        background: #4a0e4e;
        border-radius: 4px;
    }

    .table-wrapper-wr::-webkit-scrollbar-thumb:hover {
        background: #3a0b3e;
    }

    .table-wr {
        border-collapse: collapse;
        font-size: 13px;
        table-layout: fixed;
        width: max-content;
        min-width: 100%;
    }

    .table-wr th {
        position: sticky;
        top: 0;
        background: #a8a8a9;
        color: #2a1b2d;
        font-weight: 600;
        padding: 12px 8px;
        text-align: center;
        border-bottom: 2px solid #e6e1e8;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        white-space: nowrap;
        z-index: 10;
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
    }

    .table-wr td {
        padding: 12px 8px;
        border-bottom: 1px solid #f2e8f3;
        vertical-align: middle;
        word-wrap: break-word;
        overflow-wrap: break-word;
        text-align: center;
    }

    .table-wr tr:hover {
        background: #faf8fb;
    }

    .table-wr tr:last-child td {
        border-bottom: none;
    }

    .badge {
        display: inline-block;
        padding: 4px 8px;
        font-size: 11px;
        font-weight: 600;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge.bg-warning {
        background: #fbbf24;
        color: #92400e;
    }

    .badge.bg-info {
        background: #3b82f6;
        color: white;
    }

    .badge.bg-success {
        background: #22c55e;
        color: white;
    }

    .badge.bg-secondary {
        background: #6b7280;
        color: white;
    }

    .alert {
        padding: 12px 16px;
        margin-bottom: 20px;
        border-radius: 8px;
        border: 1px solid transparent;
        position: relative;
    }

    .alert-success {
        background: #d1fae5;
        border-color: #a7f3d0;
        color: #065f46;
    }

    .alert-dismissible {
        padding-right: 50px;
    }

    .alert .close-btn {
        position: absolute;
        top: 50%;
        right: 16px;
        transform: translateY(-50%);
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: inherit;
        opacity: 0.7;
    }

    .alert .close-btn:hover {
        opacity: 1;
    }

    /* Scroll hint */
    .scroll-hint-wr {
        display: none;
        text-align: center;
        font-size: 12px;
        color: #6d5671;
        margin-bottom: 10px;
        padding: 8px;
        background: #f8f6f9;
        border-radius: 6px;
        border: 1px solid #e6e1e8;
    }

    .sn-footer-wr {
        text-align: center;
        font-size: 15px;
        color: #6d5671;
        margin-top: 10px;
        padding: 10px 0;
    }

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
        overflow-y: auto;
    }

    .modal-header {
        padding: 20px 30px;
        border-bottom: 1px solid #e6e1e8;
        display: flex;
        justify-content: space-between;
        align-items: center;
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
    }

    .modal-footer {
        padding: 20px 30px;
        border-top: 1px solid #e6e1e8;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .form-group {
        margin-bottom: 20px;
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

    /* Mobile responsive */
    @media (max-width: 800px) {
        .main-content-wr {
            margin-left: 0;
            margin-bottom: 60px;
        }

        .container-wr {
            padding: 10px;
        }

        .header-wr {
            background: linear-gradient(135deg, #8b1538 0%, #4a0e4e 100%);
            color: white;
            padding: 15px 30px;
            margin: -20px -20px 20px -20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(139, 21, 56, 0.3);
            flex-wrap: wrap;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
            min-width: 200px;
        }

        .controls {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
        }

        .form-row {
            flex-direction: column;
            gap: 10px;
        }

        .modal-dialog {
            width: 95%;
            margin: 20px auto;
        }

        .modal-header,
        .modal-body,
        .modal-footer {
            padding: 20px;
        }

        .table-wr {
            font-size: 11px;
        }

        .table-wr th,
        .table-wr td {
            padding: 8px 6px;
            font-size: 11px;
        }
    }
</style>

<div class="main-content-wr">
    <div class="container-wr">
        <div class="header-wr">
            <div>
                <h1>WARROOM ACTIVITY</h1>
            </div>
            <div class="controls">
                <button type="button" class="btn btn-success" onclick="openModal('addModal')">
                    <i class="fas fa-plus"></i>ADD+
                </button>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            {{ session('success') }}
            <button type="button" class="close-btn" onclick="this.parentElement.style.display='none'">&times;</button>
        </div>
        @endif

        <div class="scroll-hint-wr">
            ← Geser ke kiri/kanan untuk melihat semua Colom →
        </div>

        <div class="table-container-wr">
            <div class="table-wrapper-wr">
                <table class="table-wr">
                    <thead>
                        <tr>
                            <th class="col-no-wr">No</th>
                            <th class="col-tgl-wr">Tgl</th>
                            <th class="col-agenda-wr">Agenda</th>
                            <th class="col-uic-wr">UIC</th>
                            <th class="col-peserta-wr">Peserta</th>
                            <th class="col-pembahasan-wr">Pembahasan</th>
                            <th class="col-ac-wr">Action Plan</th>
                            <th class="col-sn-wr">Support Needed</th>
                            <th class="col-kompetitor-wr">Info Kompetitor</th>
                            <th class="col-jap-wr">Jumlah Action Plan</th>
                            <th class="col-uap-wr">Update Action Plan</th>
                            <th class="col-sap-wr">Status Action Plan</th>
                            <th class="col-action-wr">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($warroomData as $index => $item)
                        <tr>
                            <td class="col-no-wr">{{ $index + 1 }}</td>
                            <td class="col-tgl-wr">{{ \Carbon\Carbon::parse($item->tgl)->format('d-m-Y') }}</td>
                            <td class="col-agenda-wr">{{ $item->agenda }}</td>
                            <td class="col-uic-wr">{{ $item->uic }}</td>
                            <td class="col-peserta-wr">{{ $item->peserta }}</td>
                            <td class="col-pembahasan-wr">{{ $item->pembahasan }}</td>
                            <td class="col-ac-wr">{{ $item->action_plan }}</td>
                            <td class="col-sn-wr">{{ $item->support_needed }}</td>
                            <td class="col-kompetitor-wr">{{ $item->info_kompetitor }}</td>
                            <td class="col-jac-wr">{{ $item->jumlah_action_plan }}</td>
                            <td class="col-uac-wr">{{ $item->update_action_plan }}</td>
                            <td class="col-sap-wr">
                                @if($item->status_action_plan == 'Open')
                                <span class="badge bg-warning">Open</span>
                                @elseif($item->status_action_plan == 'Progress')
                                <span class="badge bg-info">Progress</span>
                                @elseif($item->status_action_plan == 'Closed')
                                <span class="badge bg-success">Closed</span>
                                @else
                                <span class="badge bg-secondary">{{ $item->status_action_plan }}</span>
                                @endif
                            </td>
                            <td class="col-action-wr">
                                <div class="btn-group-vertical">
                                    <button type="button" class="btn btn-sm btn-warning"
                                        onclick="openModal('editModal{{ $item->id }}')">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>

                                    <form action="{{ route('newwarroom.destroy', $item->id) }}" method="POST"
                                        style="display:inline-block;"
                                        onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="13" class="text-muted">
                                <i class="fas fa-info-circle"></i> Tidak ada data warroom ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit untuk setiap item -->
@foreach($warroomData as $item)
<div class="modal" id="editModal{{ $item->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('newwarroom.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning">
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
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editModal{{ $item->id }}')">
                        <i class="fas fa-times"></i> Batal
                    </button>
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
                <div class="modal-header bg-success">
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
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addModal')">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-success">
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
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            closeModal(e.target.id);
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const activeModal = document.querySelector('.modal.show');
            if (activeModal) {
                closeModal(activeModal.id);
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Auto-resize textarea
        const textareas = document.querySelectorAll('textarea');
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
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.display = 'none';
            });
        }, 5000);
    });
</script>
@endsection