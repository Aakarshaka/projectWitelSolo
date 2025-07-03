@extends('layouts.app')

@section('title', 'Summary')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/summary.css') }}">
@endpush

@section('content')
<div class="content-demo">
    <div style="text-align: center; margin-bottom: 30px;">
        <h1 style="font-size: 36px; font-weight: bold; color: #4A0E4E; margin: 0;">SUMMARY</h1>
    </div>

    <div style="display: flex; gap: 20px; margin-bottom: 30px;">
        <!-- Category -->
        <div class="collab-container" style="width: 200px; flex-shrink: 0;">
            <div class="collab-header">
                <h2>CATEGORY</h2>
            </div>
            <div class="collab-table-container">
                <table class="collab-table">
                    <thead>
                        <tr><th>&nbsp;</th></tr>
                    </thead>
                    <tbody>
                        <tr><td class="bg-danger text-white fw-bold">Total</td></tr>
                        <tr><td>Close</td></tr>
                        <tr><td>Progress</td></tr>
                        <tr><td>Need Discuss</td></tr>
                        <tr><td>Eskalasi</td></tr>
                        <tr class="bg-info-subtle"><td>%Close</td></tr>
                        <tr class="bg-warning-subtle"><td>Rata2 %Progress</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Support Needed -->
        <div class="collab-container" style="flex: 1;">
            <div class="collab-header">
                <h2>SUPPORT NEEDED</h2>
            </div>
            <div class="collab-table-container">
                <table class="collab-table">
                    <thead>
                        <tr>
                            <th>TELDA CORE</th><th>AM CORE</th><th>UNIT CORE</th><th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="bg-danger text-white fw-bold">12</td>
                            <td class="bg-danger text-white fw-bold">0</td>
                            <td class="bg-danger text-white fw-bold">3</td>
                            <td class="bg-danger text-white fw-bold">15</td>
                        </tr>
                        <tr><td>2</td><td>0</td><td>0</td><td>2</td></tr>
                        <tr><td>3</td><td>0</td><td>0</td><td>3</td></tr>
                        <tr><td>0</td><td>0</td><td>0</td><td>0</td></tr>
                        <tr><td>5</td><td>0</td><td>3</td><td>8</td></tr>
                        <tr class="bg-info-subtle">
                            <td>17%</td><td>0%</td><td>0%</td><td>13%</td>
                        </tr>
                        <tr class="bg-warning-subtle">
                            <td>40%</td><td>0%</td><td>25%</td><td>22%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Eskalasi -->
        <div class="collab-container" style="flex: 1;">
            <div class="collab-header">
                <h2>ESKALASI</h2>
            </div>
            <div class="collab-table-container">
                <table class="collab-table">
                    <thead>
                        <tr>
                            <th>to TREG</th><th>to TIF_TA</th><th>to Sal</th>
                            <th>to GSD</th><th>Others</th><th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="bg-danger text-white fw-bold">19</td>
                            <td class="bg-danger text-white"></td>
                            <td class="bg-danger text-white"></td>
                            <td class="bg-danger text-white"></td>
                            <td class="bg-danger text-white"></td>
                            <td class="bg-danger text-white fw-bold">19</td>
                        </tr>
                        <tr><td>4</td><td></td><td></td><td></td><td></td><td>4</td></tr>
                        <tr><td>0</td><td></td><td></td><td></td><td></td><td>0</td></tr>
                        <tr><td>0</td><td></td><td></td><td></td><td></td><td>0</td></tr>
                        <tr><td>0</td><td></td><td></td><td></td><td></td><td>0</td></tr>
                        <tr class="bg-info-subtle">
                            <td>21%</td><td></td><td></td><td></td><td></td><td>21%</td>
                        </tr>
                        <tr class="bg-warning-subtle">
                            <td>32%</td><td></td><td></td><td></td><td></td><td>32%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Total Summary -->
    <div class="collab-container">
        <div class="collab-header">
            <h2>TOTAL</h2>
        </div>
        <div class="collab-table-container">
            <table class="collab-table">
                <thead>
                    <tr>
                        <th>Total Cases</th><th>Close Cases</th><th>In Progress</th>
                        <th>Need Discuss</th><th>Eskalasi</th><th>%Close</th><th>Rata2 %Progress</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="bg-primary text-white fw-bold">34</td>
                        <td class="bg-primary text-white fw-bold">6</td>
                        <td class="bg-primary text-white fw-bold">3</td>
                        <td class="bg-primary text-white fw-bold">0</td>
                        <td class="bg-primary text-white fw-bold">8</td>
                        <td class="bg-primary text-white fw-bold">18%</td>
                        <td class="bg-primary text-white fw-bold">27%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
