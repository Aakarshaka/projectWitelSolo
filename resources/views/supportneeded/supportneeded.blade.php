@extends('layouts.layout')

@section('title', 'SUPPORT NEEDED')

@section('content')

<div class="main-content">
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <h1>SUPPORT NEEDED</h1>
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-label">Total</div>
                    <div class="stat-value">{{ $total }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Close</div>
                    <div class="stat-value">{{ $close }} ({{ $closePercentage }}%)</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Actual Progress</div>
                    <div class="stat-value">{{ round($avgProgress, 1) }}%</div>
                </div>
                <a href="{{ route('supportneeded.export') }}" class="add-btn" type="button">
                    Export to Excel
                </a>
                <button class="add-btn" type="button" onclick="openModal('addSupportModal')">ADD+</button>
            </div>
        </div>

        <!-- Filter Controls -->
        <div class="controls">
            <div class="filters">
                <form method="GET" action="{{ route('supportneeded.index') }}" class="filters">
                    <div class="filter-group">
                        <label class="filter-label">Status</label>
                        <select class="filter-select" name="status">
                            <option value="">All Status</option>
                            <option value="Action" {{ request('status') == 'Action' ? 'selected' : '' }}>Action</option>
                            <option value="Eskalasi" {{ request('status') == 'Eskalasi' ? 'selected' : '' }}>Eskalasi</option>
                            <option value="Support Needed" {{ request('status') == 'Support Needed' ? 'selected' : '' }}>Support Needed</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Progress</label>
                        <select class="filter-select" name="progress">
                            <option value="">All Progress</option>
                            <option value="Open" {{ request('progress') == 'Open' ? 'selected' : '' }}>Open</option>
                            <option value="Need Discuss" {{ request('progress') == 'Need Discuss' ? 'selected' : '' }}>Need Discuss</option>
                            <option value="On Progress" {{ request('progress') == 'On Progress' ? 'selected' : '' }}>On Progress</option>
                            <option value="Done" {{ request('progress') == 'Done' ? 'selected' : '' }}>Done</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Witel or Unit</label>
                        <select class="filter-select" name="unit_or_telda">
                            <option value="">All Witel or Unit</option>
                            @php
                                $units = [
                                    'TELDA BLORA', 'TELDA BOYOLALI', 'TELDA JEPARA', 'TELDA KLATEN', 
                                    'TELDA KUDUS', 'MEA SOLO', 'TELDA PATI', 'TELDA PURWODADI', 
                                    'TELDA REMBANG', 'TELDA SRAGEN', 'TELDA WONOGIRI', 'BS', 'GS', 
                                    'PRQ', 'SSGS', 'RSO WITEL'
                                ];
                            @endphp
                            @foreach($units as $unit)
                                <option value="{{ $unit }}" {{ request('unit_or_telda') == $unit ? 'selected' : '' }}>
                                    {{ $unit }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">UIC</label>
                        <select class="filter-select" name="uic">
                            <option value="">All UIC</option>
                            @php
                                $uics = [
                                    'BS', 'GS', 'RLEGS', 'RSO REGIONAL', 'RSO WITEL', 'ED', 
                                    'TIF', 'TSEL', 'GSD', 'SSGS', 'PRQ', 'RSMES', 'BPPLP', 'SSS'
                                ];
                            @endphp
                            @foreach($uics as $uic)
                                <option value="{{ $uic }}" {{ request('uic') == $uic ? 'selected' : '' }}>
                                    {{ $uic }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <button type="submit" class="filter-btn">FILTER</button>
                </form>
                
                <form action="{{ route('supportneeded.index') }}" method="GET" class="search-form">
                    <input type="text" name="search" class="search-box" placeholder="Search agenda or unit..." value="{{ request('search') }}">
                    <button type="submit" class="filter-btn">SEARCH</button>
                </form>
            </div>
        </div>

        <div class="scroll-hint">
            ← Geser ke kiri/kanan untuk melihat semua Kolom →
        </div>

        <!-- Table Section -->
        <div class="table-container-sn">
            <div class="table-wrapper" id="tableWrapper">
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
                            <th class="col-approval">UIC Approval Status</th>
                            <th class="col-progress">Progress</th>
                            <th class="col-complete">% Complete</th>
                            <th class="col-status">Status</th>
                            <th class="col-respons">Response UIC</th>
                            <th class="col-action">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $index => $item)
                        <tr>
                            <td class="col-no">{{ $index + 1 }}</td>
                            <td class="col-agenda">{{ $item->agenda }}</td>
                            <td class="col-unit">{{ $item->unit_or_telda }}</td>
                            <td class="col-start">
                                {{ $item->start_date ? \Carbon\Carbon::parse($item->start_date)->format('d M Y') : '-' }}
                            </td>
                            <td class="col-end">
                                {{ $item->end_date ? \Carbon\Carbon::parse($item->end_date)->format('d M Y') : '-' }}
                            </td>
                            <td class="col-off">
                                @if($item->start_date)
                                    @if($item->progress === 'Done' && $item->end_date)
                                        {{ \Carbon\Carbon::parse($item->start_date)->diffInDays(\Carbon\Carbon::parse($item->end_date)) + 1 }}
                                    @else
                                        {{ ceil(\Carbon\Carbon::parse($item->start_date)->diffInHours(\Carbon\Carbon::now()) / 24) }}
                                    @endif
                                    Day
                                @else
                                    -
                                @endif
                            </td>
                            <td class="col-notes">{!! nl2br(e($item->notes_to_follow_up)) !!}</td>
                            <td class="col-uic">
                                @if($item->uic)
                                    @php
                                        $uics = is_array($item->uic) ? $item->uic : explode(',', $item->uic);
                                    @endphp
                                    <div class="uic-list">
                                        @foreach($uics as $uic)
                                            <span class="uic-badge">{{ trim($uic) }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="col-approval">
                                @if($item->uic)
                                    @php
                                        $uics = is_array($item->uic) ? $item->uic : explode(',', $item->uic);
                                        $approvals = $item->uic_approvals ? json_decode($item->uic_approvals, true) : [];
                                        $isDone = $item->progress === 'Done';
                                    @endphp
                                    <div class="approval-status">
                                        @foreach($uics as $uic)
                                            @php 
                                                $trimmedUic = trim($uic);
                                                $isApproved = isset($approvals[$trimmedUic]) && $approvals[$trimmedUic];
                                                // Jika progress Done, semua UIC dianggap approved
                                                $finalApproved = $isDone ? true : $isApproved;
                                            @endphp
                                            <div class="approval-item">
                                                <span class="uic-name">{{ $trimmedUic }}:</span>
                                                <label class="approval-checkbox">
                                                    <input type="checkbox" 
                                                           data-item-id="{{ $item->id }}" 
                                                           data-uic="{{ $trimmedUic }}"
                                                           {{ $finalApproved ? 'checked' : '' }}
                                                           {{ $isDone ? 'disabled' : '' }}
                                                           onchange="updateApproval(this)">
                                                    <span class="checkmark"></span>
                                                    <span class="approval-text">
                                                        {{ $finalApproved ? 'Approved' : 'Pending' }}
                                                    </span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="col-progress">{{ $item->progress }}</td>
                            @php
                                switch ($item->progress) {
                                    case 'Open':
                                        $complete = 0;
                                        $progressColor = 'bg-red';
                                        break;
                                    case 'Need Discuss':
                                        $complete = 25;
                                        $progressColor = 'bg-orange';
                                        break;
                                    case 'On Progress':
                                        $complete = 75;
                                        $progressColor = 'bg-yellow';
                                        break;
                                    case 'Done':
                                        $complete = 100;
                                        $progressColor = 'bg-green';
                                        break;
                                    default:
                                        $complete = 0;
                                        $progressColor = 'bg-gray';
                                }
                            @endphp
                            <td class="col-complete">
                                <div class="progress-bar">
                                    <div class="progress-fill {{ $progressColor }}" style="width: {{ $complete }}%"></div>
                                    <div class="progress-text">{{ $complete }}%</div>
                                </div>
                            </td>
                            <td class="col-status">
                                <span class="status-badge 
                                    {{ $item->status == 'Eskalasi' ? 'status-done' :
                                       ($item->status == 'Action' ? 'status-action' :
                                       ($item->status == 'Support Needed' ? 'status-in-progress' : 'status-empty')) }}">
                                    {{ $item->status ?: '-' }}
                                </span>
                            </td>
                            <td class="col-respons">{!! nl2br(e($item->response_uic)) !!}</td>
                            <td class="col-action">
                                <div class="btn-group-horizontal">
                                    <button type="button" class="action-btn edit-btn save-scroll"
                                        onclick="populateEditForm({{ json_encode([
                                            'id' => $item->id,
                                            'agenda' => $item->agenda,
                                            'unit_or_telda' => $item->unit_or_telda,
                                            'start_date' => $item->start_date,
                                            'uic' => $item->uic,
                                            'uic_approvals' => $item->uic_approvals,
                                            'progress' => $item->progress,
                                            'notes_to_follow_up' => $item->notes_to_follow_up,
                                            'response_uic' => $item->response_uic
                                        ]) }}); openModal('editSupportModal');">
                                        Edit
                                    </button>
                                    <form action="{{ route('supportneeded.destroy', $item->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete-btn save-scroll"
                                            onclick="return confirm('Are you sure you want to delete this item?')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="14" style="color: #6b7280; font-style: italic; text-align: center;">
                                No data available.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="sn-footer">Powered by <strong>GIAT CORE</strong></div>
    </div>

    @include('supportneeded.snmodal')
    
    @push('scripts')
    <script src="{{ asset('js/tablescript.js') }}"></script>
    <script>
        // Fixed JavaScript untuk Support Needed form
