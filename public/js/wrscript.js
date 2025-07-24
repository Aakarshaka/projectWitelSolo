// ✅ PRESERVE SCROLL POSITION
function saveScrollPosition() {
    sessionStorage.setItem('warroomScrollPosition', window.pageYOffset);
}

function restoreScrollPosition() {
    const scrollPos = sessionStorage.getItem('warroomScrollPosition');
    if (scrollPos) {
        setTimeout(() => {
            window.scrollTo(0, parseInt(scrollPos));
            sessionStorage.removeItem('warroomScrollPosition');
        }, 100);
    }
}

// ✅ PRESERVE FILTER IN FORMS
function addFilterParamsToForm(form) {
    const urlParams = new URLSearchParams(window.location.search);
    const filterParams = ['bulan', 'tahun', 'uic', 'search'];
    
    // Hapus input hidden yang sudah ada untuk menghindari duplikat
    form.querySelectorAll('input[type="hidden"]').forEach(input => {
        if (filterParams.includes(input.name)) {
            input.remove();
        }
    });
    
    filterParams.forEach(param => {
        const value = urlParams.get(param);
        if (value) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = param;
            input.value = value;
            form.appendChild(input);
        }
    });
}

// Modifikasi function handleFormSubmit yang existing
function handleFormSubmit(e) {
    e.preventDefault();
    const form = e.target;

    // ✅ PERBAIKAN: Hanya save scroll jika bukan delete form
    if (!form.action.includes('destroy')) {
        saveScrollPosition();
    }
    addFilterParamsToForm(form);

    // Set empty action plan textareas to '0'
    const actionPlanTextareas = form.querySelectorAll('textarea[name^="action_plan_"]');
    actionPlanTextareas.forEach(textarea => {
        if (!textarea.value.trim()) {
            textarea.value = '0';
        }
    });

    // Validate required fields
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.style.borderColor = '#dc3545';
            isValid = false;
        } else {
            field.style.borderColor = '#e6e1e8';
        }
    });

    if (!isValid) {
        alert('Mohon lengkapi semua field yang wajib diisi!');
        return;
    }

    form.submit();
}

// ✅ PERBAIKAN: Pisahkan initialization
function initializeEventListeners() {
    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('wr-modal')) {
            closeModal(event.target.id);
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const openModal = document.querySelector('.wr-modal.show');
            if (openModal) {
                closeModal(openModal.id);
            }
        }
    });

    // ✅ PERBAIKAN: Hanya handle modal forms, bukan semua form
    document.querySelectorAll('.wr-modal form').forEach(form => {
        form.addEventListener('submit', handleFormSubmit);
    });

    // ✅ PERBAIKAN: Handle delete forms secara terpisah
    document.querySelectorAll('form[action*="destroy"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (confirm('Yakin ingin menghapus data ini?')) {
                saveScrollPosition();
                addFilterParamsToForm(this);
                // Form akan submit otomatis karena tidak ada preventDefault
            } else {
                e.preventDefault();
            }
        });
    });

    // Setup textarea auto-resize
    setupTextareaAutoResize();
}

// ✅ PERBAIKAN: Panggil functions dengan benar saat DOM ready
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
    
    // Restore scroll position hanya setelah semua content loaded
    setTimeout(restoreScrollPosition, 200);
});

// ✅ HAPUS baris ini jika ada di bagian bawah:
// document.addEventListener('DOMContentLoaded', initializeEventListeners);