<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Support Needed Modal</title>
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
            overflow: hidden;
            transform: scale(0.7);
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
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
            overflow-y: auto;
            flex-grow: 1;
            max-height: calc(90vh - 160px);
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

        /* Multi-select UIC Styles */
        .uic-multiselect {
            position: relative;
        }

        .uic-select-container {
            border: 1px solid #e6e1e8;
            border-radius: 8px;
            background: white;
            min-height: 48px;
            padding: 8px 12px;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .uic-select-container:focus-within {
            border-color: #4a0e4e;
            box-shadow: 0 0 0 3px rgba(74, 14, 78, 0.1);
        }

        .uic-selected-item {
            background: #4a0e4e;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .uic-remove {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            padding: 0;
            width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .uic-remove:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .uic-placeholder {
            color: #9ca3af;
            font-size: 14px;
            flex: 1;
        }

        .uic-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #e6e1e8;
            border-top: none;
            border-radius: 0 0 8px 8px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1001;
            display: none;
        }

        .uic-dropdown.show {
            display: block;
        }

        .uic-option {
            padding: 12px 16px;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
            transition: background-color 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .uic-option:hover {
            background-color: #f9fafb;
        }

        .uic-option.selected {
            background-color: #4a0e4e;
            color: white;
        }

        .uic-checkbox {
            width: 16px;
            height: 16px;
            margin: 0;
        }

        /* Selected UICs Display */
        .selected-uics-display {
            margin-top: 8px;
            padding: 8px 12px;
            background: #f8fafc;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            min-height: 40px;
        }

        .selected-uics-list {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .selected-uic-badge {
            background: #4a0e4e;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .no-uics-selected {
            color: #64748b;
            font-size: 13px;
            font-style: italic;
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

        .error-message {
            color: #e53e3e;
            font-size: 12px;
            margin-top: 4px;
        }

        .loading {
            opacity: 0.6;
            pointer-events: none;
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
</head>
<body>

<!-- Add Support Modal -->
<div id="addSupportModal" class="sn-modal">
    <div class="sn-modal-content">
        <div class="sn-modal-header">
            <h2 class="sn-modal-title">Add New Data</h2>
            <button class="sn-modal-close" onclick="closeModal('addSupportModal')">&times;</button>
        </div>
        <form id="addSupportForm" method="POST">
            <div class="sn-modal-body">
                <div class="sn-form-grid">
                    <div class="sn-form-group">
                        <label class="sn-form-label">Agenda <span style="color: red;">*</span></label>
                        <select class="sn-form-select" name="agenda" required>
                            <option value="">Select Agenda</option>
                            <option value="1 ON 1 AM">1 ON 1 AM</option>
                            <option value="1 ON 1 TELDA">1 ON 1 TELDA</option>
                            <option value="WAR">WAR</option>
                            <option value="FORUM TIF">FORUM TIF</option>
                            <option value="FORUM TSEL">FORUM TSEL</option>
                            <option value="FORUM GSD">FORUM GSD</option>
                            <option value="REVIEW KPI">REVIEW KPI</option>
                            <option value="OTHERS">OTHERS</option>
                        </select>
                        <div class="error-message" id="add-agenda-error"></div>
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
                        <label class="sn-form-label">UIC</label>
                        <div class="uic-multiselect" id="add_uic_multiselect">
                            <div class="uic-select-container" onclick="toggleUicDropdown('add')">
                                <div class="uic-placeholder">Select UIC(s)...</div>
                            </div>
                            <div class="uic-dropdown" id="add_uic_dropdown">
                                <div class="uic-option" data-value="BS">
                                    <input type="checkbox" class="uic-checkbox" id="add_uic_BS" value="BS">
                                    <label for="add_uic_BS">BS</label>
                                </div>
                                <div class="uic-option" data-value="GS">
                                    <input type="checkbox" class="uic-checkbox" id="add_uic_GS" value="GS">
                                    <label for="add_uic_GS">GS</label>
                                </div>
                                <div class="uic-option" data-value="RLEGS">
                                    <input type="checkbox" class="uic-checkbox" id="add_uic_RLEGS" value="RLEGS">
                                    <label for="add_uic_RLEGS">RLEGS</label>
                                </div>
                                <div class="uic-option" data-value="RSO REGIONAL">
                                    <input type="checkbox" class="uic-checkbox" id="add_uic_RSO_REGIONAL" value="RSO REGIONAL">
                                    <label for="add_uic_RSO_REGIONAL">RSO REGIONAL</label>
                                </div>
                                <div class="uic-option" data-value="RSO WITEL">
                                    <input type="checkbox" class="uic-checkbox" id="add_uic_RSO_WITEL" value="RSO WITEL">
                                    <label for="add_uic_RSO_WITEL">RSO WITEL</label>
                                </div>
                                <div class="uic-option" data-value="ED">
                                    <input type="checkbox" class="uic-checkbox" id="add_uic_ED" value="ED">
                                    <label for="add_uic_ED">ED</label>
                                </div>
                                <div class="uic-option" data-value="TIF">
                                    <input type="checkbox" class="uic-checkbox" id="add_uic_TIF" value="TIF">
                                    <label for="add_uic_TIF">TIF</label>
                                </div>
                                <div class="uic-option" data-value="TSEL">
                                    <input type="checkbox" class="uic-checkbox" id="add_uic_TSEL" value="TSEL">
                                    <label for="add_uic_TSEL">TSEL</label>
                                </div>
                                <div class="uic-option" data-value="GSD">
                                    <input type="checkbox" class="uic-checkbox" id="add_uic_GSD" value="GSD">
                                    <label for="add_uic_GSD">GSD</label>
                                </div>
                                <div class="uic-option" data-value="SSGS">
                                    <input type="checkbox" class="uic-checkbox" id="add_uic_SSGS" value="SSGS">
                                    <label for="add_uic_SSGS">SSGS</label>
                                </div>
                                <div class="uic-option" data-value="PRQ">
                                    <input type="checkbox" class="uic-checkbox" id="add_uic_PRQ" value="PRQ">
                                    <label for="add_uic_PRQ">PRQ</label>
                                </div>
                                <div class="uic-option" data-value="RSMES">
                                    <input type="checkbox" class="uic-checkbox" id="add_uic_RSMES" value="RSMES">
                                    <label for="add_uic_RSMES">RSMES</label>
                                </div>
                                <div class="uic-option" data-value="BPPLP">
                                    <input type="checkbox" class="uic-checkbox" id="add_uic_BPPLP" value="BPPLP">
                                    <label for="add_uic_BPPLP">BPPLP</label>
                                </div>
                                <div class="uic-option" data-value="SSS">
                                    <input type="checkbox" class="uic-checkbox" id="add_uic_SSS" value="SSS">
                                    <label for="add_uic_SSS">SSS</label>
                                </div>
                            </div>
                        </div>
                        <div class="selected-uics-display">
                            <div class="selected-uics-list" id="add_selected_uics">
                                <div class="no-uics-selected">No UIC selected</div>
                            </div>
                        </div>
                        <!-- Hidden input for form submission -->
                        <input type="hidden" name="uic" id="add_uic_hidden">
                    </div>

                    <div class="sn-form-group">
                        <label class="sn-form-label">Progress <span style="color: red;">*</span></label>
                        <select class="sn-form-select" name="progress" required>
                            <option value="">Select Progress</option>
                            <option value="Open">Open</option>
                            <option value="Need Discuss">Need Discuss</option>
                            <option value="On Progress">On Progress</option>
                            <option value="Done">Done</option>
                        </select>
                        <div class="error-message" id="add-progress-error"></div>
                    </div>

                    <div class="sn-form-group full-width">
                        <label class="sn-form-label">Notes to Follow Up</label>
                        <textarea class="sn-form-textarea" name="notes_to_follow_up" rows="4"
                            placeholder="Enter detailed notes for follow up..."></textarea>
                    </div>

                    <div class="sn-form-group full-width">
                        <label class="sn-form-label">Response UIC</label>
                        <textarea class="sn-form-textarea" name="response_uic" rows="4"
                            placeholder="Enter UIC response..."></textarea>
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
            <input type="hidden" name="_method" value="PUT">
            <div class="sn-modal-body">
                <div class="sn-form-grid">
                    <div class="sn-form-group">
                        <label class="sn-form-label">Agenda <span style="color: red;">*</span></label>
                        <select class="sn-form-select" name="agenda" id="edit_agenda" required>
                            <option value="">Select Agenda</option>
                            <option value="1 ON 1 AM">1 ON 1 AM</option>
                            <option value="1 ON 1 TELDA">1 ON 1 TELDA</option>
                            <option value="WAR">WAR</option>
                            <option value="FORUM TIF">FORUM TIF</option>
                            <option value="FORUM TSEL">FORUM TSEL</option>
                            <option value="FORUM GSD">FORUM GSD</option>
                            <option value="REVIEW KPI">REVIEW KPI</option>
                            <option value="OTHERS">OTHERS</option>
                        </select>
                        <div class="error-message" id="edit-agenda-error"></div>
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
                        <input type="date" class="sn-form-input" name="start_date" id="edit_start_date">
                    </div>

                    <div class="sn-form-group">
                        <label class="sn-form-label">UIC</label>
                        <div class="uic-multiselect" id="edit_uic_multiselect">
                            <div class="uic-select-container" onclick="toggleUicDropdown('edit')">
                                <div class="uic-placeholder">Select UIC(s)...</div>
                            </div>
                            <div class="uic-dropdown" id="edit_uic_dropdown">
                                <div class="uic-option" data-value="BS">
                                    <input type="checkbox" class="uic-checkbox" id="edit_uic_BS" value="BS">
                                    <label for="edit_uic_BS">BS</label>
                                </div>
                                <div class="uic-option" data-value="GS">
                                    <input type="checkbox" class="uic-checkbox" id="edit_uic_GS" value="GS">
                                    <label for="edit_uic_GS">GS</label>
                                </div>
                                <div class="uic-option" data-value="RLEGS">
                                    <input type="checkbox" class="uic-checkbox" id="edit_uic_RLEGS" value="RLEGS">
                                    <label for="edit_uic_RLEGS">RLEGS</label>
                                </div>
                                <div class="uic-option" data-value="RSO REGIONAL">
                                    <input type="checkbox" class="uic-checkbox" id="edit_uic_RSO_REGIONAL" value="RSO REGIONAL">
                                    <label for="edit_uic_RSO_REGIONAL">RSO REGIONAL</label>
                                </div>
                                <div class="uic-option" data-value="RSO WITEL">
                                    <input type="checkbox" class="uic-checkbox" id="edit_uic_RSO_WITEL" value="RSO WITEL">
                                    <label for="edit_uic_RSO_WITEL">RSO WITEL</label>
                                </div>
                                <div class="uic-option" data-value="ED">
                                    <input type="checkbox" class="uic-checkbox" id="edit_uic_ED" value="ED">
                                    <label for="edit_uic_ED">ED</label>
                                </div>
                                <div class="uic-option" data-value="TIF">
                                    <input type="checkbox" class="uic-checkbox" id="edit_uic_TIF" value="TIF">
                                    <label for="edit_uic_TIF">TIF</label>
                                </div>
                                <div class="uic-option" data-value="TSEL">
                                    <input type="checkbox" class="uic-checkbox" id="edit_uic_TSEL" value="TSEL">
                                    <label for="edit_uic_TSEL">TSEL</label>
                                </div>
                                <div class="uic-option" data-value="GSD">
                                    <input type="checkbox" class="uic-checkbox" id="edit_uic_GSD" value="GSD">
                                    <label for="edit_uic_GSD">GSD</label>
                                </div>
                                <div class="uic-option" data-value="SSGS">
                                    <input type="checkbox" class="uic-checkbox" id="edit_uic_SSGS" value="SSGS">
                                    <label for="edit_uic_SSGS">SSGS</label>
                                </div>
                                <div class="uic-option" data-value="PRQ">
                                    <input type="checkbox" class="uic-checkbox" id="edit_uic_PRQ" value="PRQ">
                                    <label for="edit_uic_PRQ">PRQ</label>
                                </div>
                                <div class="uic-option" data-value="RSMES">
                                    <input type="checkbox" class="uic-checkbox" id="edit_uic_RSMES" value="RSMES">
                                    <label for="edit_uic_RSMES">RSMES</label>
                                </div>
                                <div class="uic-option" data-value="BPPLP">
                                    <input type="checkbox" class="uic-checkbox" id="edit_uic_BPPLP" value="BPPLP">
                                    <label for="edit_uic_BPPLP">BPPLP</label>
                                </div>
                                <div class="uic-option" data-value="SSS">
                                    <input type="checkbox" class="uic-checkbox" id="edit_uic_SSS" value="SSS">
                                    <label for="edit_uic_SSS">SSS</label>
                                </div>
                            </div>
                        </div>
                        <div class="selected-uics-display">
                            <div class="selected-uics-list" id="edit_selected_uics">
                                <div class="no-uics-selected">No UIC selected</div>
                            </div>
                        </div>
                        <!-- Hidden input for form submission -->
                        <input type="hidden" name="uic" id="edit_uic_hidden">
                    </div>

                    <div class="sn-form-group">
                        <label class="sn-form-label">Progress <span style="color: red;">*</span></label>
                        <select class="sn-form-select" name="progress" id="edit_progress" required>
                            <option value="">Select Progress</option>
                            <option value="Open">Open</option>
                            <option value="Need Discuss">Need Discuss</option>
                            <option value="On Progress">On Progress</option>
                            <option value="Done">Done</option>
                        </select>
                        <div class="error-message" id="edit-progress-error"></div>
                    </div>

                    <div class="sn-form-group full-width">
                        <label class="sn-form-label">Notes to Follow Up</label>
                        <textarea class="sn-form-textarea" name="notes_to_follow_up" id="edit_notes_to_follow_up" rows="4"
                            placeholder="Enter detailed notes for follow up..."></textarea>
                    </div>

                    <div class="sn-form-group full-width">
                        <label class="sn-form-label">Response UIC</label>
                        <textarea class="sn-form-textarea" name="response_uic" id="edit_response_uic" rows="4"
                            placeholder="Enter UIC response..."></textarea>
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