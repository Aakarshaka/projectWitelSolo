@extends('layouts.app')

@section('title', 'TELDA')

@section('content')
<div class="telda-container">
    <!-- Header Section -->
    <div class="telda-header">
        <div class="header-left">
            <h1 class="page-title">SUPPORT NEEDED TELDA</h1>
        </div>
        <div class="header-stats">
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
    <div class="table-container">
        <table class="telda-table">
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
                    <td>Masuk dalam agenda War Room, Forum bersama IG Collaboration </td>
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
                    <td>Proses : Mayoritas Order suspect reseller dipandang perlu percepatan produk solusinya untuk menambah PS</td>
                    <td>PRO</td>
                    <td>TREG</td>
                    <td><span class="progress-partial">25%</span></td>
                    <td><span class="status-eskalasi">Eskalasi</span></td>
                    <td>sudah ada sosialisasi dari EMRM ditarsilisasi RSME, namun belum ada info launch</td>
                </tr>
                
                <tr>
                    <td>3</td>
                    <td>1 on 1 TD</td>
                    <td>Kudus</td>
                    <td>19-Jun-25</td>
                    <td>-</td>
                    <td>Proses : memberikan teknisi as guard baik dalam proses scaling maupun sustaining (DO androidh Indihome)</td>
                    <td>PRO</td>
                    <td>TIF_TA</td>
                    <td><span class="progress-partial">25%</span></td>
                    <td><span class="status-eskalasi">Eskalasi</span></td>
                    <td>yg terkait sustaining (DO Pindah Indihome), SSGS sdh menyampaikan ke TIF agar ada improvement flow yang bisa disepakati. Terkait scaling, perlu diminimkan flow yang jelas ke otorfas lebih tinggi dan aktusl lebih target dari TIF_TA</td>
                </tr>
                
                <tr>
                    <td>4</td>
                    <td>1 on 1 TD</td>
                    <td>Kudus</td>
                    <td>19-Jun-25</td>
                    <td>-</td>
                    <td>Proses : Feedback / kajian DO secara sensitifve office, dukum ulk indihome tndihome Reseller</td>
                    <td>BS Virtual</td>
                    <td>RSMES</td>
                    <td><span class="progress-partial">25%</span></td>
                    <td><span class="status-eskalasi">Eskalasi</span></td>
                    <td>Menjadi usulan ke regional (RSMES) dan perlu kajian virtual ruphya pdfal SA</td>
                </tr>
                
                <tr>
                    <td>5</td>
                    <td>1 on 1 TD</td>
                    <td>Kudus</td>
                    <td>19-Jun-25</td>
                    <td>-</td>
                    <td>Tools : Info Channel penurunan revenue Telda</td>
                    <td>PRO</td>
                    <td>BPFLP</td>
                    <td><span class="progress-partial">25%</span></td>
                    <td><span class="status-eskalasi">Eskalasi</span></td>
                    <td>perlu dukungan data detil di PMS atau dashboard sd Telda/STO. Saat ini blm tersedia. Namun untuk sementara bisa mengeksplore menu di Bright dan data billing di MyFalms</td>
                </tr>
                
                <tr>
                    <td>6</td>
                    <td>1 on 1 TD</td>
                    <td>Kudus</td>
                    <td>19-Jun-25</td>
                    <td>-</td>
                    <td>Tools : Pengawasan UNSC</td>
                    <td>SSGS</td>
                    <td>SSS TR3</td>
                    <td><span class="progress-half">50%</span></td>
                    <td><span class="status-progress">Progress</span></td>
                    <td>Diharapkan pemangku yg akan pindah, untuk tetap pemantauan UNSC agar berjalan dengan baik</td>
                </tr>
                
                <tr>
                    <td>7</td>
                    <td>1 on 1 TD</td>
                    <td>Kudus</td>
                    <td>19-Jun-24</td>
                    <td>-</td>
                    <td>bln-25 Tools : Anisement target 3% km Telda Kudus is sudah</td>
                    <td>PRO</td>
                    <td>BPFLP</td>
                    <td><span class="progress-complete">100%</span></td>
                    <td><span class="status-done">Done</span></td>
                    <td>Penetapan target 2024, akan dilihat berdasarkan realisasi di buld-end 2024. Sudah ada simulasi namun masih akan dievaluasi lebih lanjut agar lebih optimal</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection