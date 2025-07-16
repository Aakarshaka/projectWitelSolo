// =======================
// Buka Edit Modal + isi data
// =======================
function openEditModal(button) {
    const id = button.getAttribute("data-id");
    const route = button.getAttribute("data-route");
    const form = document.getElementById("editForm");
    form.action = `/${route}/${id}`;

    // Set value ke input & select
    document.getElementById("editEvent").value = button.getAttribute("data-event");
    document.getElementById("editUnit").value = button.getAttribute("data-unit");
    document.getElementById("editStartDate").value = button.getAttribute("data-start");
    document.getElementById("editEndDate").value = button.getAttribute("data-end");
    document.getElementById("editNotes").value = button.getAttribute("data-notes");
    document.getElementById("editUIC").value = button.getAttribute("data-uic");
    document.getElementById("editUnitCollab").value = button.getAttribute("data-unitcollab");
    document.getElementById("editStatus").value = button.getAttribute("data-status");
    document.getElementById("editRespond").value = button.getAttribute("data-respond");

    document.getElementById("editStatus").dispatchEvent(new Event('change'));

    // Set min & max date
    const startDate = document.getElementById("editStartDate");
    const endDate = document.getElementById("editEndDate");
    endDate.min = startDate.value || "";
    startDate.max = endDate.value || "";

    new bootstrap.Modal(document.getElementById("editModal")).show();
}

function openEditModalWarroom(button) {
    const id = button.getAttribute('data-id');
    document.getElementById('editTgl').value = button.getAttribute('data-tgl');
    document.getElementById('editAgenda').value = button.getAttribute('data-agenda');
    document.getElementById('editPeserta').value = button.getAttribute('data-peserta');
    document.getElementById('editPembahasan').value = button.getAttribute('data-pembahasan');
    document.getElementById('editActionPlan').value = button.getAttribute('data-action_plan');
    document.getElementById('editSupportNeeded').value = button.getAttribute('data-support_needed');
    document.getElementById('editInfoKompetitor').value = button.getAttribute('data-info_kompetitor');
    document.getElementById('editJumlahActionPlan').value = button.getAttribute('data-jumlah_action_plan');
    document.getElementById('editUpdateActionPlan').value = button.getAttribute('data-update_action_plan');
    document.getElementById('editStatusActionPlan').value = button.getAttribute('data-status_action_plan');

    // set action form-nya ke route PUT
    document.getElementById('editForm').action = `/warroom/${id}`;

    new bootstrap.Modal(document.getElementById('editModal')).show();
}


// ==========================
// Set % Complete otomatis sesuai status
// ==========================
function checkStatus(selectElement, completeInputId) {
    const completeInput = document.getElementById(completeInputId);
    const valueMap = {
        "Open": 0, "Need Discuss": 25, "Eskalasi": 50,
        "Progress": 75, "Done": 100
    };
    completeInput.value = valueMap[selectElement.value] ?? 0;
    completeInput.readOnly = true;
}

// ============================
// Validasi saat submit Add & Edit form
// ============================
function validateAddForm() {
    const startDate = document.querySelector('#addModal input[name="start_date"]').value;
    const endDate = document.querySelector('#addModal input[name="end_date"]').value;
    if (startDate && endDate && endDate < startDate) {
        alert("End Date tidak boleh sebelum Start Date.");
        return false;
    }
    return true;
}

function validateEditForm() {
    const startDate = document.getElementById("editStartDate").value;
    const endDate = document.getElementById("editEndDate").value;
    if (startDate && endDate && endDate < startDate) {
        alert("End Date tidak boleh sebelum Start Date.");
        return false;
    }
    return true;
}

// ============================
// Sinkronisasi min & max date Add Modal
// ============================
const addStartDate = document.querySelector('#addModal input[name="start_date"]');
const addEndDate = document.querySelector('#addModal input[name="end_date"]');

addStartDate.addEventListener('change', function () {
    addEndDate.min = this.value || "";
});

addEndDate.addEventListener('change', function () {
    addStartDate.max = this.value || "";
});

// ============================
// Sinkronisasi min & max date Edit Modal
// ============================
const editStartDate = document.getElementById("editStartDate");
const editEndDate = document.getElementById("editEndDate");

editStartDate.addEventListener('change', function () {
    editEndDate.min = this.value || "";
});

editEndDate.addEventListener('change', function () {
    editStartDate.max = this.value || "";
});

// =========================
// Reset form Add saat modal ditutup
// =========================
document.getElementById("addModal").addEventListener("hidden.bs.modal", function () {
    this.querySelector("form").reset();
    document.getElementById("addComplete").value = 0;
    document.getElementById("addComplete").readOnly = true;
    document.getElementById("addStatus").value = "";
    addStartDate.min = "";
    addStartDate.max = "";
    addEndDate.min = "";
    addEndDate.max = "";
});

// =========================
// Reset form Edit saat modal ditutup
// =========================
document.getElementById("editModal").addEventListener("hidden.bs.modal", function () {
    this.querySelector("form").reset();
    document.getElementById("editComplete").value = 0;
    document.getElementById("editComplete").readOnly = true;
    document.getElementById("editStatus").value = "";
    editStartDate.min = "";
    editStartDate.max = "";
    editEndDate.min = "";
    editEndDate.max = "";
});
