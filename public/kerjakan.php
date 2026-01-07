<?php
require_once '../autoload.php';
require_once '../config/database.php';
// Pastikan file Auth dan Ujian dipanggil manual jika autoload bermasalah di laptop tertentu
require_once '../src/Auth.php';
require_once '../src/Ujian.php';

Auth::checkLogin(); // Pastikan siswa login

$database = new Database();
$db = $database->getConnection();
$ujian = new Ujian($db);
$daftar_soal = $ujian->getDaftarSoal(5); // Ambil 5 soal acak
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ujian Sedang Berlangsung</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f9f9f9; }
        .soal-box { background: white; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        #timer { position: fixed; top: 20px; right: 20px; background: #e74c3c; color: white; padding: 10px 20px; border-radius: 5px; font-weight: bold; z-index: 1000; }
        button { padding: 10px 20px; background: #27ae60; color: white; border: none; font-size: 16px; cursor: pointer; border-radius: 5px; }
        img.soal-img { max-width: 100%; height: auto; max-height: 300px; border: 1px solid #ddd; margin: 10px 0; border-radius: 5px; }
    </style>
</head>
<body>

    <div id="timer">Waktu: <span id="countdown">00:00</span></div>
    
    <h2>Lembar Jawab Komputer (LJK)</h2>

    <form id="formUjian" action="submit_ujian.php" method="POST">
        
        <?php foreach ($daftar_soal as $i => $s): ?>
            <div class="soal-box">
                <span style="background:#eee; padding:3px 8px; font-size:12px; border-radius:3px; color: #555;">
                    Kategori: <b><?= $s['kategori'] ?></b>
                </span>
                
                <p><strong><?= ($i+1) . ". " . $s['pertanyaan']; ?></strong></p>
                
                <?php if (!empty($s['gambar'])): ?>
                    <div>
                        <img src="uploads/<?= $s['gambar'] ?>" class="soal-img">
                    </div>
                <?php endif; ?>

                <div style="margin-top: 10px;">
                    <label><input type="radio" name="jawaban[<?= $s['id_soal'] ?>]" value="A"> <?= $s['pilihan_a'] ?></label><br>
                    <label><input type="radio" name="jawaban[<?= $s['id_soal'] ?>]" value="B"> <?= $s['pilihan_b'] ?></label><br>
                    <label><input type="radio" name="jawaban[<?= $s['id_soal'] ?>]" value="C"> <?= $s['pilihan_c'] ?></label><br>
                    <label><input type="radio" name="jawaban[<?= $s['id_soal'] ?>]" value="D"> <?= $s['pilihan_d'] ?></label><br>
                    <label><input type="radio" name="jawaban[<?= $s['id_soal'] ?>]" value="E"> <?= $s['pilihan_e'] ?></label>
                </div>
            </div>
        <?php endforeach; ?>
        
        <button type="submit">Selesai & Kirim Jawaban</button>
    </form>

    <script>
        let waktuDetik = 120; // 2 Menit
        const display = document.querySelector('#countdown');
        const form = document.querySelector('#formUjian');

        const timer = setInterval(() => {
            let menit = Math.floor(waktuDetik / 60);
            let detik = waktuDetik % 60;
            display.textContent = `${menit}:${detik < 10 ? '0'+detik : detik}`;

            if (--waktuDetik < 0) {
                clearInterval(timer);
                alert("Waktu Habis! Jawaban otomatis dikirim.");
                form.submit();
            }
        }, 1000);
    </script>

</body>
</html>