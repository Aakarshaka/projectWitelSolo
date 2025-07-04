// Buka Edit Modal + isi data
function openEditModal(button) {
    let id = button.getAttribute("data-id");
    let route = button.getAttribute("data-route");
    document.getElementById("editForm").action = "/" + route + "/" + id;

    document.getElementById("editEvent").value = button.getAttribute("data-event");
    document.getElementById("editUnit").value = button.getAttribute("data-unit");
    document.getElementById("editStartDate").value = button.getAttribute("data-start");
    document.getElementById("editEndDate").value = button.getAttribute("data-end");
    document.getElementById("editNotes").value = button.getAttribute("data-notes");
    document.getElementById("editUIC").value = button.getAttribute("data-uic");
    document.getElementById("editUnitCollab").value = button.getAttribute("data-unitcollab");
    document.getElementById("editComplete").value = button.getAttribute("data-complete");
    document.getElementById("editStatus").value = button.getAttribute("data-status");
    document.getElementById("editRespond").value = button.getAttribute("data-respond");

    checkComplete(document.getElementById("editComplete"), "editStatus");

    var modal = new bootstrap.Modal(document.getElementById("editModal"));
    modal.show();
}

function checkComplete(input, statusSelectorId) {
    const statusSelect = document.getElementById(statusSelectorId);
    const value = parseInt(input.value);

    // Reset enable semua dulu
    for (let i = 0; i < statusSelect.options.length; i++) {
        statusSelect.options[i].disabled = false;
    }

    if (value === 0) {
        // 0 → status hanya boleh kosong atau Open
        if (statusSelect.value !== "" && statusSelect.value !== "Open") {
            statusSelect.value = "Open";
        }
        for (let i = 0; i < statusSelect.options.length; i++) {
            const opt = statusSelect.options[i];
            if (opt.value !== "" && opt.value !== "Open") {
                opt.disabled = true;
            }
        }
    } else if (value === 100) {
        // 100 → Done
        statusSelect.value = "Done";
        for (let i = 0; i < statusSelect.options.length; i++) {
            const opt = statusSelect.options[i];
            opt.disabled = (opt.value !== "Done" && opt.value !== "");
        }
    } else {
        // Rekomendasi status sesuai complete %
        if (value === 25) {
            if (statusSelect.value === "" || statusSelect.value === "Open") {
                statusSelect.value = "Eskalasi";
            }
        } else if (value === 50) {
            if (statusSelect.value === "" || statusSelect.value === "Open" || statusSelect.value === "Eskalasi" || statusSelect.value === "Need Discuss") {
                statusSelect.value = "Progress";
            }
        } else if (value > 0 && value < 25) {
            statusSelect.value = "Open";
        }
        // Selain itu biarin user bebas pilih
    }
}

// Saat user ganti status → rekomendasi nilai complete defaultnya
function checkStatus(selectElement, completeInputId) {
    const completeInput = document.getElementById(completeInputId);
    if (selectElement.value === "Done") {
        completeInput.value = 100;
        completeInput.readOnly = true;
    } else if (selectElement.value === "Open" || selectElement.value === "") {
        completeInput.value = 0;
        completeInput.readOnly = false;
    } else if (selectElement.value === "Eskalasi" || selectElement.value === "Need Discuss") {
        if (completeInput.value == 0 || completeInput.value == 100) {
            completeInput.value = 25;
        }
        completeInput.readOnly = false;
    } else if (selectElement.value === "Progress") {
        if (completeInput.value == 0 || completeInput.value == 100) {
            completeInput.value = 50;
        }
        completeInput.readOnly = false;
    } else {
        completeInput.readOnly = false;
    }
}

// Validasi saat submit ADD form
function validateAddForm() {
    const startDate = document.querySelector('#addModal input[name="start_date"]').value;
    const endDate = document.querySelector('#addModal input[name="end_date"]').value;
    const complete = parseInt(document.querySelector('#addModal input[name="complete"]').value || 0);
    const status = document.getElementById("addStatus").value;

    if (endDate && startDate && endDate < startDate) {
        alert("End Date tidak boleh sebelum Start Date.");
        return false;
    }

    if (complete === 100 && status !== "Done") {
        alert("Jika progress 100%, status wajib 'Done'.");
        return false;
    }

    if (complete === 0 && status !== "" && status !== "Open") {
        alert("Jika progress 0%, status wajib kosong atau 'Open'.");
        return false;
    }

    return true;
}

// Validasi saat submit EDIT form
function validateEditForm() {
    const startDate = document.getElementById("editStartDate").value;
    const endDate = document.getElementById("editEndDate").value;
    const complete = parseInt(document.getElementById("editComplete").value || 0);
    const status = document.getElementById("editStatus").value;

    if (endDate && startDate && endDate < startDate) {
        alert("End Date tidak boleh sebelum Start Date.");
        return false;
    }

    if (complete === 100 && status !== "Done") {
        alert("Jika progress 100%, status wajib 'Done'.");
        return false;
    }

    if (complete === 0 && status !== "" && status !== "Open") {
        alert("Jika progress 0%, status wajib kosong atau 'Open'.");
        return false;
    }

    return true;
}

// Jika kosong → isi 0
function setZeroIfEmpty(input) {
    if (input.value === "") {
        input.value = 0;
    }
}

// Reset form Add saat modal ditutup
document.getElementById("addModal").addEventListener("hidden.bs.modal", function () {
    this.querySelector("form").reset();
    // Tambahan: reset readonly & enable status options kalau ada
    const addStatus = document.getElementById("addStatus");
    addStatus.readOnly = false;
    for (let i = 0; i < addStatus.options.length; i++) {
        addStatus.options[i].disabled = false;
    }
});

// Reset form Edit saat modal ditutup
document.getElementById("editModal").addEventListener("hidden.bs.modal", function () {
    this.querySelector("form").reset();
    const editStatus = document.getElementById("editStatus");
    editStatus.readOnly = false;
    for (let i = 0; i < editStatus.options.length; i++) {
        editStatus.options[i].disabled = false;
    }
});

document.getElementById("addModal").addEventListener("hidden.bs.modal", function () {
    this.querySelector("form").reset();
    document.getElementById("addComplete").value = 0;
    document.getElementById("addStatus").value = "";
    const addStatus = document.getElementById("addStatus");
    for (let i = 0; i < addStatus.options.length; i++) {
        addStatus.options[i].disabled = false;
    }
});
