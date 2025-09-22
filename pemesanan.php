<?php
session_start();
require 'koneksi.php';

$is_admin = false;

// Cek apakah pengguna login
if (!isset($_SESSION['id_user'])) {
    echo "Harap login terlebih dahulu.";
    exit;
}

$id_user = $_SESSION['id_user'];

// Jika request berupa POST, berarti pemesanan baru
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $id_mobil = $_POST['id_mobil'] ?? null;
    $nama_driver = $_POST['nama_driver'] ?? '';
    $email = $_POST['email_driver'] ?? '';
    $no_hp = $_POST['telepon_driver'] ?? '';
    $tanggal_sewa = $_POST['tanggal_mulai'] ?? '';
    $tanggal_kembali = $_POST['tanggal_selesai'] ?? '';

    // Validasi input
    if (!$id_mobil || !$nama_driver || !$email || !$no_hp || !$tanggal_sewa || !$tanggal_kembali) {
        echo json_encode(['success' => false, 'message' => 'Semua data harus diisi.']);
        exit;
    }

    // Hitung hari
    $start = new DateTime($tanggal_sewa);
    $end = new DateTime($tanggal_kembali);
    $jumlah_hari = $start->diff($end)->days;

    if ($jumlah_hari <= 0) {
        echo json_encode(['success' => false, 'message' => 'Tanggal kembali harus setelah tanggal sewa.']);
        exit;
    }

    // Ambil tarif mobil
    $stmt_tarif = mysqli_prepare($conn, "SELECT tarif_per_hari FROM mobil WHERE id_mobil = ?");
    mysqli_stmt_bind_param($stmt_tarif, 'i', $id_mobil);
    mysqli_stmt_execute($stmt_tarif);
    $result_tarif = mysqli_stmt_get_result($stmt_tarif);

    if (!$result_tarif || mysqli_num_rows($result_tarif) === 0) {
        echo json_encode(['success' => false, 'message' => 'Mobil tidak ditemukan.']);
        exit;
    }

    $tarif_per_hari = mysqli_fetch_assoc($result_tarif)['tarif_per_hari'];
    $total_harga = $tarif_per_hari * $jumlah_hari;

    // Pastikan user valid
    $stmt_user = mysqli_prepare($conn, "SELECT id_user FROM user WHERE id_user = ?");
    mysqli_stmt_bind_param($stmt_user, 'i', $id_user);
    mysqli_stmt_execute($stmt_user);
    $result_user = mysqli_stmt_get_result($stmt_user);
    if (mysqli_num_rows($result_user) === 0) {
        echo json_encode(['success' => false, 'message' => 'User tidak ditemukan.']);
        exit;
    }

    // Simpan pemesanan
    $status ='selesai';
    $stmt_insert = mysqli_prepare($conn, "INSERT INTO pemesanan (id_user, id_mobil, nama_driver, email, no_hp, tanggal_sewa, tanggal_kembali, status, total_harga)
                                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt_insert, 'iissssssd', $id_user, $id_mobil, $nama_driver, $email, $no_hp, $tanggal_sewa, $tanggal_kembali, $status, $total_harga);

    if (mysqli_stmt_execute($stmt_insert)) {
        echo json_encode(['success' => true, 'message' => 'Pemesanan berhasil.', 'id_pemesanan' => mysqli_insert_id($conn)]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menyimpan pemesanan: ' . mysqli_error($conn)]);
    }

    exit;
}

// Jika admin, tampilkan tabel dan aksi
// Cek role dari database (opsional jika role disimpan di session)
$sql_role = mysqli_query($conn, "SELECT role FROM user WHERE id_user = $id_user");
$data_role = mysqli_fetch_assoc($sql_role);
$is_admin = $data_role && $data_role['role'] === 'admin';

if ($is_admin) {
    // Update status jika ada action
    if (isset($_GET['action']) && isset($_GET['id_pemesanan'])) {
        $id_pemesanan = (int) $_GET['id_pemesanan'];
        $action = $_GET['action'];

        if ($action === 'selesai') $status = 'Selesai';
        elseif ($action === 'cancel') $status = 'Dibatalkan';

        $update_sql = "UPDATE pemesanan SET status = '$status' WHERE id_pemesanan = $id_pemesanan";
        if (mysqli_query($conn, $update_sql)) {
            header("Location: pemesanan.php");
            exit;
        } else {
            echo "Error updating status: " . mysqli_error($conn);
        }
    }

    // Ambil data pemesanan
    $sql = "SELECT p.id_pemesanan, u.username AS nama_user,
            CONCAT(m.merk, ' ', m.tipe) AS nama_mobil,
            p.nama_driver, p.email, p.no_hp,
            p.tanggal_sewa, p.tanggal_kembali, p.status, p.total_harga
            FROM pemesanan p
            JOIN user u ON p.id_user = u.id_user
            JOIN mobil m ON p.id_mobil = m.id_mobil
            ORDER BY p.tanggal_sewa DESC";

    $result = mysqli_query($conn, $sql);
    ?>

    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Data Pemesanan - Admin</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; background: #f9f9f9; }
            h2 { margin-bottom: 20px; }
            table { width: 100%; border-collapse: collapse; background: #fff; }
            th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
            th { background-color: #eee; }
            .btn-container { display: flex; gap: 10px; }
            .btn-selesai, .btn-cancel {
                padding: 5px 15px; margin: 5px; cursor: pointer;
                border: none; border-radius: 5px; text-decoration: none;
            }
            .btn-selesai { background-color: #28a745; color: white; }
            .btn-cancel { background-color: #dc3545; color: white; }
            .btn-selesai:hover { background-color: #218838; }
            .btn-cancel:hover { background-color: #c82333; }
        </style>
    </head>
    <body>
        <h2>Data Pemesanan</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pemesan</th>
                    <th>Mobil</th>
                    <th>Nama Driver</th>
                    <th>Email</th>
                    <th>No HP</th>
                    <th>Tanggal Sewa</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                    <th>Total Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['id_pemesanan'] ?></td>
                        <td><?= htmlspecialchars($row['nama_user']) ?></td>
                        <td><?= htmlspecialchars($row['nama_mobil']) ?></td>
                        <td><?= htmlspecialchars($row['nama_driver']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['no_hp']) ?></td>
                        <td><?= $row['tanggal_sewa'] ?></td>
                        <td><?= $row['tanggal_kembali'] ?></td>
                        <td><?= $row['status'] ?></td>
                        <td>Rp<?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                        <td>
                            <?php if ($row['status'] != 'Selesai' && $row['status'] != 'Dibatalkan'): ?>
                                <div class="btn-container">
                                    <a href="?action=selesai&id_pemesanan=<?= $row['id_pemesanan'] ?>" class="btn-selesai">Selesai</a>
                                    <a href="?action=cancel&id_pemesanan=<?= $row['id_pemesanan'] ?>" class="btn-cancel">Cancel</a>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </body>
    </html>

<?php
} else {
    echo "Halaman ini hanya dapat diakses oleh admin.";
}
?>
