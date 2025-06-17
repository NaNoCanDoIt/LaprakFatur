<?php
// File ini merupakan template utama form penilaian mahasiswa berbasis web
// Menggunakan CSS dan JS dari Bootstrap CDN
?>
<!-- PUNYA FATUR MAULANA ADNIN -->
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Metadata dasar untuk pengaturan halaman -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Penilaian Mahasiswa</title>
    
    <!-- Mengimpor CSS Bootstrap v5.3 dari CDN untuk tampilan yang responsif dan modern -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Container utama untuk membungkus seluruh isi halaman -->
    <div class="container mt-5">
        <!-- Judul halaman menggunakan komponen Bootstrap alert -->
        <div class="alert text-center fw-bold fs-4 text-white" style="background-color: #0d6efd;">
            Form Penilaian Mahasiswa
        </div>

        <!-- Div untuk menampilkan peringatan validasi form jika ada -->
        <div id="alertContainer"></div>

        <!-- Form input data nilai mahasiswa -->
        <form id="nilaiForm">
            <!-- Input nama mahasiswa -->
            <div class="mb-3">
                <label for="nama" class="form-label">Masukkan Nama</label>
                <input type="text" class="form-control" id="nama" placeholder="Masukkan nama">
            </div>

            <!-- Input NIM mahasiswa -->
            <div class="mb-3">
                <label for="nim" class="form-label">Masukkan NIM</label>
                <input type="text" class="form-control" id="nim" placeholder="Contoh: 202332xxx">
            </div>

            <!-- Input nilai kehadiran -->
            <div class="mb-3">
                <label for="kehadiran" class="form-label">Nilai Kehadiran (10%)</label>
                <input type="number" class="form-control" id="kehadiran" placeholder="Untuk Lulus minimal 70%" min="0" max="100">
            </div>

            <!-- Input nilai tugas -->
            <div class="mb-3">
                <label for="tugas" class="form-label">Nilai Tugas (20%)</label>
                <input type="number" class="form-control" id="tugas" placeholder="0 - 100" min="0" max="100">
            </div>

            <!-- Input nilai UTS -->
            <div class="mb-3">
                <label for="uts" class="form-label">Nilai UTS (30%)</label>
                <input type="number" class="form-control" id="uts" placeholder="0 - 100" min="0" max="100">
            </div>

            <!-- Input nilai UAS -->
            <div class="mb-3">
                <label for="uas" class="form-label">Nilai UAS (40%)</label>
                <input type="number" class="form-control" id="uas" placeholder="0 - 100" min="0" max="100">
            </div>

            <!-- Tombol untuk memproses data nilai -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Proses</button>
            </div>
        </form>

        <!-- Div hasil akan muncul setelah proses form berhasil -->
        <div class="card mt-4 d-none" id="hasil">
            <!-- Bagian header akan berubah warna tergantung status kelulusan -->
            <div class="card-header text-white" id="hasilHeader">Hasil</div>
            <div class="card-body">
                <p><strong>Nama:</strong> <span id="outputNama"></span></p>
                <p><strong>NIM:</strong> <span id="outputNIM"></span></p>
                <p><strong>Nilai Akhir:</strong> <span id="outputNilai"></span></p>
                <p><strong>Grade:</strong> <span id="outputGrade"></span></p>
                <p><strong>Status:</strong> <span id="outputStatus"></span></p>

                <!-- Tombol untuk mereset form -->
                <button class="btn w-100 mt-3" id="selesaiBtn">Selesai</button>
            </div>
        </div>
    </div>

    <!-- Mengimpor JavaScript dari Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script utama untuk proses validasi dan perhitungan nilai -->
    <script>
        const form = document.getElementById("nilaiForm");
        const hasil = document.getElementById("hasil");
        const hasilHeader = document.getElementById("hasilHeader");
        const alertContainer = document.getElementById("alertContainer");

        form.addEventListener("submit", function (e) {
            e.preventDefault();
            alertContainer.innerHTML = "";

            // Ambil data dari form
            const nama = document.getElementById("nama").value.trim();
            const nim = document.getElementById("nim").value.trim();
            const kehadiran = document.getElementById("kehadiran").value;
            const tugas = document.getElementById("tugas").value;
            const uts = document.getElementById("uts").value;
            const uas = document.getElementById("uas").value;

            // Validasi input kosong
            if (!nama) return showAlert("Nama harus diisi!");
            if (!nim) return showAlert("NIM harus diisi!");
            if (!kehadiran) return showAlert("Nilai Kehadiran harus diisi!");
            if (!tugas) return showAlert("Nilai Tugas harus diisi!");
            if (!uts) return showAlert("Nilai UTS harus diisi!");
            if (!uas) return showAlert("Nilai UAS harus diisi!");

            // Konversi ke tipe angka
            const nh = parseFloat(kehadiran);
            const nt = parseFloat(tugas);
            const nu = parseFloat(uts);
            const na = parseFloat(uas);

            // Hitung nilai akhir berdasarkan bobot komponen
            const nilaiAkhir = (nh * 0.1) + (nt * 0.2) + (nu * 0.3) + (na * 0.4);

            // Menentukan grade
            let grade;
            if (nilaiAkhir >= 85) grade = "A";
            else if (nilaiAkhir >= 70) grade = "B";
            else if (nilaiAkhir >= 55) grade = "C";
            else if (nilaiAkhir >= 40) grade = "D";
            else grade = "E";

            // Menentukan status lulus atau tidak
            let status;
            let lulus = nilaiAkhir >= 60 && nh >= 70 && nt >= 40 && nu >= 40 && na >= 40;
            if (nh < 70) status = "TIDAK LULUS (Absensi kurang dari 70%)";
            else status = lulus ? "LULUS" : "TIDAK LULUS";

            // Tampilkan hasil
            document.getElementById("outputNama").textContent = nama;
            document.getElementById("outputNIM").textContent = nim;
            document.getElementById("outputNilai").textContent = nilaiAkhir.toFixed(2);
            document.getElementById("outputGrade").textContent = grade;
            document.getElementById("outputStatus").textContent = status;

            // Ubah warna card dan tombol selesai berdasarkan status
            hasilHeader.className = "card-header text-white bg-" + (status === "LULUS" ? "success" : "danger");
            document.getElementById("selesaiBtn").className = "btn mt-3 w-100 btn-" + (status === "LULUS" ? "success" : "danger");

            hasil.classList.remove("d-none");
        });

        // Fungsi untuk menampilkan alert jika ada kesalahan input
        function showAlert(message) {
            alertContainer.innerHTML = `<div class='alert alert-danger'>${message}</div>`;
        }

        // Tombol selesai akan mereset form dan menyembunyikan hasil
        document.getElementById("selesaiBtn").addEventListener("click", function () {
            form.reset();
            hasil.classList.add("d-none");
        });
    </script>
</body>

</html>
