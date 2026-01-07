<?php
require_once '../autoload.php';
require_once '../config/database.php';
require_once '../src/Auth.php';      // Panggil manual
require_once '../src/Dashboard.php'; // Panggil manual

Auth::checkLogin();

$database = new Database();
$db = $database->getConnection();

// Inisialisasi Dashboard
$dash = new Dashboard($db);

// Ambil data riwayat
$riwayat = $dash->getRiwayatNilai($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body { font-family: sans-serif; padding: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #4CAF50; color: white; }
        .btn-ujian { background: #2980b9; color: white; padding: 10px; text-decoration: none; border-radius: 5px; margin-left: 5px; }
        .logout { background: #e74c3c; color: white; padding: 10px; text-decoration: none; border-radius: 5px; margin-left: 5px;}
    </style>
</head>
<body>
    <h1>Halo, <?= $_SESSION['nama'] ?>!</h1>
    <p>Status: <strong><?= ucfirst($_SESSION['role']) ?></strong></p>
    
    <div style="margin-bottom: 20px;">
        
        <?php if ($_SESSION['role'] == 'admin'): ?>
            <a href="admin_soal.php" style="background: orange; color: black; padding: 10px; text-decoration: none; border-radius: 5px;">🔧 Kelola Soal (Admin)</a>
        <?php endif; ?>

        <a href="kerjakan.php" class="btn-ujian">Mulai Ujian Baru</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>
    <h3>Riwayat Nilai Anda</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Benar</th>
                <th>Skor</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($riwayat)): ?>
                <tr><td colspan="4">Belum ada data ujian.</td></tr>
            <?php else: ?>
                <?php foreach ($riwayat as $i => $r): ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><?= $r['tanggal_ujian'] ?></td>
                    <td><?= $r['jumlah_benar'] ?></td>
                    <td><strong><?= number_format($r['skor'], 0) ?></strong></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>