document.addEventListener('DOMContentLoaded', function() {
    // Setup CSRF token globally
    const token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        window.csrfToken = token.getAttribute('content');
    }
    
    // Setup jQuery AJAX if available
    if (typeof $ !== 'undefined') {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': window.csrfToken
            }
        });
    }
    
    // Initialize UIC displays
    updateUicDisplay('add');
    updateUicDisplay('edit');
    
    // Setup form submissions
    setupFormSubmission();
    
    console.log('Support form initialized');
    
    // Setup checkbox event listeners for approval updates
    document.querySelectorAll('input[type="checkbox"][data-uic]').forEach(cb => {
        cb.addEventListener('change', async function () {
            // Skip if checkbox is disabled (Done items)
            if (cb.disabled) return;
            
            await updateApproval(cb);
        });
    });
});

// UIC Multi-select Functions
let selectedUics = {
    add: [],
    edit: []
};

function toggleUicDropdown(formType) {
    const dropdown = document.getElementById(`${formType}_uic_dropdown`);
    if (!dropdown) return;
    
    const isVisible = dropdown.classList.contains('show');
    
    // Close all dropdowns first
    document.querySelectorAll('.uic-dropdown').forEach(dd => {
        dd.classList.remove('show');
    });
    
    // Toggle current dropdown
    if (!isVisible) {
        dropdown.classList.add('show');
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.uic-multiselect')) {
        document.querySelectorAll('.uic-dropdown').forEach(dropdown => {
            dropdown.classList.remove('show');
        });
    }
});

