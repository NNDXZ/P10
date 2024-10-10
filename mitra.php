<?php
#include 'admin_middleware.php';#

// Koneksi ke database
$host = 'localhost';
$dbname = 'db_sdm';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        // Proses penambahan data
        $nama = $_POST['nama'];
        $alamat = $_POST['alamat'];
        $kerja_sama = $_POST['kerja_sama'];
        
        $stmt = $conn->prepare("INSERT INTO mitra_kerja (nama, alamat, kerja_sama) VALUES (:nama, :alamat, :kerja_sama)");
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':alamat', $alamat);
        $stmt->bindParam(':kerja_sama', $kerja_sama);
        $stmt->execute();

        // Redirect ke halaman sukses
        header('Location: mitra.php?message=Data berhasil ditambahkan');
        exit();
    } elseif (isset($_POST['update'])) {
        // Proses update data
        $id = $_POST['id'];
        $nama = $_POST['nama'];
        $alamat = $_POST['alamat'];
        $kerja_sama = $_POST['kerja_sama'];
        
        $stmt = $conn->prepare("UPDATE mitra_kerja SET nama = :nama, alamat = :alamat, kerja_sama = :kerja_sama WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':alamat', $alamat);
        $stmt->bindParam(':kerja_sama', $kerja_sama);
        $stmt->execute();

        // Redirect ke halaman sukses
        header('Location: mitra.php?message=Data berhasil diperbarui');
        exit();
    }
}

// Proses DELETE (bisa diletakkan di sini atau di bagian atas file)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM mitra_kerja WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Redirect setelah menghapus
    header('Location: mitra.php?message=Data berhasil dihapus');
    exit();
}

// Ambil semua asesor
$stmt = $conn->prepare("SELECT * FROM mitra_kerja");
$stmt->execute();
$mitra_kerja = $stmt->fetchAll();
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Data Mitra Kerja</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
    <style>
        .sidebar {
            height: 100vh;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover {
            color: #f8f9fa;
        }
        .content {
            padding: 20px;
        }
        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="text-center mb-4">
                    <img src="logo 3.png" alt="Moch. Shohibul Asyrof" class="profile-image">
                    <h5>SDM TIK</h5>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link active" href="tuk.php">TUK</a></li>
                    <li class="nav-item"><a class="nav-link" href="asesor.php">Asesor</a></li>
                    <li class="nav-item"><a class="nav-link" href="mitra.php">Mitra</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Formulirku</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.html">Keluar</a></li>
                </ul>
            </div>
<!-- Main Content -->
<div class="col-md-9 col-lg-10 content">
    <h2 class="mb-4">Manajemen Data Mitra Kerja</h2>
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addModal">Tambah</button>
    <a href="lihatmitra.php" class="btn btn-info mb-3 ml-2">Lihat Data Mitra Kerja</a>
    
    <div class="table-responsive">
        <table id="mitra_kerjaTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Kerja Sama</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mitra_kerja as $index => $mitra): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($mitra['nama'] ?? '') ?></td>
                    <td><?= htmlspecialchars($mitra['alamat'] ?? '') ?></td>
                    <td><?= htmlspecialchars($mitra['kerja_sama'] ?? '') ?></td>
                    <td>
                        <button class="btn btn-sm btn-info edit-btn" data-id="<?= htmlspecialchars($mitra['id'] ?? '') ?>">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="<?= htmlspecialchars($mitra['id'] ?? '') ?>">Hapus</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
    // Tampilkan pesan jika ada
    if (isset($_GET['message'])) {
        echo '<div class="alert alert-success">' . htmlspecialchars($_GET['message']) . '</div>';
    }
    ?>


<!-- Modal untuk menambah data -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Mitra KerjaBaru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <input type="text" class="form-control" name="alamat" required>
                    </div>
                    <div class="form-group">
                        <label>Kerja Sama</label>
                        <input type="text" class="form-control" name="kerja_sama" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" name="add" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal untuk mengedit data -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Mitra</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" name="nama" id="edit_nama" required>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <input type="text" class="form-control" name="alamat" id="edit_alamat" required>
                    </div>
                    <div class="form-group">
                        <label>Kerja Sama</label>
                        <input type="text" class="form-control" name="kerja_sama" id="edit_kerja_sama" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" name="update" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    
<script>
$(document).ready(function() {
    $('#mitra_kerjaTable').DataTable({
        "language": {
            "lengthMenu": "Tampilkan _MENU_ entri",
            "search": "Cari:",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        }
    });

    // Fungsi untuk mengisi modal edit
    $('.edit-btn').click(function() {
    var id = $(this).data('id');
    $.ajax({
        url: 'get_asesor.php', // Pastikan path ini benar
        method: 'GET',
        data: {id: id},
        dataType: 'json',
        success: function(data) {
            if (data.error) {
                alert(data.error);
            } else {
                $('#edit_id').val(data.id);
                $('#edit_nama').val(data.nama);
                $('#edit_alamat').val(data.alamat);
                $('#edit_kerja_sama').val(data.kerja_sama);
                $('#editModal').modal('show');
            }
        },
        error: function(xhr, status, error) {
            console.error("Error: " + error);
            alert("Terjadi kesalahan saat mengambil data asesor.");
        }
    });
});

    // Fungsi untuk konfirmasi hapus
    $('.delete-btn').click(function() {
        var id = $(this).data('id');
        if (confirm('Apakah Anda yakin ingin menghapus asesor ini?')) {
            window.location.href = '?delete=' + id;
        }
    });
});
</script>
</body>
</html>