<style>
    /* Modal Styles */
    .log-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
    }

    .log-modal-content {
        background-color: #fefefe;
        margin: 3% auto;
        padding: 0;
        border: none;
        border-radius: 16px;
        width: 95%;
        max-width: 900px;
        max-height: 90vh;
        overflow: hidden;
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3);
        animation: log-modalFadeIn 0.3s ease;
    }

    @keyframes log-modalFadeIn {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .log-modal-header {
        background: linear-gradient(135deg, #8b1538 0%, #4a0e4e 100%);
        color: white;
        padding: 24px 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 16px 16px 0 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .log-modal-title {
        font-size: 22px;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .log-modal-close {
        background: none;
        border: none;
        color: white;
        font-size: 28px;
        cursor: pointer;
        padding: 0;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .log-modal-close:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: rotate(90deg);
    }

    .log-modal-body {
        padding: 32px;
        max-height: 75vh;
        overflow-y: auto;
        background: #fafafa;
    }

    .log-modal-body::-webkit-scrollbar {
        width: 8px;
    }

    .log-modal-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .log-modal-body::-webkit-scrollbar-thumb {
        background: #8b1538;
        border-radius: 4px;
    }

    .log-info-section {
        background: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        border: 1px solid #e9ecef;
    }

    .log-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .log-info-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .log-info-label {
        font-weight: 600;
        color: #495057;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .log-info-value {
        font-size: 16px;
        color: #212529;
        padding: 8px 12px;
        background: #f8f9fa;
        border-radius: 6px;
        border-left: 3px solid #8b1538;
    }

    .action-create {
        background: #d4edda !important;
        color: #155724 !important;
        border-left-color: #28a745 !important;
    }

    .action-update {
        background: #fff3cd !important;
        color: #856404 !important;
        border-left-color: #ffc107 !important;
    }

    .action-delete {
        background: #f8d7da !important;
        color: #721c24 !important;
        border-left-color: #dc3545 !important;
    }

    .log-changes-section {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        border: 1px solid #e9ecef;
    }

    .log-changes-title {
        font-size: 18px;
        font-weight: 600;
        margin: 0 0 20px 0;
        color: #495057;
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 12px;
        border-bottom: 2px solid #e9ecef;
    }

    .log-changes-container {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .log-change-item {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 16px;
        border-left: 4px solid #8b1538;
        transition: all 0.2s ease;
    }

    .log-change-item:hover {
        background: #f1f3f4;
        transform: translateX(2px);
    }

    .log-change-field {
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .log-change-values {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .log-single-value {
        display: flex;
        align-items: center;
        gap: 8px;
        width: 100%;
    }

    .log-value-label {
        font-size: 12px;
        color: #6c757d;
        font-weight: 500;
        min-width: 120px;
    }

    .log-value-content {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 14px;
        flex: 1;
    }

    .log-comparison-value {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        width: 100%;
    }

    .log-before-after {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        width: 100%;
    }

    .log-value-section {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .log-section-label {
        font-size: 12px;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .log-comparison-value .fas.fa-arrow-right {
        display: none;
    }

    .old-value {
        background: #fff5f5;
        border: 1px solid #fed7d7;
        color: #742a2a;
        padding: 12px 16px;
        border-radius: 8px;
        word-break: break-word;
        min-height: 40px;
        font-size: 13px;
        line-height: 1.5;
    }

    .new-value {
        background: #f0fff4;
        border: 1px solid #c6f6d5;
        color: #276749;
        padding: 12px 16px;
        border-radius: 8px;
        word-break: break-word;
        min-height: 40px;
        font-size: 13px;
        line-height: 1.5;
    }

    .old-value pre,
    .new-value pre {
        margin: 0;
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        font-size: 12px;
        line-height: 1.4;
        white-space: pre-wrap;
        background: none;
        padding: 0;
        border: none;
        color: inherit;
    }

    .log-changes-table {
        display: none;
    }

    .log-field-value .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-success {
        background: #d4edda;
        color: #155724;
    }

    .badge-secondary {
        background: #e2e3e5;
        color: #383d41;
    }

    .log-no-changes-modal {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .log-no-changes-modal i {
        font-size: 48px;
        margin-bottom: 16px;
        color: #ffc107;
    }

    .log-no-changes-modal p {
        font-size: 16px;
        margin: 0;
    }

    .text-muted {
        color: #6c757d !important;
    }

    .text-success {
        color: #28a745 !important;
    }

    .text-warning {
        color: #ffc107 !important;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .log-modal-content {
            width: 98%;
            margin: 2% auto;
            max-height: 95vh;
        }

        .log-modal-header {
            padding: 20px 24px;
        }

        .log-modal-title {
            font-size: 18px;
        }

        .log-modal-body {
            padding: 20px;
        }

        .log-info-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .log-before-after {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .log-value-section {
            width: 100%;
        }
    }
</style>

<!-- Modal for Changes Detail -->
@foreach($logs as $log)
<div id="modal-{{ $log->id }}" class="log-modal">
    <div class="log-modal-content">
        <div class="log-modal-header">
            <h3 class="log-modal-title">
                <i class="fas fa-info-circle"></i>
                Detail Perubahan - {{ ucfirst($log->action) }}
            </h3>
            <button class="log-modal-close" onclick="closeModal({{ $log->id }})">&times;</button>
        </div>
        <div class="log-modal-body">
            <div class="log-info-section">
                <div class="log-info-grid">
                    <div class="log-info-item">
                        <span class="log-info-label">Waktu</span>
                        <span class="log-info-value">{{ $log->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}</span>
                    </div>
                    <div class="log-info-item">
                        <span class="log-info-label">User</span>
                        <span class="log-info-value">{{ $log->user->name ?? 'System' }}</span>
                    </div>
                    <div class="log-info-item">
                        <span class="log-info-label">Action</span>
                        <span class="log-info-value action-{{ strtolower($log->action) }}">
                            {{ ucfirst($log->action) }}
                        </span>
                    </div>
                    <div class="log-info-item">
                        <span class="log-info-label">Model</span>
                        <span class="log-info-value">{{ class_basename($log->model_type) }}</span>
                    </div>
                    <div class="log-info-item">
                        <span class="log-info-label">Model ID</span>
                        <span class="log-info-value">#{{ $log->model_id }}</span>
                    </div>
                    <div class="log-info-item">
                        <span class="log-info-label">Deskripsi</span>
                        <span class="log-info-value">{{ $log->description }}</span>
                    </div>
                </div>
            </div>

            @php
            $allFields = [];
            $changes = $log->changes;
            
            // Field yang tidak perlu ditampilkan
            $excludedFields = ['id', 'created_at', 'updated_at', 'uic_approvals'];

            if ($changes && is_array($changes)) {
                // Untuk action CREATE
                if ($log->action === 'create') {
                    foreach ($changes as $field => $change) {
                        // Skip field yang dikecualikan
                        if (in_array($field, $excludedFields)) {
                            continue;
                        }
                        
                        $allFields[$field] = [
                            'old' => null,
                            'new' => $change['new'] ?? $change ?? null
                        ];
                    }
                }
                // Untuk action UPDATE
                elseif ($log->action === 'update') {
                    foreach ($changes as $field => $change) {
                        // Skip field yang dikecualikan
                        if (in_array($field, $excludedFields)) {
                            continue;
                        }
                        
                        if (is_array($change) && isset($change['old']) && isset($change['new'])) {
                            $allFields[$field] = [
                                'old' => $change['old'],
                                'new' => $change['new']
                            ];
                        } else {
                            $allFields[$field] = [
                                'old' => $change['old'] ?? null,
                                'new' => $change['new'] ?? $change ?? null
                            ];
                        }
                    }
                }
                // Untuk action DELETE
                elseif ($log->action === 'delete') {
                    foreach ($changes as $field => $change) {
                        // Skip field yang dikecualikan
                        if (in_array($field, $excludedFields)) {
                            continue;
                        }
                        
                        $allFields[$field] = [
                            'old' => $change['old'] ?? $change ?? null,
                            'new' => null
                        ];
                    }
                }
            }
            @endphp

            @if(!empty($allFields))
            <div class="log-changes-section">
                <h4 class="log-changes-title">
                    @if($log->action === 'create')
                    <i class="fas fa-plus-circle text-success"></i> Data yang Ditambahkan
                    @elseif($log->action === 'update')
                    <i class="fas fa-edit text-warning"></i> Data yang Diubah
                    @elseif($log->action === 'delete')
                    <i class="fas fa-trash text-danger"></i> Data yang Dihapus
                    @endif
                </h4>

                <div class="log-changes-container">
                    @foreach($allFields as $field => $values)
                    <div class="log-change-item">
                        <div class="log-change-field">
                            <strong>{{ ucfirst(str_replace('_', ' ', $field)) }}</strong>
                        </div>
                        <div class="log-change-values">
                            @php
                            $oldValue = $values['old'];
                            $newValue = $values['new'];
                            
                            // Format old value
                            if (is_null($oldValue)) {
                                $oldFormatted = '<span class="text-muted">-</span>';
                            } elseif (is_bool($oldValue)) {
                                $oldFormatted = '<span class="badge badge-' . ($oldValue ? 'success' : 'secondary') . '">' . ($oldValue ? 'True' : 'False') . '</span>';
                            } elseif (is_array($oldValue)) {
                                $items = [];
                                foreach ($oldValue as $key => $val) {
                                    if (is_numeric($key)) {
                                        $items[] = is_string($val) ? $val : json_encode($val);
                                    } else {
                                        $items[] = $key . ' = ' . (is_string($val) ? $val : json_encode($val));
                                    }
                                }
                                $oldFormatted = '<pre>' . implode("\n", $items) . '</pre>';
                            } elseif ($oldValue === '') {
                                $oldFormatted = '<span class="text-muted">Kosong</span>';
                            } else {
                                $oldFormatted = $oldValue;
                            }
                            
                            // Format new value
                            if (is_null($newValue)) {
                                $newFormatted = '<span class="text-muted">-</span>';
                            } elseif (is_bool($newValue)) {
                                $newFormatted = '<span class="badge badge-' . ($newValue ? 'success' : 'secondary') . '">' . ($newValue ? 'True' : 'False') . '</span>';
                            } elseif (is_array($newValue)) {
                                $items = [];
                                foreach ($newValue as $key => $val) {
                                    if (is_numeric($key)) {
                                        $items[] = is_string($val) ? $val : json_encode($val);
                                    } else {
                                        $items[] = $key . ' = ' . (is_string($val) ? $val : json_encode($val));
                                    }
                                }
                                $newFormatted = '<pre>' . implode("\n", $items) . '</pre>';
                            } elseif ($newValue === '') {
                                $newFormatted = '<span class="text-muted">Kosong</span>';
                            } else {
                                $newFormatted = $newValue;
                            }
                            @endphp
                            
                            @if($log->action === 'create')
                            <div class="log-single-value">
                                <span class="log-value-label">Nilai:</span>
                                <span class="log-value-content new-value">{!! $newFormatted !!}</span>
                            </div>
                            @elseif($log->action === 'delete')
                            <div class="log-single-value">
                                <span class="log-value-label">Nilai yang dihapus:</span>
                                <span class="log-value-content old-value">{!! $oldFormatted !!}</span>
                            </div>
                            @else
                            <div class="log-comparison-value">
                                <div class="log-before-after">
                                    <div class="log-value-section">
                                        <span class="log-section-label">Sebelum:</span>
                                        <div class="old-value">{!! $oldFormatted !!}</div>
                                    </div>
                                    <div class="log-value-section">
                                        <span class="log-section-label">Sesudah:</span>
                                        <div class="new-value">{!! $newFormatted !!}</div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="log-no-changes-modal">
                <i class="fas fa-exclamation-triangle"></i>
                <p>Tidak ada perubahan data yang tercatat</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endforeach

<script>
    function openModal(logId) {
        const modal = document.getElementById('modal-' + logId);
        if (modal) {
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden'; // Prevent background scroll
        }
    }

    function closeModal(logId) {
        const modal = document.getElementById('modal-' + logId);
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto'; // Restore background scroll
        }
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('log-modal')) {
            event.target.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const visibleModals = document.querySelectorAll('.log-modal[style*="display: block"]');
            visibleModals.forEach(modal => {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            });
        }
    });
</script>