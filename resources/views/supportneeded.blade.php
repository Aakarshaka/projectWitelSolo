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
                <tr>
                    <td class="col-no">1</td>
                    <td class="col-agenda">1 0n 1 WR Witel</td>
                    <td class="col-unit">Witel Yogyakarta</td>
                    <td class="col-start">20 Jan 2025</td>
                    <td class="col-end">28 Feb 2025</td>
                    <td class="col-off">3</td>
                    <td class="col-notes">Telah dilakukan pengembangan fitur enhancement dalam dashboard MetaBright yang terintegrasi dengan sistem monitoring dan alert</td>
                    <td class="col-uic">BPPLP</td>
                    <td class="col-progress">On Progress</td>
                    <td class="col-complete">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 25%"></div>
                            <div class="progress-text">25%</div>
                        </div>
                    </td>
                    <td class="col-status"><span class="status-badge status-in-progress">Eskalasi</span></td>
                    <td class="col-respons">Development team sedang melakukan enhancement fitur dashboard</td>
                    <td class="col-action">
                        <button class="action-btn edit-btn">Edit</button>
                        <button class="action-btn delete-btn">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td class="col-no">1</td>
                    <td class="col-agenda">1 0n 1 WR Witel</td>
                    <td class="col-unit">Witel Yogyakarta</td>
                    <td class="col-start">20 Jan 2025</td>
                    <td class="col-end">28 Feb 2025</td>
                    <td class="col-off">3</td>
                    <td class="col-notes">Telah dilakukan pengembangan fitur enhancement dalam dashboard MetaBright yang terintegrasi dengan sistem monitoring dan alert</td>
                    <td class="col-uic">BPPLP</td>
                    <td class="col-progress">On Progress</td>
                    <td class="col-complete">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 25%"></div>
                            <div class="progress-text">25%</div>
                        </div>
                    </td>
                    <td class="col-status"><span class="status-badge status-in-progress">Eskalasi</span></td>
                    <td class="col-respons">Development team sedang melakukan enhancement fitur dashboard</td>
                    <td class="col-action">
                        <button class="action-btn edit-btn">Edit</button>
                        <button class="action-btn delete-btn">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td class="col-no">1</td>
                    <td class="col-agenda">1 0n 1 WR Witel</td>
                    <td class="col-unit">Witel Yogyakarta</td>
                    <td class="col-start">20 Jan 2025</td>
                    <td class="col-end">28 Feb 2025</td>
                    <td class="col-off">3</td>
                    <td class="col-notes">Telah dilakukan pengembangan fitur enhancement dalam dashboard MetaBright yang terintegrasi dengan sistem monitoring dan alert</td>
                    <td class="col-uic">BPPLP</td>
                    <td class="col-progress">On Progress</td>
                    <td class="col-complete">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 25%"></div>
                            <div class="progress-text">25%</div>
                        </div>
                    </td>
                    <td class="col-status"><span class="status-badge status-in-progress">Eskalasi</span></td>
                    <td class="col-respons">Development team sedang melakukan enhancement fitur dashboard</td>
                    <td class="col-action">
                        <button class="action-btn edit-btn">Edit</button>
                        <button class="action-btn delete-btn">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td class="col-no">1</td>
                    <td class="col-agenda">1 0n 1 WR Witel</td>
                    <td class="col-unit">Witel Yogyakarta</td>
                    <td class="col-start">20 Jan 2025</td>
                    <td class="col-end">28 Feb 2025</td>
                    <td class="col-off">3</td>
                    <td class="col-notes">Telah dilakukan pengembangan fitur enhancement dalam dashboard MetaBright yang terintegrasi dengan sistem monitoring dan alert</td>
                    <td class="col-uic">BPPLP</td>
                    <td class="col-progress">On Progress</td>
                    <td class="col-complete">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 25%"></div>
                            <div class="progress-text">25%</div>
                        </div>
                    </td>
                    <td class="col-status"><span class="status-badge status-in-progress">Eskalasi</span></td>
                    <td class="col-respons">Development team sedang melakukan enhancement fitur dashboard</td>
                    <td class="col-action">
                        <button class="action-btn edit-btn">Edit</button>
                        <button class="action-btn delete-btn">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td class="col-no">1</td>
                    <td class="col-agenda">1 0n 1 WR Witel</td>
                    <td class="col-unit">Witel Yogyakarta</td>
                    <td class="col-start">20 Jan 2025</td>
                    <td class="col-end">28 Feb 2025</td>
                    <td class="col-off">3</td>
                    <td class="col-notes">Telah dilakukan pengembangan fitur enhancement dalam dashboard MetaBright yang terintegrasi dengan sistem monitoring dan alert</td>
                    <td class="col-uic">BPPLP</td>
                    <td class="col-progress">On Progress</td>
                    <td class="col-complete">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 25%"></div>
                            <div class="progress-text">25%</div>
                        </div>
                    </td>
                    <td class="col-status"><span class="status-badge status-in-progress">Eskalasi</span></td>
                    <td class="col-respons">Development team sedang melakukan enhancement fitur dashboard</td>
                    <td class="col-action">
                        <button class="action-btn edit-btn">Edit</button>
                        <button class="action-btn delete-btn">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="sn-footer">Powered by <strong>GIAT CORE</strong></div>
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