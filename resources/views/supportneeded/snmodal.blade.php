<!-- Modal CSS -->
<style>
    /* Modal Styles */
    .sn-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(3px);
        opacity: 0;
        transition: opacity 0.3s ease;

    }

    .sn-modal.show {
        display: block;
        opacity: 1;
    }

    .sn-modal-content {
        background-color: white;
        margin: 3% auto;
        padding: 0;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        width: 90%;
        max-width: 700px;
        max-height: 90vh;
        overflow-y: auto;
        transform: scale(0.7);
        transition: transform 0.3s ease;
    }

    .sn-modal.show .sn-modal-content {
        transform: scale(1);
    }

    .sn-modal-header {
        background: linear-gradient(135deg, #8b1538 0%, #4a0e4e 100%);
        color: white;
        padding: 20px 30px;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .sn-modal-title {
        font-size: 20px;
        font-weight: 600;
        margin: 0;
    }

    .sn-modal-close {
        background: none;
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background-color 0.3s ease;
    }

    .sn-modal-close:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }

    .sn-modal-body {
        padding: 30px;
    }

    .sn-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .sn-form-group {
        display: flex;
        flex-direction: column;
    }

    .sn-form-group.full-width {
        grid-column: span 2;
    }

    .sn-form-label {
        font-size: 14px;
        font-weight: 500;
        color: #2a1b2d;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .sn-form-input,
    .sn-form-select,
    .sn-form-textarea {
        padding: 12px 16px;
        border: 1px solid #e6e1e8;
        border-radius: 8px;
        font-size: 14px;
        color: #2a1b2d;
        background: white;
        transition: all 0.3s ease;
        font-family: 'Poppins', sans-serif;
    }

    .sn-form-input:focus,
    .sn-form-select:focus,
    .sn-form-textarea:focus {
        outline: none;
        border-color: #4a0e4e;
        box-shadow: 0 0 0 3px rgba(74, 14, 78, 0.1);
    }

    .sn-form-textarea {
        resize: vertical;
        min-height: 100px;
        font-family: 'Poppins', sans-serif;
    }

    .sn-modal-footer {
        padding: 20px 30px;
        border-top: 1px solid #e6e1e8;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }

    .sn-btn {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: 'Poppins', sans-serif;
    }

    .sn-btn-secondary {
        background: #6b7280;
        color: white;
    }

    .sn-btn-secondary:hover {
        background: #4b5563;
    }

    .sn-btn-primary {
        background: #4a0e4e;
        color: white;
    }

    .sn-btn-primary:hover {
        background: #3a0b3e;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sn-modal-content {
            width: 95%;
            margin: 5% auto;
        }

        .sn-modal-header,
        .sn-modal-body,
        .sn-modal-footer {
            padding: 20px;
        }

        .sn-form-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .sn-form-group.full-width {
            grid-column: span 1;
        }

        .sn-modal-footer {
            flex-direction: column;
        }

        .sn-btn {
            width: 100%;
        }
    }
</style>

<!-- Add Support Modal -->
<div id="addSupportModal" class="sn-modal">
    <div class="sn-modal-content">
        <div class="sn-modal-header">
            <h2 class="sn-modal-title">Add New Data</h2>
            <button class="sn-modal-close" onclick="closeModal('addSupportModal')">&times;</button>
        </div>
        <form id="addSupportForm" action="{{ route('supportneeded.store') }}" method="POST">
            @csrf
            <div class="sn-modal-body">
                <div class="sn-form-grid">
                    <div class="sn-form-group">
                        <label class="sn-form-label">Agenda</label>
                        <select class="sn-form-select" name="agenda" required>
                            <option value="">Select Agenda</option>
                            <option value="1 ON 1 UIC">1 ON 1 UIC</option>
                            <option value="1 ON 1 WITEL">1 ON 1 WITEL</option>
                            <option value="EVP DIRECTION">EVP DIRECTION</option>
                            <option value="WBR IT FEB">WBR IT FEB</option>
                            <option value="STRATEGIC MEETING">STRATEGIC MEETING</option>
                        </select>
                    </div>

                    <div class="sn-form-group">
                        <label class="sn-form-label">Unit/Telda</label>
                        <select class="sn-form-select" name="unit_or_telda">
                            <option value="">Select Unit/Telda</option>
                            <option value="TELDA BLORA">TELDA BLORA</option>
                            <option value="TELDA BOYOLALI">TELDA BOYOLALI</option>
                            <option value="TELDA JEPARA">TELDA JEPARA</option>
                            <option value="TELDA KLATEN">TELDA KLATEN</option>
                            <option value="TELDA KUDUS">TELDA KUDUS</option>
                            <option value="MEA SOLO">MEA SOLO</option>
                            <option value="TELDA PATI">TELDA PATI</option>
                            <option value="TELDA PURWODADI">TELDA PURWODADI</option>
                            <option value="TELDA REMBANG">TELDA REMBANG</option>
                            <option value="TELDA SRAGEN">TELDA SRAGEN</option>
                            <option value="TELDA WONOGIRI">TELDA WONOGIRI</option>
                            <option value="BS">BS</option>
                            <option value="GS">GS</option>
                            <option value="PRQ">PRQ</option>
                            <option value="SSGS">SSGS</option>
                            <option value="RSO WITEL">RSO WITEL</option>
                        </select>
                    </div>

                    <div class="sn-form-group">
                        <label class="sn-form-label">Start Date</label>
                        <input type="date" class="sn-form-input" name="start_date" id="add_start_date">
                    </div>

                    <div class="sn-form-group">
                        <label class="sn-form-label">End Date</label>
                        <input type="date" class="sn-form-input" name="end_date" id="add_end_date">
                    </div>

                    <div class="sn-form-group">
                        <label class="sn-form-label">UIC</label>
                        <select class="sn-form-select" name="uic">
                            <option value="">Select UIC</option>
                            <option value="BS">BS</option>
                            <option value="GS">GS</option>
                            <option value="RLEGS">RLEGS</option>
                            <option value="RSO REGIONAL">RSO REGIONAL</option>
                            <option value="RSO WITEL">RSO WITEL</option>
                            <option value="ED">ED</option>
                            <option value="TIF">TIF</option>
                            <option value="TSEL">TSEL</option>
                            <option value="GSD">GSD</option>
                            <option value="SSGS">SSGS</option>
                            <option value="PRQ">PRQ</option>
                            <option value="RSMES">RSMES</option>
                            <option value="BPPLP">BPPLP</option>
                            <option value="SSS">SSS</option>
                        </select>
                    </div>

                    <div class="sn-form-group">
                        <label class="sn-form-label">Progress</label required>
                        <select class="sn-form-select" name="progress">
                            <option value="Open">Open</option>
                            <option value="Need Discuss">Need Discuss</option>
                            <option value="Progress">Progress</option>
                            <option value="Done">Done</option>
                        </select>
                    </div>

                    <div class="sn-form-group full-width">
                        <label class="sn-form-label">Notes to Follow Up</label>
                        <textarea class="sn-form-textarea" name="notes_to_follow_up" rows="4" placeholder="Enter detailed notes for follow up..."></textarea>
                    </div>

                    <div class="sn-form-group full-width">
                        <label class="sn-form-label">Response UIC</label>
                        <textarea class="sn-form-textarea" name="response_uic" rows="4" placeholder="Enter UIC response..."></textarea>
                    </div>
                </div>
            </div>
            <div class="sn-modal-footer">
                <button type="button" class="sn-btn sn-btn-secondary" onclick="closeModal('addSupportModal')">Cancel</button>
                <button type="submit" class="sn-btn sn-btn-primary">Add Support</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Support Modal -->
<div id="editSupportModal" class="sn-modal">
    <div class="sn-modal-content">
        <div class="sn-modal-header">
            <h2 class="sn-modal-title">Edit Support</h2>
            <button class="sn-modal-close" onclick="closeModal('editSupportModal')">&times;</button>
        </div>
        <form id="editSupportForm" method="POST">
            @csrf
            @method('PUT')
            <div class="sn-modal-body">
                <div class="sn-form-grid">
                    <div class="sn-form-group">
                        <label class="sn-form-label">Agenda</label>
                        <select class="sn-form-select" name="agenda" id="edit_agenda" required>
                            <option value="">Select Agenda</option>
                            <option value="1 ON 1 UIC">1 ON 1 UIC</option>
                            <option value="1 ON 1 WITEL">1 ON 1 WITEL</option>
                            <option value="EVP DIRECTION">EVP DIRECTION</option>
                            <option value="WBR IT FEB">WBR IT FEB</option>
                            <option value="STRATEGIC MEETING">STRATEGIC MEETING</option>
                        </select>
                    </div>

                    <div class="sn-form-group">
                        <label class="sn-form-label">Unit/Telda</label>
                        <select class="sn-form-select" name="unit_or_telda" id="edit_unit_or_telda">
                            <option value="">Select Unit/Telda</option>
                            <option value="TELDA BLORA">TELDA BLORA</option>
                            <option value="TELDA BOYOLALI">TELDA BOYOLALI</option>
                            <option value="TELDA JEPARA">TELDA JEPARA</option>
                            <option value="TELDA KLATEN">TELDA KLATEN</option>
                            <option value="TELDA KUDUS">TELDA KUDUS</option>
                            <option value="TELDA MEA SOLO">MEA SOLO</option>
                            <option value="TELDA PATI">TELDA PATI</option>
                            <option value="TELDA PURWODADI">TELDA PURWODADI</option>
                            <option value="TELDA REMBANG">TELDA REMBANG</option>
                            <option value="TELDA SRAGEN">TELDA SRAGEN</option>
                            <option value="TELDA WONOGIRI">TELDA WONOGIRI</option>
                            <option value="BS">BS</option>
                            <option value="GS">GS</option>
                            <option value="PRQ">PRQ</option>
                            <option value="SSGS">SSGS</option>
                            <option value="RSO WITEL">RSO WITEL</option>
                        </select>
                    </div>

                    <div class="sn-form-group">
                        <label class="sn-form-label">Start Date</label>
                        <input type="date" class="sn-form-input" name="start_date" id="edit_start_date">
                    </div>

                    <div class="sn-form-group">
                        <label class="sn-form-label">End Date</label>
                        <input type="date" class="sn-form-input" name="end_date" id="edit_end_date">
                    </div>

                    <div class="sn-form-group">
                        <label class="sn-form-label">UIC</label>
                        <select class="sn-form-select" name="uic" id="edit_uic">
                            <option value="">Select UIC</option>
                            <option value="BS">BS</option>
                            <option value="GS">GS</option>
                            <option value="RLEGS">RLEGS</option>
                            <option value="RSO REGIONAL">RSO REGIONAL</option>
                            <option value="RSO WITEL">RSO WITEL</option>
                            <option value="ED">ED</option>
                            <option value="TIF">TIF</option>
                            <option value="TSEL">TSEL</option>
                            <option value="GSD">GSD</option>
                            <option value="SSGS">SSGS</option>
                            <option value="PRQ">PRQ</option>
                            <option value="RSMES">RSMES</option>
                            <option value="BPPLP">BPPLP</option>
                            <option value="SSS">SSS</option>
                        </select>
                    </div>

                    <div class="sn-form-group">
                        <label class="sn-form-label">Progress</label>
                        <select class="sn-form-select" name="progress" id="edit_progress" required>
                            <option value disabled="">Select Progress</option>
                            <option value="Open">Open</option>
                            <option value="Need Discuss">Need Discuss</option>
                            <option value="Progress">Progress</option>
                            <option value="Done">Done</option>
                        </select>
                    </div>

                    <div class="sn-form-group full-width">
                        <label class="sn-form-label">Notes to Follow Up</label>
                        <textarea class="sn-form-textarea" name="notes_to_follow_up" id="edit_notes_to_follow_up" rows="4" placeholder="Enter detailed notes for follow up..."></textarea>
                    </div>

                    <div class="sn-form-group full-width">
                        <label class="sn-form-label">Response UIC</label>
                        <textarea class="sn-form-textarea" name="response_uic" id="edit_response_uic" rows="4" placeholder="Enter UIC response..."></textarea>
                    </div>
                </div>
            </div>
            <div class="sn-modal-footer">
                <button type="button" class="sn-btn sn-btn-secondary" onclick="closeModal('editSupportModal')">Cancel</button>
                <button type="submit" class="sn-btn sn-btn-primary">Update Support</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Modal Functions
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('sn-modal')) {
            closeModal(event.target.id);
        }
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

    // Function to populate edit form (call this when edit button is clicked)
    function populateEditForm(data) {
        document.getElementById('edit_agenda').value = data.agenda || '';
        document.getElementById('edit_unit_or_telda').value = data.unit_or_telda || '';
        document.getElementById('edit_start_date').value = data.start_date || '';
        document.getElementById('edit_end_date').value = data.end_date || '';
        document.getElementById('edit_uic').value = data.uic || '';
        document.getElementById('edit_progress').value = data.progress || '';
        document.getElementById('edit_notes_to_follow_up').value = data.notes_to_follow_up || '';
        document.getElementById('edit_response_uic').value = data.response_uic || '';

        document.getElementById('editSupportForm').action = '/supportneeded/' + data.id;

        // ✅ Set min di sini juga supaya langsung aktif saat modal dibuka
        const editStartDateInput = document.getElementById('edit_start_date');
        const editEndDateInput = document.getElementById('edit_end_date');

        if (editStartDateInput && editEndDateInput) {
            if (editStartDateInput.value) {
                editEndDateInput.min = editStartDateInput.value;
                if (editEndDateInput.value && editEndDateInput.value < editStartDateInput.value) {
                    editEndDateInput.value = '';
                }
            } else {
                editEndDateInput.removeAttribute('min');
            }
        }
    }

    // Update the Add Support button to use the new modal
    document.addEventListener('DOMContentLoaded', function() {
        // Validasi Add Support Modal
        const startDateInput = document.getElementById('add_start_date');
        const endDateInput = document.getElementById('add_end_date');

        if (startDateInput && endDateInput) {
            startDateInput.addEventListener('change', function() {
                if (startDateInput.value) {
                    endDateInput.min = startDateInput.value;
                    if (endDateInput.value && endDateInput.value < startDateInput.value) {
                        endDateInput.value = '';
                    }
                } else {
                    endDateInput.removeAttribute('min');
                }
            });
        }

        // Validasi Edit Support Modal
        const editStartDateInput = document.getElementById('edit_start_date');
        const editEndDateInput = document.getElementById('edit_end_date');

        if (editStartDateInput && editEndDateInput) {
            editStartDateInput.addEventListener('change', function() {
                if (editStartDateInput.value) {
                    editEndDateInput.min = editStartDateInput.value;
                    if (editEndDateInput.value && editEndDateInput.value < editStartDateInput.value) {
                        editEndDateInput.value = '';
                    }
                } else {
                    editEndDateInput.removeAttribute('min');
                }
            });
        }
    });
</script>