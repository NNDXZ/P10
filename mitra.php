<?php
include 'admin_middleware.php';

// Koneksi ke database
$host = 'localhost';
$dbname = 'db_sdm';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Tambah mitra kerja
if (isset($_POST['add'])) {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $jenis = $_POST['jenis'];
    $kerja_sama = $_POST['kerja_sama'];
    $stmt = $conn->prepare("INSERT INTO mitra_kerja (nama, alamat, jenis, kerja_sama) VALUES (:nama, :alamat, :jenis, :kerja_sama)");
    $stmt->bindParam(':nama', $nama);
    $stmt->bindParam(':alamat', $alamat);
    $stmt->bindParam(':jenis', $jenis);
    $stmt->bindParam(':kerja_sama', $kerja_sama);
    $stmt->execute();
}

// Update mitra kerja
if (isset($_POST['update'])) {
    $no = $_POST['no'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $jenis = $_POST['jenis'];
    $kerja_sama = $_POST['kerja_sama'];
    $stmt = $conn->prepare("UPDATE mitra_kerja SET nama = :nama, alamat = :alamat, jenis = :jenis, kerja_sama = :kerja_sama WHERE no = :no");
    $stmt->bindParam(':no', $no);
    $stmt->bindParam(':nama', $nama);
    $stmt->bindParam(':alamat', $alamat);
    $stmt->bindParam(':jenis', $jenis);
    $stmt->bindParam(':kerja_sama', $kerja_sama);
    $stmt->execute();
}

// Delete mitra kerja
if (isset($_GET['delete'])) {
    $no = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM mitra_kerja WHERE no = :no");
    $stmt->bindParam(':no', $no);
    $stmt->execute();
}

// Ambil semua mitra kerja
$stmt = $conn->prepare("SELECT * FROM mitra_kerja");
$stmt->execute();
$mitra_kerja = $stmt->fetchAll();
?>

<h1>Manage Mitra Kerja</h1>
<form method="POST" action="">
    Nama: <input type="text" name="nama" required><br>
    Alamat: <input type="text" name="alamat" required><br>
    Jenis: <input type="text" name="jenis" required><br>
    Kerja Sama: <input type="text" name="kerja_sama" required><br>
    <button type="submit" name="add">Add Mitra Kerja</button>
</form>

<h2>All Mitra Kerja</h2>
<table border="1">
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Alamat</th>
        <th>Jenis</th>
        <th>Kerja Sama</th>
        <th>Action</th>
    </tr>
    <?php foreach ($mitra_kerja as $mitra) : ?>
    <tr>
        <td><?= $mitra['no'] ?></td>
        <td><?= $mitra['nama'] ?></td>
        <td><?= $mitra['alamat'] ?></td>
        <td><?= $mitra['jenis'] ?></td>
        <td><?= $mitra['kerja_sama'] ?></td>
        <td>
            <form method="POST" action="">
                <input type="hidden" name="no" value="<?= $mitra['no'] ?>">
                Nama: <input type="text" name="nama" value="<?= $mitra['nama'] ?>" required><br>
                Alamat: <input type="text" name="alamat" value="<?= $mitra['alamat'] ?>" required><br>
                Jenis: <input type="text" name="jenis" value="<?= $mitra['jenis'] ?>" required><br>
                Kerja Sama: <input type="text" name="kerja_sama" value="<?= $mitra['kerja_sama'] ?>" required><br>
                <button type="submit" name="update">Update</button>
            </form>
            <a href="?delete=<?= $mitra['no'] ?>">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
