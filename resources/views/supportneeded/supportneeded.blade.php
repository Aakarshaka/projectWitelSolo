@extends('layouts.layout')

@section('title', 'SUPPORT NEEDED')

@section('content')
<div class="container">
    <div class="header">
        <h1>SUPPORT NEEDED</h1>
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-label">Total</div>
                <div class="stat-value">1</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Close</div>
                <div class="stat-value">0 (0%)</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Actual Progress</div>
                <div class="stat-value">0%</div>
            </div>
            <button class="add-btn">ADD+</button>
        </div>
    </div>

    <div class="controls">
        <div class="filters">
            <div class="filter-group">
                <label class="filter-label">Type of Agenda</label>
                <select class="filter-select">
                    <option>All Agenda</option>
                    <option>1 ON 1 UIC</option>
                    <option>1 ON 1 WITEL</option>
                    <option>EVP DIRECTION</option>
                    <option>WBR IT FEB</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Witel or Unit</label>
                <select class="filter-select">
                    <option>All Witel or Unit</option>
                    <option>RLEGS</option>
                    <option>Witel Bali</option>
                    <option>Witel Yogyakarta</option>
                    <option>Witel Suramadu</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">UIC</label>
                <select class="filter-select">
                    <option>All UIC</option>
                    <option>BPPLP</option>
                    <option>RSO1</option>
                    <option>RSMES</option>
                    <option>RLEGS</option>
                </select>
            </div>
            <button class="filter-btn">FILTER</button>
        </div>
        <div class="search-container">
            <input type="text" class="search-box" placeholder="Search...">
        </div>
    </div>

    <div class="scroll-hint">
        ← Geser ke kiri/kanan untuk melihat semua kolom →
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th class="col-agenda">Agenda</th>
                    <th class="col-unit">Unit/Telda</th>
                    <th class="col-start">Start Date</th>
                    <th class="col-end">End Date</th>
                    <th class="col-off"># Off Day</th>
                    <th class="col-notes">Notes to Follow Up</th>
                    <th class="col-uic">UIC</th>
                    <th class="col-progress">Progress</th>
                    <th class="col-complete">% Complete</th>
                    <th class="col-status">Status</th>
                    <th class="col-respons">Response UIC</th>
                    <th class="col-action">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $index => $item)
                <tr>
                    <td class="col-no">{{ $items->firstItem() + $index }}</td>
                    <td class="col-agenda">{{ $item->agenda }}</td>
                    <td class="col-unit">{{ $item->unit_or_telda }}</td>
                    <td class="col-start">{{ \Carbon\Carbon::parse($item->start_date)->format('d M Y') }}</td>
                    <td class="col-end">{{ \Carbon\Carbon::parse($item->end_date)->format('d M Y') }}</td>
                    <td class="col-off">{{ $item->off_day }}</td>
                    <td class="col-notes">{{ $item->notes_to_follow_up }}</td>
                    <td class="col-uic">{{ $item->uic }}</td>
                    <td class="col-progress">{{ $item->progress }}</td>
                    <td class="col-complete">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $item->complete }}%"></div>
                            <div class="progress-text">{{ $item->complete }}%</div>
                        </div>
                    </td>
                    <td class="col-status">
                        <span class="status-badge {{ $item->status === 'Done' ? 'status-done' : 'status-in-progress' }}">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td class="col-respons">{{ $item->response_uic }}</td>
                    <td class="col-action">
                        <a href="{{ route('supportneeded.edit', $item->id) }}" class="action-btn edit-btn">Edit</a>
                        <form action="{{ route('supportneeded.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="13" style="text-align:center;">No data available.</td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
    <div class="sn-footer">Powered by <strong>GIAT CORE</strong></div>
</div>

