const tableWrapper = document.getElementById("tableWrapper");

// Simpan posisi scroll saat tombol edit/delete diklik
document.querySelectorAll(".save-scroll").forEach((button) => {
    button.addEventListener("click", () => {
        sessionStorage.setItem("tableScrollLeft", tableWrapper.scrollLeft);
        sessionStorage.setItem("tableScrollTop", tableWrapper.scrollTop);
    });
});

// Saat halaman dimuat, kembalikan posisi scroll kalau ada
window.addEventListener("load", function () {
    const savedLeft = sessionStorage.getItem("tableScrollLeft");
    const savedTop = sessionStorage.getItem("tableScrollTop");
    if (savedLeft !== null) tableWrapper.scrollLeft = parseInt(savedLeft);
    if (savedTop !== null) tableWrapper.scrollTop = parseInt(savedTop);
    // Hapus setelah dipakai supaya tidak nyangkut antar navigasi
    sessionStorage.removeItem("tableScrollLeft");
    sessionStorage.removeItem("tableScrollTop");
});

// Debug version untuk mengetahui ID yang sebenarnya ada di DOM
function debugCopyRowData(data) {
    console.log('=== DEBUG COPY FUNCTION ===');
    console.log('Data to copy:', data);

    // Tampilkan semua form yang ada
    const forms = document.querySelectorAll('form');
    console.log('Found forms:');
    forms.forEach((form, index) => {
        console.log(`Form ${index}: ID="${form.id}", Class="${form.className}"`);
    });

    // Tampilkan semua input, select, textarea yang ada
    const inputs = document.querySelectorAll('input, select, textarea');
    console.log('Found inputs:');
    inputs.forEach((input, index) => {
        console.log(`Input ${index}: ID="${input.id}", Name="${input.name}", Type="${input.type}"`);
    });

    // Tampilkan semua modal yang ada
    const modals = document.querySelectorAll('[id*="modal"], [class*="modal"]');
    console.log('Found modals:');
    modals.forEach((modal, index) => {
        console.log(`Modal ${index}: ID="${modal.id}", Class="${modal.className}"`);
    });

    // Coba cari field dengan berbagai cara
    console.log('=== SEARCHING FOR FIELDS ===');

    // Cari berdasarkan name attribute
    const agendaByName = document.querySelector('[name="agenda"]');
    console.log('Agenda by name:', agendaByName);

    // Cari berdasarkan ID yang mengandung kata tertentu
    const agendaById = document.querySelector('[id*="agenda"]');
    console.log('Agenda by ID containing "agenda":', agendaById);

    // Tampilkan semua element yang ID atau name-nya mengandung kata kunci
    const keywords = ['agenda', 'unit', 'uic', 'progress', 'notes', 'response'];
    keywords.forEach(keyword => {
        const elements = document.querySelectorAll(`[id*="${keyword}"], [name*="${keyword}"]`);
        console.log(`Elements containing "${keyword}":`, elements);
    });
}