<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WARROOM ACTIVITY</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 200px;
            height: 100vh;
            background: linear-gradient(-120deg, #4A0E4E 0%, #8B0000 100%);
            color: white;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 20px 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            margin: 0;
            color: #00BFFF;
        }

        .logo-sub {
            font-size: 20px;
            color: yellow;
            margin-left: 5px;
        }

        .sidebar-nav {
            padding: 15px 10px;
        }

        .nav-section {
            margin-bottom: 8px;
        }

        .nav-section-header {
            padding: 15px 5px 8px 5px;
            margin-top: 15px;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #FFD700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-item {
            display: block;
            padding: 12px 15px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 3px 0;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-size: 13px;
            text-align: center;
            font-weight: 500;
            cursor: pointer;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #FFD700;
            transform: translateX(3px);
            border-color: rgba(255, 215, 0, 0.5);
        }

        .nav-item.active {
            background: rgba(255, 215, 0, 0.2);
            color: #FFD700;
            border-color: #FFD700;
        }

        .main-content {
            margin-left: 200px;
            padding: 20px;
            min-height: 100vh;
        }

        /* Summary Section */
        .summary-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 25px;
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
        }

        .summary-header {
            background: linear-gradient(135deg, #8B0000 0%, #4A0E4E 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .summary-title {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            color: #FDD835;
        }

        .summary-subtitle {
            font-size: 12px;
            color: #ddd;
            margin-top: 5px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .summary-item {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            border-left: 4px solid #4A0E4E;
        }

        .summary-label {
            font-size: 12px;
            color: #666;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 16px;
            font-weight: bold;
            color: #4A0E4E;
        }

        .summary-agenda {
            grid-column: 1 / -1;
            background: #fff5f5;
            border-left: 4px solid #8B0000;
        }

        .agenda-list {
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }

        /* Filter Section */
        .filter-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .filter-label {
            font-weight: 500;
            color: #4A0E4E;
            font-size: 14px;
        }

        .filter-select {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 14px;
            color: #333;
            background: white;
        }

        .filter-select:focus {
            border-color: #4A0E4E;
            outline: none;
        }

        /* Table Section */
        .collab-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .collab-header {
            background: linear-gradient(135deg, #8B0000 0%, #4A0E4E 100%);
            color: white;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            color: #FDD835;
        }

        .add-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #59e946;
            color: #4A0E4E;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .add-button:hover {
            background-color: #40ae32;
            color: white;
        }

        .collab-table-container {
            overflow-x: auto;
            overflow-y: auto;
        }

        .collab-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .collab-table th {
            background: #4A0E4E;
            color: white;
            padding: 12px 8px;
            text-align: center;
            font-weight: bold;
            position: sticky;
            top: 0;
            z-index: 10;
            border: 1px solid #2c3e50;
            font-size: 11px;
        }

        .collab-table td {
            padding: 10px 8px;
            border: 1px solid #ddd;
            vertical-align: top;
            background: white;
        }

        .collab-table tbody tr:nth-child(even) {
            background: #f9f1f6;
        }

        .collab-table tbody tr:hover {
            background: #fbe9f0;
        }

        .action-button {
            display: inline-block;
            padding: 4px 8px;
            font-size: 10px;
            border-radius: 4px;
            color: white;
            text-decoration: none;
            margin: 2px;
            text-align: center;
            min-width: 50px;
            border: none;
            cursor: pointer;
        }

        .action-edit {
            background-color: #59e946;
            color: #4A0E4E;
        }

        .action-edit:hover {
            background-color: #40ae32;
            color: white;
        }

        .action-delete {
            background-color: #e93d3a;
            color: #4A0E4E;
        }

        .action-delete:hover {
            background-color: #C62828;
            color: white;
        }

        .status-done {
            background: #43A047;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }

        .status-progress {
            background: #039BE5;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }

        .status-pending {
            background: #EF6C00;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .summary-grid {
                grid-template-columns: 1fr;
            }
            
            .filter-container {
                flex-direction: column;
                align-items: stretch;
            }
            
            .collab-header {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2 class="logo">GIAT<span class="logo-sub">CORE</span></h2>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <a href="#" class="nav-item">
                    <span class="nav-text">Dashboard</span>
                    <span class="nav-subtext">Giat</span>
                </a>
            </div>

            <div class="nav-section">
                <a href="#" class="nav-item active">
                    <span class="nav-text">WAR ROOM</span>
                    <span class="nav-subtext">Activity</span>
                </a>
            </div>

            <div class="nav-section">
                <a href="#" class="nav-item">
                    <span class="nav-text">Summary</span>
                </a>
            </div>

            <div class="nav-section-header">
                <span class="section-title">Support Needed</span>
            </div>

            <div class="nav-section">
                <a href="#" class="nav-item">
                    <span class="nav-text">SN UNIT</span>
                </a>
            </div>
            <div class="nav-section">
                <a href="#" class="nav-item">
                    <span class="nav-text">SN TELDA</span>
                </a>
            </div>
            <div class="nav-section">
                <a href="#" class="nav-item">
                    <span class="nav-text">SN AM</span>
                </a>
            </div>

            <div class="nav-section-header">
                <span class="section-title">ESKALASI</span>
            </div>

            <div class="nav-section">
                <a href="#" class="nav-item">
                    <span class="nav-text">to TREG</span>
                </a>
            </div>
            <div class="nav-section">
                <a href="#" class="nav-item">
                    <span class="nav-text">to TIF_TA</span>
                </a>
            </div>
            <div class="nav-section">
                <a href="#" class="nav-item">
                    <span class="nav-text">to TSEL</span>
                </a>
            </div>
            <div class="nav-section">
                <a href="#" class="nav-item">
                    <span class="nav-text">to GSD</span>
                </a>
            </div>
            <div class="nav-section">
                <a href="#" class="nav-item">
                    <span class="nav-text">to UNIT WITEL</span>
                </a>
            </div>

            <div class="nav-section-header"></div>

            <div class="nav-section">
                <a href="#" class="nav-item">
                    <span class="nav-text">Logout</span>
                </a>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Summary Section -->
        <div class="summary-container">
            <div class="summary-header">
                <h2 class="summary-title">WAR ROOM ACTIVITY</h2>
                <p class="summary-subtitle">Forum WARROOM bulan Juni (start 24 Juni 2025)</p>
            </div>
            
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-label">Jumlah Agenda</div>
                    <div class="summary-value">9</div>
                </div>
                
                <div class="summary-item">
                    <div class="summary-label">Jumlah Action Plan</div>
                    <div class="summary-value">33</div>
                </div>
                
                <div class="summary-item">
                    <div class="summary-label">Masuk Eskalasi</div>
                    <div class="summary-value">3</div>
                </div>
                
                <div class="summary-item">
                    <div class="summary-label">Status</div>
                    <div class="summary-value">Active</div>
                </div>
                
                <div class="summary-item summary-agenda">
                    <div class="summary-label">Nama Agenda</div>
                    <div class="agenda-list">
                        1) 1 on 1 Hotda<br>
                        2) Review AOSODORNORO & EDK all Segmen<br>
                        3) 1 on 1 AM BS<br>
                        4) Review Rising Star, championeer, KM<br>
                        5) WAR Witel Solo
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-container">
            <label class="filter-label">Filter Bulan:</label>
            <select class="filter-select" id="monthFilter">
                <option value="">Semua Bulan</option>
                <option value="2025-01">Januari 2025</option>
                <option value="2025-02">Februari 2025</option>
                <option value="2025-03">Maret 2025</option>
                <option value="2025-04">April 2025</option>
                <option value="2025-05">Mei 2025</option>
                <option value="2025-06" selected>Juni 2025</option>
                <option value="2025-07">Juli 2025</option>
                <option value="2025-08">Agustus 2025</option>
                <option value="2025-09">September 2025</option>
                <option value="2025-10">Oktober 2025</option>
                <option value="2025-11">November 2025</option>
                <option value="2025-12">Desember 2025</option>
            </select>
        </div>

        <!-- Table Section -->
        <div class="collab-container">
            <div class="collab-header">
                <div class="header-left">
                    <h1 class="page-title">Detail Activity</h1>
                </div>
                <div class="header-right">
                    <a href="#" class="add-button">ADD+</a>
                </div>
            </div>

            <div class="collab-table-container">
                <table class="collab-table">
                    <thead>
                        <tr>
                            <th style="width: 40px;">NO</th>
                            <th style="width: 90px;">TGL</th>
                            <th style="width: 200px;">AGENDA</th>
                            <th style="width: 120px;">PESERTA</th>
                            <th style="width: 250px;">PEMBAHASAN</th>
                            <th style="width: 200px;">ACTION PLAN</th>
                            <th style="width: 150px;">SUPPORT NEEDED</th>
                            <th style="width: 150px;">INFO KOMPETITOR</th>
                            <th style="width: 80px;">JML ACTION PLAN</th>
                            <th style="width: 180px;">UPDATE ACTION PLAN</th>
                            <th style="width: 100px;">STATUS ACTION PLAN</th>
                            <th style="width: 100px;">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="text-align: center;">1</td>
                            <td style="text-align: center;">24-Jun-25</td>
                            <td>1 on 1 Hotda</td>
                            <td>Manager, Hotda Team</td>
                            <td>Review performance Q2 dan strategi Q3, diskusi target pencapaian dan obstacle yang dihadapi</td>
                            <td>Follow up customer complaint, improvement SLA response time</td>
                            <td>Technical Support dari Tim IT</td>
                            <td>Competitor A launching new product bundle</td>
                            <td style="text-align: center;">5</td>
                            <td>3 dari 5 action plan sudah completed, 2 masih dalam progress dengan target completion minggu depan</td>
                            <td style="text-align: center;"><span class="status-progress">Progress</span></td>
                            <td style="text-align: center;">
                                <a href="#" class="action-button action-edit">EDIT</a>
                                <button type="button" class="action-button action-delete">DELETE</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Month filter functionality
        document.getElementById('monthFilter').addEventListener('change', function() {
            const selectedMonth = this.value;
            // Add filter logic here
            console.log('Selected month:', selectedMonth);
        });

        // Navigation active state
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelectorAll('.nav-item').forEach(navItem => {
                    navItem.classList.remove('active');
                });
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>