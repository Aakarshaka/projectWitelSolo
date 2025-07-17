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
        margin: 5% auto;
        padding: 0;
        border: none;
        border-radius: 12px;
        width: 90%;
        max-width: 800px;
        max-height: 80vh;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
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
        padding: 20px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 12px 12px 0 0;
    }

    .log-modal-title {
        font-size: 20px;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .log-modal-close {
        background: none;
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        padding: 0;
        width: 30px;
        height: 30px;
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
        padding: 30px;
        max-height: 60vh;
        overflow-y: auto;
    }

    .log-modal-body::-webkit-scrollbar {
        width: 8px;
    }

    .log-modal-body::-webkit-scrollbar-track {
        background: #f8f6f9;
    }

    .log-modal-body::-webkit-scrollbar-thumb {
        background: #4a0e4e;
        border-radius: 4px;
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

            if ($changes && is_array($changes)) {
            // Untuk action CREATE
            if ($log->action === 'create') {
            foreach ($changes as $field => $change) {
            $allFields[$field] = [
            'old' => null,
            'new' => $change['new'] ?? $change ?? null
            ];
            }
            }
            // Untuk action UPDATE
            elseif ($log->action === 'update') {
            foreach ($changes as $field => $change) {
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
                    <table class="log-changes-table">
                        <thead>
                            <tr>
                                <th width="25%">Field</th>
                                <th width="37.5%">
                                    @if($log->action === 'create')
                                    Sebelum (Kosong)
                                    @endif
                                </th>
                                <th width="37.5%">
                                    @if($log->action === 'delete')
                                    Setelah (Dihapus)
                                    @endif
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allFields as $field => $values)
                            <tr>
                                <td class="log-field-name">
                                    <strong>{{ ucfirst(str_replace('_', ' ', $field)) }}</strong>
                                </td>
                                <td class="log-field-value-cell">
                                    @if($log->action === 'create')
                                    <div class="log-field-value log-empty-value">
                                        <i class="fas fa-minus"></i>
                                        <span class="text-muted">Kosong</span>
                                    </div>
                                    @else
                                    @if($values['old'] !== null)
                                    <div class="log-field-value log-old-value">
                                        @if(is_array($values['old']))
                                        <pre>{{ json_encode($values['old'], JSON_PRETTY_PRINT) }}</pre>
                                        @elseif(is_bool($values['old']))
                                        <span class="badge badge-{{ $values['old'] ? 'success' : 'secondary' }}">
                                            {{ $values['old'] ? 'True' : 'False' }}
                                        </span>
                                        @elseif($values['old'] === '')
                                        <span class="text-muted">Kosong</span>
                                        @else
                                        <span>{{ $values['old'] }}</span>
                                        @endif
                                    </div>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                    @endif
                                </td>
                                <td class="log-field-value-cell">
                                    @if($log->action === 'delete')
                                    <div class="log-field-value log-deleted-value">
                                        <i class="fas fa-trash"></i>
                                        <span class="text-muted">Dihapus</span>
                                    </div>
                                    @else
                                    @if($values['new'] !== null)
                                    <div class="log-field-value log-new-value">
                                        @if(is_array($values['new']))
                                        <pre>{{ json_encode($values['new'], JSON_PRETTY_PRINT) }}</pre>
                                        @elseif(is_bool($values['new']))
                                        <span class="badge badge-{{ $values['new'] ? 'success' : 'secondary' }}">
                                            {{ $values['new'] ? 'True' : 'False' }}
                                        </span>
                                        @elseif($values['new'] === '')
                                        <span class="text-muted">Kosong</span>
                                        @else
                                        <span>{{ $values['new'] }}</span>
                                        @endif
                                    </div>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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