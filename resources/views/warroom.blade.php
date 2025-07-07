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
        <a href="#" class="stat-value add-stat add-button">ADD+</a>
    </div>

    <div class="collab-table-container">
        <table class="collab-table">
            <thead>
                <tr>
                    <th class="col-no">NO</th>
                    <th class="col-start">TGL</th>
                    <th class="col-event">AGENDA</th>
                    <th class="col-unit">PESERTA</th>
                    <th class="col-notes">PEMBAHASAN</th>
                    <th class="col-notes">ACTION PLAN</th>
                    <th class="col-unit-collab">SUPPORT NEEDED</th>
                    <th class="col-notes">INFO KOMPETITOR</th>
                    <th class="col-complete">JML ACTION</th>
                    <th class="col-notes">UPDATE ACTION</th>
                    <th class="col-status">STATUS</th>
                    <th class="col-action">ACTION</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>24-Jun-25</td>
                    <td>1 on 1 Hotda</td>
                    <td>Manager, Hotda Team</td>
                    <td>Review performance dan strategi Q3</td>
                    <td>Implementasi sistem CRM</td>
                    <td>IT Support, Budget approval</td>
                    <td>Kompetitor X launching</td>
                    <td>5</td>
                    <td>CRM 70% complete</td>
                    <td>
                        <span class="status-progress">Progress</span>
                    </td>
                    <td>
                        <a href="#" class="action-button action-edit">EDIT</a>
                        <button class="action-button action-delete">DELETE</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection