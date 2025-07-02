@extends('layouts.app')

@section('title', 'Create Data Support Needed TELDA')

@section('content')
<div class="collab-form-container">
    <h2 class="mb-4 page-title">Tambah Data Support Needed TELDA</h2>

    <form action="{{ route('telda.store') }}" method="POST">
        @csrf

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
            <textarea name="notes" class="form-control" rows="3" placeholder="Masukkan catatan atau tindak lanjut" required></textarea>
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

        <div class="d-flex justify-content-end">
            <a href="{{ route('telda.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection
