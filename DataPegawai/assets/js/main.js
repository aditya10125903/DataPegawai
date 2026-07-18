/**
 * File: main.js
 * Deskripsi: Logika JavaScript untuk tema robotik futuristik.
 */

document.addEventListener("DOMContentLoaded", function () {
  // --- Logika Jam Digital Futuristik ---
  const clockContainer = document.getElementById("clock-container");
  const clockElement = document.getElementById("clock");

  if (clockContainer && clockElement) {
    let lastHour = -1;
    let shimmerInterval;

    function updateClock() {
      const now = new Date();
      const day = now.toLocaleDateString("id-ID", { weekday: "long" });
      const date = now.toLocaleDateString("id-ID", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
      });
      const time = now
        .toLocaleTimeString("id-ID", {
          hour: "2-digit",
          minute: "2-digit",
          second: "2-digit",
          hour12: false,
        })
        .replace(/\./g, ":");

      clockElement.innerHTML = `${day}, ${date} | ${time}`;

      const seconds = now.getSeconds();
      const currentHour = now.getHours();

      // Animasi 5 detik terakhir sebelum menit berganti
      if (seconds >= 55) {
        clockContainer.classList.add("blinking");
      } else {
        clockContainer.classList.remove("blinking");
      }

      // Fungsi untuk memicu kilauan
      const triggerShimmer = () => {
        if (!clockElement.classList.contains("shimmer")) {
          clockElement.classList.add("shimmer");
          // Hapus kelas setelah animasi selesai (durasi animasi adalah 1.5s)
          setTimeout(() => clockElement.classList.remove("shimmer"), 1500);
        }
      };

      // Animasi pergantian menit (detik ke 59)
      if (seconds === 59) {
        // Bersiap untuk pergantian menit
        let count = 0;
        if (shimmerInterval) clearInterval(shimmerInterval);
        // Jalankan kilauan 3 kali, dimulai dari detik ke-0
        setTimeout(() => {
          shimmerInterval = setInterval(() => {
            if (count < 3) {
              triggerShimmer();
              count++;
            } else {
              clearInterval(shimmerInterval);
            }
          }, 1000); // Interval antar kilauan
        }, 1000); // Mulai 1 detik setelah detik 59 (yaitu di detik 00)
      }

      // Animasi pergantian jam
      if (lastHour !== -1 && lastHour !== currentHour) {
        clockContainer.classList.add("blinking");
        setTimeout(() => clockContainer.classList.remove("blinking"), 10000); // Berkedip selama 10 detik

        // Selama 10 detik pertama, picu juga kilauan teks setiap 2.5 detik
        for (let i = 0; i < 4; i++) {
          setTimeout(triggerShimmer, i * 2500);
        }
      }
      lastHour = currentHour;
    }

    setInterval(updateClock, 1000);
    updateClock(); // Panggil pertama kali
  }

  // --- Logika Tooltip untuk Tombol Aksi ---
  const actionButtons = document.querySelectorAll(".action-cell .btn");
  actionButtons.forEach((btn) => {
    const title = btn.getAttribute("title");
    if (title) {
      // Buat elemen tooltip jika belum ada
      if (!btn.querySelector(".action-tooltip")) {
        const tooltip = document.createElement("span");
        tooltip.className = "action-tooltip";
        tooltip.textContent = title;
        btn.appendChild(tooltip);
      }
      // Hapus atribut title asli agar tidak muncul tooltip default browser
      btn.removeAttribute("title");
    }
  });

  // --- Logika Grup Menu Sidebar ---
  document
    .querySelectorAll(".sidebar .nav-group > .nav-link")
    .forEach((groupLink) => {
      groupLink.addEventListener("click", function (e) {
        e.preventDefault();
        const group = this.parentElement;
        const content = group.querySelector(".nav-group-items");

        // Tutup grup lain yang sedang terbuka
        document
          .querySelectorAll(".sidebar .nav-group.open")
          .forEach((openGroup) => {
            if (openGroup !== group) {
              openGroup.classList.remove("open");
            }
          });

        // Buka/tutup grup yang diklik
        group.classList.toggle("open");
      });
    });
});
