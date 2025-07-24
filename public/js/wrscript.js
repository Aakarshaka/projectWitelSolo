document.addEventListener('DOMContentLoaded', function() {
    // ✅ Clear form ketika halaman load jika tidak ada query parameters
    const urlParams = new URLSearchParams(window.location.search);
    const hasParams = urlParams.toString().length > 0;
    
    if (!hasParams) {
        // Reset semua form elements
        const form = document.getElementById('filterForm');
        if (form) {
            form.reset();
            
            // Clear semua select options ke default
            document.querySelectorAll('select[name]').forEach(select => {
                select.selectedIndex = 0;
            });
            
            // Clear semua input text
            document.querySelectorAll('input[type="text"]').forEach(input => {
                input.value = '';
            });
        }
    }
    
    // ✅ Auto submit ketika filter berubah (opsional)
    document.querySelectorAll('select[name]').forEach(select => {
        select.addEventListener('change', function() {
            // Uncomment baris berikut jika ingin auto-submit saat filter berubah
            // document.getElementById('filterForm').submit();
        });
    });
    
    // ✅ Enter key submit untuk search input
    document.getElementById('search').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('filterForm').submit();
        }
    });
});

// ✅ Function untuk clear semua filter
function clearAllFilters() {
    // Reset form
    document.getElementById('filterForm').reset();
    
    // Clear URL parameters dan redirect
    const baseUrl = window.location.pathname;
    window.location.href = baseUrl;
}

// ✅ Function untuk set filter value programmatically (jika diperlukan)
function setFilterValue(filterName, value) {
    const element = document.getElementById(filterName);
    if (element) {
        element.value = value;
    }
}

// ✅ Function untuk get current filter values
function getCurrentFilters() {
    return {
        bulan: document.getElementById('bulan').value,
        tahun: document.getElementById('tahun').value,
        uic: document.getElementById('uic').value,
        search: document.getElementById('search').value
    };
}