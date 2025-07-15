@extends('layouts.layout')

@section('title', 'War Room')

@section('content')
<div class="main-content-wr">
    <div class="container-wr">
        <div class="header-wr">
            <h1>WARROOM ACTIVITY</h1>
            <div class="stats-container-wr">
                <div class="stat-card-wr">
                    <div class="stat-label-wr">Jumlah Action Plan</div>
                    <div class="stat-value-wr">8</div>
                </div>
                <button class="add-btn-wr" type="button" onclick="openModal('addModal')">ADD+</button>
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
                                <span>Open</span>
                                @elseif($item->status_action_plan == 'Progress')
                                <span>Progress</span>
                                @elseif($item->status_action_plan == 'Closed')
                                <span>Closed</span>
                                @else
                                <span class="badge bg-secondary">{{ $item->status_action_plan }}</span>
                                @endif
                            </td>
                            <td class="col-action-wr">
                                <div class="btn-group-horizontal-wr">
                                    <button type="button" class="btn-wr btn-sm btn-warning"
                                        onclick="openModal('editModal{{ $item->id }}')">
                                        Edit
                                    </button>

                                    <form action="{{ route('newwarroom.destroy', $item->id) }}" method="POST"
                                        style="display:inline-block;"
                                        onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-wr btn-sm btn-danger">
                                            Delete
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
        <div class="sn-footer">Powered by <strong>GIAT CORE</strong></div>
    </div>
</div>

@include('warroom.wrmodal')
@push('scripts')
<script src="{{ asset('js/tablescript.js') }}"></script>
@endpush
@endsection