@extends('layouts.app')

@section('title', 'War Room Activity')

@section('content')
<div class="warroom-container">
    <div class="warroom-header">
        <h1 class="warroom-title">WAR ROOM ACTIVITY</h1>
        <p class="warroom-subtitle">Strategic Operations Center</p>
    </div>

    <div class="summary-section">
        <div class="summary-grid">
            <div class="forum-info">
                <h3 class="forum-title">Forum WARROOM Bulan Juni</h3>
                <p class="forum-date">Start: 24 Juni 2025</p>
                <ul class="agenda-list">
                    <li class="agenda-item">1) 1 on 1 Hotda</li>
                    <li class="agenda-item">2) Review AOSODOMORO & EDK all Segmen</li>
                    <li class="agenda-item">3) 1 on 1 AM BS</li>
                    <li class="agenda-item">4) Review Rising Star, championeer, KM</li>
                    <li class="agenda-item">5) WAR Witel Solo</li>
                </ul>
            </div>
            <div class="stats-panel">
                <div class="stat-card">
                    <div class="stat-number">9</div>
                    <div class="stat-label-wr">Jumlah Agenda</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">33</div>
                    <div class="stat-label-wr">Action Plan</div>
                </div>
                <div class="stat-card escalation">
                    <div class="stat-number">3</div>
                    <div class="stat-label-wr">Masuk Eskalasi</div>
                </div>
            </div>
        </div>
    </div>

    <div class="filter-section">
        <div class="filter-group">
            <span class="filter-label">Filter by Month:</span>
            <select class="filter-select">
                <option value="06-2025">Juni 2025</option>
                <option value="05-2025">Mei 2025</option>
                <option value="04-2025">April 2025</option>
            </select>
        </div>
        <a href="#" class="stat-value add-stat add-button" data-bs-toggle="modal" data-bs-target="#addModal">ADD+</a>
    </div>

    <div class="collab-table-container">
        <table class="warroom-table">
            <thead>
                <tr>
                    <th class="col-no">NO</th>
                    <th class="col-tgl">TGL</th>
                    <th class="col-agenda">AGENDA</th>
                    <th class="col-peserta">PESERTA</th>
                    <th class="col-pembahasan">PEMBAHASAN</th>
                    <th class="col-action-plan">ACTION PLAN</th>
                    <th class="col-support-needed">SUPPORT NEEDED</th>
                    <th class="col-info-kompetitor">INFO KOMPETITOR</th>
                    <th class="col-jumlah-action">JML ACTION</th>
                    <th class="col-update-action">UPDATE ACTION</th>
                    <th class="col-status">STATUS</th>
                    <th class="col-action">ACTION</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($activities as $index => $activity)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($activity->tgl)->format('d-M-Y') }}</td>
                    <td>{{ $activity->agenda }}</td>
                    <td>{{ $activity->peserta }}</td>
                    <td>{!! nl2br(e($activity->pembahasan)) !!}</td>
                    <td>{!! nl2br(e($activity->action_plan)) !!}</td>
                    <td>{{ $activity->support_needed }}</td>
                    <td>{{ $activity->info_kompetitor }}</td>
                    <td>{{ $activity->jumlah_action_plan }}</td>
                    <td>{!! nl2br(e($activity->update_action_plan)) !!}</td>
                    <td>
                        @if($activity->status_action_plan == 'Selesai')
                        <span class="status-complete">Selesai</span>
                        @elseif($activity->status_action_plan == 'Progress')
                        <span class="status-progress">Progress</span>
                        @elseif($activity->status_action_plan == 'Eskalasi')
                        <span class="status-eskalasi">Eskalasi</span>
                        @else
                        <span>-</span>
                        @endif
                    </td>
                    <td>
                        <a href="#" class="action-button action-edit"
                            data-id="{{ $activity->id }}"
                            data-tgl="{{ $activity->tgl }}"
                            data-agenda="{{ $activity->agenda }}"
                            data-peserta="{{ $activity->peserta }}"
                            data-pembahasan="{{ $activity->pembahasan }}"
                            data-action_plan="{{ $activity->action_plan }}"
                            data-support_needed="{{ $activity->support_needed }}"
                            data-info_kompetitor="{{ $activity->info_kompetitor }}"
                            data-jumlah_action_plan="{{ $activity->jumlah_action_plan }}"
                            data-update_action_plan="{{ $activity->update_action_plan }}"
                            data-status_action_plan="{{ $activity->status_action_plan }}"
                            onclick="openEditModalWarroom(this)">
                            EDIT
                        </a>
                        <form action="{{ route('warroom.destroy', $activity->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-button action-delete" onclick="return confirm('Yakin ingin hapus data ini?')">DELETE</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="12" style="text-align: center;">Belum ada data War Room</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Pop Up -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <form action="{{ route('warroom.store') }}" method="POST">
                @csrf
                <div class="modal-header" style="background-color: #4A0E4E; color: #fff;">
                    <h5 class="modal-title">Tambah Data War Room</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tgl" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Agenda</label>
                        <input type="text" name="agenda" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Peserta</label>
                        <input type="text" name="peserta" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pembahasan</label>
                        <textarea name="pembahasan" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Action Plan</label>
                        <textarea name="action_plan" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Support Needed</label>
                        <input type="text" name="support_needed" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Info Kompetitor</label>
                        <input type="text" name="info_kompetitor" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Action Plan</label>
                        <input type="number" name="jumlah_action_plan" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Update Action Plan</label>
                        <textarea name="update_action_plan" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status_action_plan" class="form-select">
                            <option value="" selected disabled>-- Pilih Status --</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Progress">Progress</option>
                            <option value="Eskalasi">Eskalasi</option>
                        </select>
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

<!-- Edit Pop Up -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <form id="editForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header" style="background-color: #4A0E4E; color: #fff;">
          <h5 class="modal-title">Edit Data War Room</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">

          <!-- Semua input field mirip Add Modal, tapi dengan id masing-masing -->
          <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="tgl" id="editTgl" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Agenda</label>
            <input type="text" name="agenda" id="editAgenda" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Peserta</label>
            <input type="text" name="peserta" id="editPeserta" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Pembahasan</label>
            <textarea name="pembahasan" id="editPembahasan" class="form-control" rows="3"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Action Plan</label>
            <textarea name="action_plan" id="editActionPlan" class="form-control" rows="3"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Support Needed</label>
            <input type="text" name="support_needed" id="editSupportNeeded" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Info Kompetitor</label>
            <input type="text" name="info_kompetitor" id="editInfoKompetitor" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Jumlah Action Plan</label>
            <input type="number" name="jumlah_action_plan" id="editJumlahActionPlan" class="form-control" min="0">
          </div>
          <div class="mb-3">
            <label class="form-label">Update Action Plan</label>
            <textarea name="update_action_plan" id="editUpdateActionPlan" class="form-control" rows="3"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status_action_plan" id="editStatusActionPlan" class="form-select">
              <option value="">-- Pilih Status --</option>
              <option value="Selesai">Selesai</option>
              <option value="Progress">Progress</option>
              <option value="Eskalasi">Eskalasi</option>
            </select>
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
<script src="{{ asset('js/tablejs.js') }}"></script>
@endsection