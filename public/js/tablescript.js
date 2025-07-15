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
