let today = new Date(); // Objek Date untuk hari ini
let currentMonth = today.getMonth(); // Bulan yang sedang ditampilkan
let currentYear = today.getFullYear(); // Tahun yang sedang ditampilkan

const monthNames = [
    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
];

// eventsData sekarang diakses dari variabel global `window.eventsData`
// yang didefinisikan oleh Blade di dashboard.blade.php
const eventsData = window.eventsData || []; // Pastikan selalu array, bahkan jika window.eventsData belum didefinisikan

document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.prev-month').addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        generateCalendar(currentMonth, currentYear);
    });

    document.querySelector('.next-month').addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        generateCalendar(currentMonth, currentYear);
    });

    document.querySelector('.today-btn').addEventListener('click', () => {
        today = new Date(); // Reset today agar highlight tetap akurat
        currentMonth = today.getMonth();
        currentYear = today.getFullYear();
        generateCalendar(currentMonth, currentYear);
    });

    generateCalendar(currentMonth, currentYear);
});

function generateCalendar(month, year) {
    // 0 = Minggu, 1 = Senin, ..., 6 = Sabtu
    const firstDayOfMonth = new Date(year, month, 1).getDay(); // Ini hari pertama bulan ini (indeks 0-6)
    const daysInMonth = new Date(year, month + 1, 0).getDate(); // Jumlah hari dalam bulan ini (cara yang lebih akurat)
    const calendarBody = document.getElementById('calendar-body');
    calendarBody.innerHTML = "";

    document.getElementById('monthYear').innerText = `${monthNames[month]} ${year}`;
    document.getElementById('todayDate').innerText = formatTodayDate();

    let date = 1;
    for (let i = 0; i < 6; i++) { // Maksimal 6 baris untuk kalender
        const row = document.createElement('tr');

        for (let j = 0; j < 7; j++) { // 7 hari dalam seminggu
            const cell = document.createElement('td');
            
            // Menentukan sel kosong sebelum hari pertama bulan
            if (i === 0 && j < firstDayOfMonth) {
                cell.innerHTML = "";
            } else if (date > daysInMonth) {
                // Jika sudah melewati jumlah hari dalam bulan, keluar dari loop
                break;
            } else {
                // Isi tanggal
                cell.innerHTML = date;

                // Highlight hari ini
                const isToday = (date === today.getDate() && month === today.getMonth() && year === today.getFullYear());
                if (isToday) {
                    cell.classList.add('bg-primary', 'text-white', 'fw-bold');
                }

                // Tambahkan event jika ada (hanya jika eventsData terisi untuk admin)
                const currentCellDate = new Date(year, month, date);
                // --- PENTING: Normalisasi tanggal sel kalender ke awal hari (00:00:00) ---
                currentCellDate.setHours(0, 0, 0, 0); 

                if (Array.isArray(eventsData)) { // Pastikan eventsData adalah array
                    eventsData.forEach(event => {
                        const eventDate = new Date(event.date);
                        // --- PENTING: Normalisasi tanggal event dari data ke awal hari (00:00:00) ---
                        eventDate.setHours(0, 0, 0, 0); 

                        // Periksa apakah tanggal event cocok dengan tanggal sel saat ini menggunakan getTime()
                        if (currentCellDate.getTime() === eventDate.getTime())
                        {
                            // Tambahkan indikator event
                            cell.classList.add('has-event');
                            cell.innerHTML += `<br><small class="text-${event.type} event-indicator" title="${event.description}">●</small>`; 
                        }
                    });
                }

                date++;
            }
            row.appendChild(cell);
        }

        calendarBody.appendChild(row);
    }
}

function formatTodayDate() {
    const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
    return today.toLocaleDateString('id-ID', options);
}