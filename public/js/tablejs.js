// Buka Edit Modal + isi data
function openEditModal(button) {
    let id = button.getAttribute("data-id");
    let route = button.getAttribute("data-route"); // ambil base route-nya
    document.getElementById("editForm").action = "/" + route + "/" + id;

    document.getElementById("editEvent").value =
        button.getAttribute("data-event");
    document.getElementById("editUnit").value =
        button.getAttribute("data-unit");
    document.getElementById("editStartDate").value =
        button.getAttribute("data-start");
    document.getElementById("editEndDate").value =
        button.getAttribute("data-end");
    document.getElementById("editNotes").value =
        button.getAttribute("data-notes");
    document.getElementById("editUIC").value = button.getAttribute("data-uic");
    document.getElementById("editUnitCollab").value =
        button.getAttribute("data-unitcollab");
    document.getElementById("editComplete").value =
        button.getAttribute("data-complete");
    document.getElementById("editStatus").value =
        button.getAttribute("data-status");
    document.getElementById("editRespond").value =
        button.getAttribute("data-respond");

    checkComplete(document.getElementById("editComplete"), "editStatus");

    var modal = new bootstrap.Modal(document.getElementById("editModal"));
    modal.show();
}

// Validasi saat complete 100%, status wajib 'Done'
function checkComplete(input, statusSelectorId) {
    const statusSelect = document.getElementById(statusSelectorId);
    const value = parseInt(input.value);

    if (value === 0) {
        statusSelect.value = "";
        for (let i = 0; i < statusSelect.options.length; i++) {
            statusSelect.options[i].disabled = false;
        }
    } else if (value === 100) {
        statusSelect.value = "Done";
        for (let i = 0; i < statusSelect.options.length; i++) {
            if (
                statusSelect.options[i].value !== "Done" &&
                statusSelect.options[i].value !== ""
            ) {
                statusSelect.options[i].disabled = true;
            }
        }
    } else {
        for (let i = 0; i < statusSelect.options.length; i++) {
            statusSelect.options[i].disabled = false;
        }
    }
}

// Validasi form Add Modal saat submit
function validateAddForm() {
    const startDate = document.querySelector(
        '#addModal input[name="start_date"]'
    ).value;
    const endDate = document.querySelector(
        '#addModal input[name="end_date"]'
    ).value;

    let complete = document.querySelector(
        '#addModal input[name="complete"]'
    ).value;
    complete = complete === "" ? 0 : parseInt(complete);

    const status = document.getElementById("addStatus").value;

    if (endDate && startDate && endDate < startDate) {
        alert("End Date tidak boleh sebelum Start Date.");
        return false;
    }

    if (complete === 100 && status !== "Done") {
        alert("Jika progress 100%, status wajib 'Done'.");
        return false;
    }

    if (complete === 0 && status !== "") {
        alert("Jika progress 0%, status wajib kosong.");
        return false;
    }

    return true;
}

// Validasi form Edit Modal saat submit
function validateEditForm() {
    const startDate = document.getElementById("editStartDate").value;
    const endDate = document.getElementById("editEndDate").value;

    let complete = document.getElementById("editComplete").value;
    complete = complete === "" ? 0 : parseInt(complete);

    const status = document.getElementById("editStatus").value;

    if (endDate && startDate && endDate < startDate) {
        alert("End Date tidak boleh sebelum Start Date.");
        return false;
    }

    if (complete === 100 && status !== "Done") {
        alert("Jika progress 100%, status wajib 'Done'.");
        return false;
    }

    if (complete === 0 && status !== "") {
        alert("Jika progress 0%, status wajib kosong.");
        return false;
    }

    return true;
}

function checkStatus(selectElement, completeInputId) {
    const completeInput = document.getElementById(completeInputId);
    if (selectElement.value === "Done") {
        completeInput.value = 100;
        completeInput.readOnly = true;
    } else {
        completeInput.readOnly = false;
    }
}

function setZeroIfEmpty(input) {
    if (input.value === "") {
        input.value = 0;
    }
}