// Handle UIC selection
document.addEventListener('change', function(event) {
    if (event.target.classList.contains('uic-checkbox')) {
        const formType = event.target.id.includes('add_') ? 'add' : 'edit';
        const value = event.target.value;
        
        if (event.target.checked) {
            if (!selectedUics[formType].includes(value)) {
                selectedUics[formType].push(value);
            }
        } else {
            selectedUics[formType] = selectedUics[formType].filter(uic => uic !== value);
        }
        
        updateUicDisplay(formType);
        updateHiddenInput(formType);
    }
});

function updateUicDisplay(formType) {
    const container = document.getElementById(`${formType}_selected_uics`);
    if (!container) return;
    
    if (selectedUics[formType].length === 0) {
        container.innerHTML = '<div class="no-uics-selected">No UIC selected</div>';
    } else {
        const badges = selectedUics[formType].map(uic => 
            `<span class="selected-uic-badge">${uic}</span>`
        ).join('');
        container.innerHTML = badges;
    }
    
    // Update placeholder
    const placeholder = document.querySelector(`#${formType}_uic_multiselect .uic-placeholder`);
    if (placeholder) {
        if (selectedUics[formType].length === 0) {
            placeholder.textContent = 'Select UIC(s)...';
            placeholder.style.display = 'block';
        } else {
            placeholder.style.display = 'none';
        }
    }
}

