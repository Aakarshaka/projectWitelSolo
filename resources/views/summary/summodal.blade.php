<!-- Detail Modal -->
<div id="detailModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close-btn" onclick="closeDetailModal()">&times;</span>
        <h2 class="modal-title">Detail Agenda</h2>
        <div id="detailContent">
            <p>Loading...</p>
        </div>
    </div>
</div>

<style>
    .modal {
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(3px);
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slideIn {
        from {
            transform: translateY(-30px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-content {
        background: linear-gradient(135deg, #8b1538 0%, #4a0e4e 100%);
        color: white;
        margin: 5% auto;
        width: 95%;
        max-height: 80vh;
        border-radius: 2px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        animation: slideIn 0.3s ease-out;
        overflow: auto;
        padding: 5px;
        flex-direction: column;
    }

    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid #e0e0e0;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
    }

    .close-btn {
        position: absolute;
        top: 10px;
        right: 20px;
        font-size: 24px;
        cursor: pointer;
    }

    .close-btn:hover {
        opacity: 1;
        background: rgba(255, 255, 255, 0.2);
    }

    .modal-body {
        padding: 30px;
        flex: 1;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .modal-body::-webkit-scrollbar {
        width: 8px;
    }

    .modal-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .modal-body::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }

    .modal-body::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    .detail-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
        font-size: 14px;
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        overflow-x: auto;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .modal-title {
        background: linear-gradient(135deg, #8b1538 0%, #4a0e4e 100%);
        color: white;
        padding: 20px 30px;
        margin: 0;
        font-size: 24px;
        font-weight: 600;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        flex-shrink: 0;
    }

    .table-container1 {
        overflow: auto;
        max-height: 60vh;
        /* atau tinggi sesuai kebutuhan */
        background: white;
        position: relative;
    }

    .detail-table th {
        background: #a8a8a9;
        color: black;
        padding: 15px 12px;
        text-align: left;
        font-weight: 600;
        border: none;
        white-space: nowrap;
        min-width: 100px;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border-bottom: 2px solid #999;
    }

    .detail-table td {
        padding: 12px;
        border-bottom: 1px solid #e0e0e0;
        vertical-align: top;
        word-wrap: break-word;
    }

    .detail-table tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .detail-table tr:hover {
        background: #faf8fb;
    }

    .detail-table td:nth-child(1) {
        text-align: center;
        font-weight: 600;
        background-color: #f5f5f5;
    }

    .status-badge-summary {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
    }

    .status-action {
        background-color: #ff9800;
        color: white;
    }

    .progress-bar {
        background-color: #e0e0e0;
        border-radius: 10px;
        height: 8px;
        overflow: hidden;
        margin-top: 2px;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #4caf50, #8bc34a);
        transition: width 0.3s ease;
    }

    .no-data {
        text-align: center;
        padding: 40px;
        color: #666;
        font-size: 16px;
    }

    .loading {
        text-align: center;
        padding: 40px;
        color: #666;
        font-size: 16px;
    }

    .loading::after {
        content: '';
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #667eea;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-left: 10px;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    @media (max-width: 768px) {
        .modal-content {
            margin: 2% auto;
            width: 98%;
            max-height: 95vh;
        }

        .modal-header {
            padding: 15px 20px;
        }

        .modal-header h2 {
            font-size: 20px;
        }

        .modal-title {
            padding: 15px 20px;
            font-size: 20px;
        }

        .modal-body {
            padding: 20px;
            max-height: 60vh;
        }

        .detail-table {
            font-size: 12px;
        }

        .detail-table th,
        .detail-table td {
            padding: 8px 6px;
        }
    }
</style>

<script>
    function openDetailModal(type, value, progress) {
        const modal = document.getElementById('detailModal');
        const content = document.getElementById('detailContent');
        content.innerHTML = '<p>Loading...</p>';
        modal.style.display = 'block';

        let url = '/supportneeded/detail?';
        if (type === 'uic') {
            url += `uic=${encodeURIComponent(value)}&progress=${encodeURIComponent(progress)}`;
        } else if (type === 'agenda') {
            url += `agenda=${encodeURIComponent(value)}&progress=${encodeURIComponent(progress)}`;
        } else if (type === 'unit') {
            url += `unit=${encodeURIComponent(value)}&progress=${encodeURIComponent(progress)}`;
        } else {
            content.innerHTML = '<p>Invalid request type.</p>';
            return;
        }

        fetch(url)
            .then(response => {
                if (!response.ok) throw new Error("Failed to fetch");
                return response.json();
            })
            .then(data => {
                if (!Array.isArray(data) || data.length === 0) {
                    content.innerHTML = '<p>No data found.</p>';
                } else {
                    let html = '<div class="table-container1">';
                    html += '<table class="detail-table">';
                    html += `<thead><tr>
                    <th>No</th>
                    <th>Agenda</th>
                    <th>Unit/Telda</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th># Off Day</th>
                    <th>Notes to Follow Up</th>
                    <th>UIC</th>
                    <th>Progress</th>
                    <th>% Complete</th>
                    <th>Status</th>
                    <th>Response UIC</th>
                </tr></thead><tbody>`;

                    data.forEach((item, index) => {
                        const statusClass = item.status?.toLowerCase() === 'action' ? 'status-action' :
                            item.status?.toLowerCase() === 'done' ? 'status-done' : 'status-pending-summary';
                        html += `<tr>
                        <td>${index + 1}</td>
                        <td>${item.agenda || '-'}</td>
                        <td>${item.unit_or_telda || '-'}</td>
                        <td>${item.start_date || '-'}</td>
                        <td>${item.end_date || '-'}</td>
                        <td>${item.off_day || 0}</td>
                        <td>${item.notes_to_follow_up ? item.notes_to_follow_up.replace(/\n/g, '<br>') : '-'}</td>
                        <td>${item.uic || '-'}</td>
                        <td>${item.progress || '-'}</td>
                        <td>
                            <div style="display: flex; align-items: center;">
                                <span style="margin-right: 8px;">${item.complete || 0}%</span>
                                <div class="progress-bar" style="width: 60px;">
                                    <div class="progress-fill" style="width: ${item.complete || 0}%;"></div>
                                </div>
                            </div>
                        </td>
                        <td><span class="status-badge-summary ${statusClass}">${item.status || '-'}</span></td>
                        <td>${item.response_uic ? item.response_uic.replace(/\n/g, '<br>') : '-'}</td>
                    </tr>`;
                    });

                    html += '</tbody></table>';
                    content.innerHTML = html;
                }
            })
            .catch((error) => {
                console.error(error);
                content.innerHTML = '<p>Failed to load data.</p>';
            });
    }

    function closeDetailModal() {
        document.getElementById('detailModal').style.display = 'none';
    }
    window.onclick = function (event) {
        const modal = document.getElementById('detailModal');
        if (event.target === modal) {
            closeDetailModal();
        }
    }
</script>