<!-- Add Support Needed Pop-up Modal -->
<div class="snpopup-overlay" id="addModal">
    <div class="snpopup-container">
        <button class="snpopup-close" onclick="closeAddModal()">×</button>
        <h2>Add Support Needed</h2>
        <form action="{{ route('supportneeded.store') }}" method="POST">
            @csrf
            <div class="snpopup-form-group">
                <label>Agenda</label>
                <select name="agenda" required>
                    <option value="1 ON 1 UIC">1 ON 1 UIC</option>
                    <option value="1 ON 1 WITEL">1 ON 1 WITEL</option>
                    <option value="EVP DIRECTION">EVP DIRECTION</option>
                    <option value="WBR IT FEB">WBR IT FEB</option>
                    <option value="Meeting Rutin">Meeting Rutin</option>
                </select>
            </div>

            <div class="snpopup-form-group">
                <label>Unit/Telda</label>
                <select name="unit_or_telda">
                    <option value="TELDA BLORA">TELDA BLORA</option>
                    <option value="BOYOLALI">BOYOLALI</option>
                    <option value="JEPARA">JEPARA</option>
                    <option value="KLATEN">KLATEN</option>
                    <option value="KUDUS">KUDUS</option>
                    <option value="MEA SOLO">MEA SOLO</option>
                    <option value="PATI">PATI</option>
                    <option value="PURWODADI">PURWODADI</option>
                    <option value="REMBANG">REMBANG</option>
                    <option value="SRAGEN">SRAGEN</option>
                    <option value="WONOGIRI">WONOGIRI</option>
                    <option value="BS">BS</option>
                    <option value="GS">GS</option>
                    <option value="PRQ">PRQ</option>
                </select>
            </div>

            <div class="snpopup-form-group">
                <label>Start Date</label>
                <input type="date" name="start_date">
            </div>

            <div class="snpopup-form-group">
                <label>End Date</label>
                <input type="date" name="end_date">
            </div>

            <div class="snpopup-form-group">
                <label>Notes to Follow Up</label>
                <textarea name="notes_to_follow_up" rows="3"></textarea>
            </div>

            <div class="snpopup-form-group">
                <label>UIC</label>
                <select name="uic">
                    <option value="BS">BS</option>
                    <option value="GS">GS</option>
                    <option value="RLEGS">RLEGS</option>
                    <option value="RSO">RSO</option>
                    <option value="TIF">TIF</option>
                    <option value="TSEL">TSEL</option>
                    <option value="GSD">GSD</option>
                    <option value="SSGS">SSGS</option>
                    <option value="PRQ">PRQ</option>
                    <option value="RSMES">RSMES</option>
                    <option value="BPPLP">BPPLP</option>
                    <option value="SSS">SSS</option>
                </select>
            </div>

            <div class="snpopup-form-group">
                <label>Progress</label>
                <select name="progress">
                    <option value="Open">Open</option>
                    <option value="Need Discuss">Need Discuss</option>
                    <option value="Progress">Progress</option>
                    <option value="Done">Done</option>
                </select>
            </div>

            <div class="snpopup-form-group">
                <label>Response UIC</label>
                <textarea name="response_uic" rows="3"></textarea>
            </div>

            <div class="snpopup-form-group">
                <button type="submit" class="snpopup-save-btn">Save</button>
            </div>
        </form>
    </div>
</div>


<script>
    // Simple JavaScript for interactivity
    document.addEventListener('DOMContentLoaded', function() {
        // Filter functionality
        const filterBtn = document.querySelector('.filter-btn');
        const addBtn = document.querySelector('.add-btn');
        const editBtns = document.querySelectorAll('.edit-btn');
        const deleteBtns = document.querySelectorAll('.delete-btn');

        filterBtn.addEventListener('click', function() {
            alert('Filter functionality would be implemented here');
        });

        addBtn.addEventListener('click', function() {
            alert('Add new support item functionality would be implemented here');
        });

        editBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                alert('Edit functionality would be implemented here');
            });
        });

        deleteBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                if (confirm('Are you sure you want to delete this item?')) {
                    alert('Delete functionality would be implemented here');
                }
            });
        });

        // Search functionality
        const searchBox = document.querySelector('.search-box');
        searchBox.addEventListener('input', function() {
            // Search functionality would be implemented here
            console.log('Searching for:', this.value);
        });
    });
</script>
@endsection