function updateHiddenInput(formType) {
    const hiddenInput = document.getElementById(`${formType}_uic_hidden`);
    if (hiddenInput) {
        hiddenInput.value = selectedUics[formType].join(',');
    }
}

// Modal Functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
        
        // Reset form if it's add modal
        if (modalId === 'addSupportModal') {
            const form = document.getElementById('addSupportForm');
            if (form) {
                form.reset();
                // Reset UIC selections
                selectedUics.add = [];
                updateUicDisplay('add');
                updateHiddenInput('add');
            }
        }
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = 'auto';
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modals = document.querySelectorAll('.sn-modal');
    modals.forEach(modal => {
        if (event.target === modal) {
            closeModal(modal.id);
        }
    });
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const openModal = document.querySelector('.sn-modal.show');
        if (openModal) {
            closeModal(openModal.id);
        }
    }
});

// Form submission handlers
function setupFormSubmission() {
    const addForm = document.getElementById('addSupportForm');
    if (addForm) {
        addForm.removeEventListener('submit', handleAddFormSubmit);
        addForm.addEventListener('submit', handleAddFormSubmit);
    }

    const editForm = document.getElementById('editSupportForm');
    if (editForm) {
        editForm.removeEventListener('submit', handleEditFormSubmit);
        editForm.addEventListener('submit', handleEditFormSubmit);
    }
}

function handleAddFormSubmit(e) {
    console.log('Add form submitted');
    
    const form = e.target;
    
    // Validasi dasar
    const agenda = form.querySelector('[name="agenda"]');
    const progress = form.querySelector('[name="progress"]');
    
    if (!agenda || !agenda.value.trim()) {
        e.preventDefault();
        alert('Please select an agenda');
        return false;
    }
    
    if (!progress || !progress.value.trim()) {
        e.preventDefault();
        alert('Please select progress');
        return false;
    }
    
    // Pastikan CSRF token ada
    let csrfInput = form.querySelector('input[name="_token"]');
    if (!csrfInput) {
        csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = window.csrfToken || document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfInput);
    }
    
    // Pastikan UIC hidden input ter-update
    updateHiddenInput('add');
    
    // Set form action dan method jika belum ada
    if (!form.action || form.action.includes('#')) {
        form.action = '/supportneeded';
    }
    if (!form.method) {
        form.method = 'POST';
    }
    
    console.log('Form will submit to:', form.action);
    console.log('Form method:', form.method);
    
    return true;
}

function handleEditFormSubmit(e) {
    console.log('Edit form submitted');
    
    const form = e.target;
    
    // Validasi dasar
    const agenda = form.querySelector('[name="agenda"]');
    const progress = form.querySelector('[name="progress"]');
    
    if (!agenda || !agenda.value.trim()) {
        e.preventDefault();
        alert('Please select an agenda');
        return false;
    }
    
    if (!progress || !progress.value.trim()) {
        e.preventDefault();
        alert('Please select progress');
        return false;
    }
    
    // Pastikan CSRF token dan method ada
    let csrfInput = form.querySelector('input[name="_token"]');
    if (!csrfInput) {
        csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = window.csrfToken || document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfInput);
    }
    
    let methodInput = form.querySelector('input[name="_method"]');
    if (!methodInput) {
        methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);
    }
    
    // Pastikan UIC hidden input ter-update
    updateHiddenInput('edit');
    
    console.log('Edit form will submit to:', form.action);
    
    return true;
}

