@extends('layouts.layout')

@section('title', 'War Room')

@section('content')
        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <style>
            .modal-backdrop {
                z-index: 1040;
            }

            .modal {
                z-index: 1050;
            }

            .table-responsive {
                overflow-x: auto;
            }

            .btn-group-vertical .btn {
                margin-bottom: 2px;
            }

            .navbar-brand {
                font-weight: bold;
            }

            .table th {
                font-size: 0.875rem;
                white-space: nowrap;
            }

            .table td {
                font-size: 0.875rem;
            }

            .form-label {
                font-weight: 600;
            }

            .is-invalid {
                border-color: #dc3545;
            }

            .alert {
                border-radius: 0.375rem;
            }
        </style>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <i class="fas fa-chess-rook"></i> War Room System
                </a>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="container mt-4">
            <h4><strong>WAR ROOM ACTIVITY WITEL JATENG TIMUR BULAN JUNI 2025</strong></h4>
            <p><em>Jumlah Action Plan</em></p>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Tombol Tambah & Sync -->
            <div class="d-flex justify-content-between mb-3">
                <form action="{{ route('warroom.sync') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sync-alt"></i> Sync dari Supportneeded
                    </button>
                </form>

                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fas fa-plus"></i> Tambah Data
                </button>
            </div>

            <!-- Tabel -->
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle">
                    <thead class="table-primary text-white">
                        <tr>
                            <th>No</th>
                            <th>Tgl</th>
                            <th>Agenda</th>
                            <th>UIC</th>
                            <th>Peserta</th>
                            <th>Pembahasan</th>
                            <th>Action Plan</th>
                            <th>Support Needed</th>
                            <th>Info Kompetitor</th>
                            <th>Jumlah Action Plan</th>
                            <th class="bg-success text-white">Update Action Plan</th>
                            <th class="bg-success text-white">Status Action Plan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($warroomData as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tgl)->format('d-m-Y') }}</td>
                                <td>{{ $item->agenda }}</td>
                                <td>{{ $item->uic }}</td>
                                <td>{{ $item->peserta }}</td>
                                <td>{{ $item->pembahasan }}</td>
                                <td>{{ $item->action_plan }}</td>
                                <td>{{ $item->support_needed }}</td>
                                <td>{{ $item->info_kompetitor }}</td>
                                <td>{{ $item->jumlah_action_plan }}</td>
                                <td>{{ $item->update_action_plan }}</td>
                                <td>
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
                                <td>
                                    <div class="btn-group-vertical" role="group">
                                        <!-- Tombol Edit Modal -->
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $item->id }}">
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

        <!-- Modal Edit untuk setiap item -->
        @foreach($warroomData as $item)
            <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $item->id }}"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form action="{{ route('newwarroom.update', $item->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header bg-warning text-dark">
                                <h5 class="modal-title" id="editModalLabel{{ $item->id }}">
                                    <i class="fas fa-edit"></i> Edit Data Warroom
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="tgl_{{ $item->id }}" class="form-label">Tanggal <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="tgl_{{ $item->id }}" name="tgl"
                                                value="{{ $item->tgl }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="agenda_{{ $item->id }}" class="form-label">Agenda <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="agenda_{{ $item->id }}" name="agenda"
                                                value="{{ $item->agenda }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="uic_{{ $item->id }}" class="form-label">UIC <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="uic_{{ $item->id }}" name="uic"
                                                value="{{ $item->uic }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="peserta_{{ $item->id }}" class="form-label">Peserta <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="peserta_{{ $item->id }}" name="peserta"
                                                value="{{ $item->peserta }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="pembahasan_{{ $item->id }}" class="form-label">Pembahasan <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" id="pembahasan_{{ $item->id }}" name="pembahasan" rows="3"
                                        required>{{ $item->pembahasan }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="action_plan_{{ $item->id }}" class="form-label">Action Plan <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" id="action_plan_{{ $item->id }}" name="action_plan" rows="3"
                                        required>{{ $item->action_plan }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="support_needed_{{ $item->id }}" class="form-label">Support Needed</label>
                                    <textarea class="form-control" id="support_needed_{{ $item->id }}" name="support_needed"
                                        rows="3">{{ $item->support_needed }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="info_kompetitor_{{ $item->id }}" class="form-label">Info Kompetitor</label>
                                    <textarea class="form-control" id="info_kompetitor_{{ $item->id }}" name="info_kompetitor"
                                        rows="3">{{ $item->info_kompetitor }}</textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="jumlah_action_plan_{{ $item->id }}" class="form-label">Jumlah Action
                                                Plan</label>
                                            <input type="number" class="form-control" id="jumlah_action_plan_{{ $item->id }}"
                                                name="jumlah_action_plan" value="{{ $item->jumlah_action_plan }}" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status_action_plan_{{ $item->id }}" class="form-label">Status Action
                                                Plan</label>
                                            <select class="form-select" id="status_action_plan_{{ $item->id }}"
                                                name="status_action_plan">
                                                <option value="Open" {{ $item->status_action_plan == 'Open' ? 'selected' : '' }}>
                                                    Open</option>
                                                <option value="Progress" {{ $item->status_action_plan == 'Progress' ? 'selected' : '' }}>Progress</option>
                                                <option value="Closed" {{ $item->status_action_plan == 'Closed' ? 'selected' : '' }}>Closed</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="update_action_plan_{{ $item->id }}" class="form-label">Update Action
                                        Plan</label>
                                    <textarea class="form-control" id="update_action_plan_{{ $item->id }}"
                                        name="update_action_plan" rows="3">{{ $item->update_action_plan }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
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
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('newwarroom.store') }}" method="POST">
                        @csrf
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title" id="addModalLabel">
                                <i class="fas fa-plus"></i> Tambah Data Warroom
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tgl" class="form-label">Tanggal <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="tgl" name="tgl" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="agenda" class="form-label">Agenda <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="agenda" name="agenda" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="uic" class="form-label">UIC <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="uic" name="uic" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="peserta" class="form-label">Peserta <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="peserta" name="peserta" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="pembahasan" class="form-label">Pembahasan <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" id="pembahasan" name="pembahasan" rows="3"
                                    required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="action_plan" class="form-label">Action Plan <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" id="action_plan" name="action_plan" rows="3"
                                    required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="support_needed" class="form-label">Support Needed</label>
                                <textarea class="form-control" id="support_needed" name="support_needed"
                                    rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="info_kompetitor" class="form-label">Info Kompetitor</label>
                                <textarea class="form-control" id="info_kompetitor" name="info_kompetitor"
                                    rows="3"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="jumlah_action_plan" class="form-label">Jumlah Action Plan</label>
                                        <input type="number" class="form-control" id="jumlah_action_plan"
                                            name="jumlah_action_plan" min="0" value="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status_action_plan" class="form-label">Status Action Plan</label>
                                        <select class="form-select" id="status_action_plan" name="status_action_plan">
                                            <option value="Open" selected>Open</option>
                                            <option value="Progress">Progress</option>
                                            <option value="Closed">Closed</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="update_action_plan" class="form-label">Update Action Plan</label>
                                <textarea class="form-control" id="update_action_plan" name="update_action_plan"
                                    rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bootstrap 5 JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Inisialisasi Bootstrap Modal
                const modals = document.querySelectorAll('.modal');
                modals.forEach(modal => {
                    new bootstrap.Modal(modal);
                });

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

                // Reset form when modal is closed
                document.querySelectorAll('.modal').forEach(modal => {
                    modal.addEventListener('hidden.bs.modal', function () {
                        const form = this.querySelector('form');
                        if (form && this.id === 'addModal') {
                            form.reset();
                            form.querySelectorAll('.is-invalid').forEach(field => {
                                field.classList.remove('is-invalid');
                            });
                        }
                    });
                });

                // Auto dismiss alerts
                setTimeout(function () {
                    const alerts = document.querySelectorAll('.alert');
                    alerts.forEach(alert => {
                        if (alert.classList.contains('show')) {
                            bootstrap.Alert.getInstance(alert)?.close();
                        }
                    });
                }, 5000);
            });
        </script>
@endsection