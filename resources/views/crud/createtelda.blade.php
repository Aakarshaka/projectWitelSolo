@extends('layouts.app')

@section('title', 'Create Data Support Needed TELDA')

@section('content')
<div class="telda-container p-4">
    <h2 class="mb-4">Tambah Data Support Needed TELDA</h2>

    <form>
        <div class="mb-3">
            <label class="form-label">Event</label>
            <input type="text" class="form-control" placeholder="Masukkan nama event">
        </div>

        <div class="mb-3">
            <label class="form-label">Unit/Telda</label>
            <input type="text" class="form-control" placeholder="Masukkan unit atau Telda">
        </div>

        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Start Date</label>
                <input type="date" class="form-control">
            </div>
            <div class="col">
                <label class="form-label">End Date</label>
                <input type="date" class="form-control">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Notes to Follow Up</label>
            <textarea class="form-control" rows="3" placeholder="Masukkan catatan atau tindak lanjut"></textarea>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label class="form-label">UIC</label>
                <input type="text" class="form-control" placeholder="Masukkan UIC">
            </div>
            <div class="col">
                <label class="form-label">Unit Collaborator</label>
                <input type="text" class="form-control" placeholder="Masukkan Unit Collaborator">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">% Complete</label>
            <input type="number" class="form-control" min="0" max="100" placeholder="Masukkan progress">
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select">
                <option selected disabled>-- Pilih Status --</option>
                <option value="Done">Done</option>
                <option value="Eskalasi">Eskalasi</option>
                <option value="Progress">Progress</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Respond UIC</label>
            <textarea class="form-control" rows="3" placeholder="Masukkan respon UIC"></textarea>
        </div>

        <button type="button" class="btn btn-primary">Simpan</button>
        <a href="#" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
