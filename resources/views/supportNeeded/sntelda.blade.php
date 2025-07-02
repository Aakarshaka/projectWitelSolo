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
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>1 on 1 TD</td>
                    <td>Kudus</td>
                    <td>19-Jun-25</td>
                    <td>-</td>
                    <td>Masuk dalam agenda War Room, Forum bersama IG Collaboration</td>
                    <td>PRO</td>
                    <td>TG</td>
                    <td><span class="progress-complete">100%</span></td>
                    <td><span class="status-done">Done</span></td>
                    <td>Masuk dalam agenda War Room, Forum bersama TG</td>
                </tr>                
                <tr>
                    <td>2</td>
                    <td>1 on 1 TD</td>
                    <td>Kudus</td>
                    <td>19-Jun-25</td>
                    <td>-</td>
                    <td>Feedback / kajian DO secara sensitifve office, dukum ulk indihome tndihome Reseller</td>
                    <td>PRO</td>
                    <td>TG</td>
                    <td><span class="progress-complete">100%</span></td>
                    <td><span class="status-done">Done</span></td>
                    <td>Masuk dalam agenda War Room, Forum bersama TG</td>
                </tr>
                <!-- isi baris lainnya sama -->
            </tbody>
        </table>
    </div>
</div>
@endsection
