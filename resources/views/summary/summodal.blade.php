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
        color: #333;
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
        display: inline-block;
    }

    .status-action {
        background-color: #ff9800;
        color: white;
    }

    .status-eskalasi {
        background-color: #f44336;
        color: white;
    }

    .status-support {
        background-color: #2196f3;
        color: white;
    }

    .progress-open {
        background-color: #ff5722;
        color: white;
    }

    .progress-discuss {
        background-color: #ff9800;
        color: white;
    }

    .progress-progress {
        background-color: #2196f3;
        color: white;
    }

    .progress-done {
        background-color: #4caf50;
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
    const modalTitle = document.querySelector('.modal-title');
    
    // Update modal title
    modalTitle.textContent = `Detail ${type.toUpperCase()} - ${value} (${progress})`;
    
    content.innerHTML = '<div class="loading">Loading data...</div>';
    modal.style.display = 'block';

    // Build URL dengan parameter yang benar
    let url = '/summary/detail?';
    url += `type=${encodeURIComponent(type)}`;
    url += `&value=${encodeURIComponent(value)}`;
    url += `&progress=${encodeURIComponent(progress)}`;

    console.log('Fetching URL:', url); // Debug log

    fetch(url)
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            if (!response.ok) {
                if (response.status === 404) {
                    throw new Error(`Route not found. Please check if the route '/summary/detail' is registered in routes/web.php`);
                } else if (response.status === 500) {
                    throw new Error(`Server error. Please check the server logs for more details.`);
                } else {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
            }
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data); // Debug log
            
            // Check if response contains error
            if (data.error) {
                throw new Error(data.message || 'Server returned an error');
            }
            
            if (!Array.isArray(data) || data.length === 0) {
                content.innerHTML = '<div class="no-data">No data found for this filter.</div>';
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
                    // Status badge class
                    let statusClass = 'status-badge-summary ';
                    if (item.status === 'Action') statusClass += 'status-action';
                    else if (item.status === 'Eskalasi') statusClass += 'status-eskalasi';
                    else if (item.status === 'Support Needed') statusClass += 'status-support';
                    else statusClass += 'status-action';

                    // Progress badge class
                    let progressClass = 'status-badge-summary ';
                    if (item.progress === 'Open') progressClass += 'progress-open';
                    else if (item.progress === 'Need Discuss') progressClass += 'progress-discuss';
                    else if (item.progress === 'On Progress') progressClass += 'progress-progress';
                    else if (item.progress === 'Done') progressClass += 'progress-done';

                    // Format UIC to show multiple UICs clearly
                    let uicDisplay = item.uic || '-';
                    if (uicDisplay !== '-' && uicDisplay.includes(',')) {
                        // Split by comma and trim spaces, then rejoin with proper formatting
                        uicDisplay = uicDisplay.split(',').map(u => u.trim()).join(', ');
                    }

                    // Gunakan tanggal yang sudah diformat dari backend
                    // Prioritas: formatted -> display -> original -> fallback
                    const startDate = item.start_date_formatted || item.start_date_display || item.start_date || '-';
                    const endDate = item.end_date_formatted || item.end_date_display || item.end_date || '-';

                    // Gunakan completion_percentage dari backend, fallback ke complete
                    const completionPercentage = item.completion_percentage || item.complete || 0;

                    html += `<tr>
                        <td>${index + 1}</td>
                        <td>${item.agenda || '-'}</td>
                        <td>${item.unit_or_telda || '-'}</td>
                        <td class="date-cell" title="${startDate}">${startDate}</td>
                        <td class="date-cell" title="${endDate}">${endDate}</td>
                        <td>${item.off_day || 0}</td>
                        <td style="max-width: 200px; word-wrap: break-word;">${item.notes_to_follow_up ? item.notes_to_follow_up.replace(/\n/g, '<br>') : '-'}</td>
                        <td><span title="${uicDisplay}">${uicDisplay}</span></td>
                        <td><span class="${progressClass}">${item.progress || '-'}</span></td>
                        <td>
                            <div style="display: flex; align-items: center;">
                                <span style="margin-right: 8px;">${completionPercentage}%</span>
                                <div class="progress-bar" style="width: 60px;">
                                    <div class="progress-fill" style="width: ${completionPercentage}%;"></div>
                                </div>
                            </div>
                        </td>
                        <td><span class="${statusClass}">${item.status || '-'}</span></td>
                        <td style="max-width: 200px; word-wrap: break-word;">${item.response_uic ? item.response_uic.replace(/\n/g, '<br>') : '-'}</td>
                    </tr>`;
                });

                html += '</tbody></table></div>';
                content.innerHTML = html;
            }
        })
        .catch((error) => {
            console.error('Error fetching data:', error);
            
            let errorMessage = 'Failed to load data. Please try again.';
            
            if (error.message.includes('Route not found')) {
                errorMessage = `
                    <div style="text-align: left; padding: 20px;">
                        <h4 style="color: #f44336; margin-bottom: 10px;">Route Not Found Error</h4>
                        <p>The route '/summary/detail' is not registered.</p>
                        <p><strong>Solution:</strong> Add this route to your routes/web.php file:</p>
                        <pre style="background: #f5f5f5; padding: 10px; border-radius: 4px; font-size: 12px; overflow-x: auto;">
Route::get('/summary/detail', [SumController::class, 'getDetail'])->name('summary.detail');
                        </pre>
                        <p>Or check if the route is properly registered and accessible.</p>
                    </div>
                `;
            } else if (error.message.includes('Server error')) {
                errorMessage = `
                    <div style="text-align: left; padding: 20px;">
                        <h4 style="color: #f44336; margin-bottom: 10px;">Server Error</h4>
                        <p>There was an error processing your request.</p>
                        <p><strong>Solution:</strong> Check the Laravel logs for more details:</p>
                        <pre style="background: #f5f5f5; padding: 10px; border-radius: 4px; font-size: 12px;">
tail -f storage/logs/laravel.log
                        </pre>
                    </div>
                `;
            }
            
            content.innerHTML = `<div class="no-data">${errorMessage}</div>`;
        });
}

function closeDetailModal() {
    document.getElementById('detailModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function (event) {
    const modal = document.getElementById('detailModal');
    if (event.target === modal) {
        closeDetailModal();
    }
}

// Close modal with ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeDetailModal();
    }
});

// Helper function to debug UIC matching
function debugUicMatching(uic, searchValue) {
    console.log('UIC Debug:', {
        originalUic: uic,
        searchValue: searchValue,
        includesCheck: uic.includes(searchValue),
        exactMatch: uic === searchValue,
        splitUics: uic.split(',').map(u => u.trim()),
        foundInSplit: uic.split(',').map(u => u.trim()).includes(searchValue)
    });
}

// Helper function untuk format tanggal di JavaScript (jika diperlukan)
function formatDateForDisplay(dateString) {
    if (!dateString || dateString === '-') return '-';
    
    try {
        // Jika sudah dalam format yang bagus, langsung return
        if (dateString.includes(' ')) {
            return dateString;
        }
        
        // Jika masih format lama, coba parse dan format ulang
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return dateString; // Jika tidak valid, return original
        
        // Format manual ke bahasa Indonesia
        const months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        
        const day = date.getDate();
        const month = months[date.getMonth()];
        const year = date.getFullYear();
        
        return `${day} ${month} ${year}`;
    } catch (error) {
        console.warn('Error formatting date:', error);
        return dateString; // Return original jika ada error
    }
}
</script>