// Populate Edit Form
function populateEditForm(data) {
    // Set basic fields
    const fields = [
        'edit_agenda', 'edit_unit_or_telda', 'edit_start_date', 
        'edit_progress', 'edit_notes_to_follow_up', 'edit_response_uic'
    ];
    
    fields.forEach(fieldId => {
        const element = document.getElementById(fieldId);
        if (element) {
            const fieldName = fieldId.replace('edit_', '');
            element.value = data[fieldName] || '';
        }
    });
    
    // Handle UIC selection
    selectedUics.edit = [];
    
    // Clear all checkboxes first
    document.querySelectorAll('#edit_uic_dropdown input[type="checkbox"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    // Set selected UICs
    if (data.uic) {
        const uics = Array.isArray(data.uic) ? data.uic : data.uic.split(',');
        uics.forEach(uic => {
            const trimmedUic = uic.trim();
            selectedUics.edit.push(trimmedUic);
            
            const checkbox = document.getElementById(`edit_uic_${trimmedUic.replace(/\s+/g, '_')}`);
            if (checkbox) {
                checkbox.checked = true;
            }
        });
    }
    
    updateUicDisplay('edit');
    updateHiddenInput('edit');
    
    // Set form action for edit
    const form = document.getElementById('editSupportForm');
    if (form) {
        form.action = `/supportneeded/${data.id}`;
    }
}

// Function to update approval status - IMPROVED VERSION
async function updateApproval(checkbox) {
    const itemId = checkbox.dataset.itemId;
    const uic = checkbox.dataset.uic;
    const approved = checkbox.checked;
    
    // Find the approval text element for this specific UIC
    const approvalItem = checkbox.closest('.approval-item');
    const approvalText = approvalItem.querySelector('.approval-text');
    
    // Update UI immediately for better user experience
    if (approvalText) {
        approvalText.textContent = approved ? 'Approved' : 'Pending';
    }
    
    try {
        const response = await fetch(`/supportneeded/${itemId}/approval`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken || document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                uic: uic,
                approved: approved
            })
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success) {
            console.log(`Approval status updated for ${uic}: ${approved ? 'Approved' : 'Pending'}`);
            
            // Check if all UICs are approved for auto-progress update
            checkAllApprovals(itemId);
        } else {
            // Revert UI if server request failed
            checkbox.checked = !approved;
            if (approvalText) {
                approvalText.textContent = !approved ? 'Approved' : 'Pending';
            }
            alert('Failed to update approval status: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        
        // Revert UI if request failed
        checkbox.checked = !approved;
        if (approvalText) {
            approvalText.textContent = !approved ? 'Approved' : 'Pending';
        }
        alert('Error updating approval status: ' + error.message);
    }
}

// Function to check if all UICs are approved
function checkAllApprovals(itemId) {
    const row = document.querySelector(`input[data-item-id="${itemId}"]`).closest('tr');
    const checkboxes = row.querySelectorAll('input[type="checkbox"]:not([disabled])');
    const allApproved = Array.from(checkboxes).every(cb => cb.checked);
    
    if (allApproved && checkboxes.length > 0) {
        // Show confirmation dialog for auto-update to Done
        if (confirm('All UICs are approved. Update progress to Done?')) {
            updateProgressToDone(itemId);
        }
    }
}

// Function to update progress to Done
async function updateProgressToDone(itemId) {
    try {
        const response = await fetch(`/supportneeded/${itemId}/progress`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken || document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                progress: 'Done'
            })
        });
        
        if (response.ok) {
            const data = await response.json();
            
            if (data.success) {
                // Update all checkboxes for this item to be checked and disabled
                const row = document.querySelector(`input[data-item-id="${itemId}"]`).closest('tr');
                const checkboxes = row.querySelectorAll('input[type="checkbox"]');
                const approvalTexts = row.querySelectorAll('.approval-text');
                
                // Update UI immediately before reload
                checkboxes.forEach(cb => {
                    cb.checked = true;
                    cb.disabled = true;
                });
                
                approvalTexts.forEach(text => {
                    text.textContent = 'Approved';
                });
                
                // Show success message and reload
                alert('Progress updated to Done successfully!');
                setTimeout(() => {
                    location.reload();
                }, 500);
            } else {
                alert('Failed to update progress: ' + (data.message || 'Unknown error'));
            }
        } else {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
    } catch (error) {
        console.error('Error updating progress:', error);
        alert('Error updating progress: ' + error.message);
    }
}
    </script>
    @endpush
</div>

@endsection