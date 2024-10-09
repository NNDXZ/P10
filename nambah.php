<?php
include "koneksi.php";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>
<!-- SweetAlert2 -->
<div class="info-data" data-infodata="<?php if(isset($_SESSION['info'])){ 
  echo $_SESSION['info']; 
  } 
  unset($_SESSION['info']); ?>">
</div>

<div class="container-fluid pt-3 pb-5 backGambar">
  <div class="row">
    <div class="col-xl-12">
      <h3 class="text-center text-uppercase text-dark">Rekapitulasi Data Pengguna</h3>
      <hr class="hr">
    </div>
  </div>

  <div class="row">
    <div class="col-xl-10 table-responsive">
      <button type="button" class="btn btn-primary btn-sm px-2 my-3" data-toggle="modal" data-target="#staticBackdrop" title="tambah data">
        <i class="fas fa-plus"></i>&nbsp;Tambah&nbsp;&nbsp;
      </button>

      <table class="table table-bordered table-hover" id="login">
        <thead>
          <tr class="text-center">
            <th width="5%">No.</th>
            <th>Nama Lengkap</th>
            <th>Username</th>
            <th>Institusi</th>
            <th>Email</th>
            <th>Level</th>
            <th>Aksi</th>
          </tr>
        </thead>

        <tbody>
  <?php
  $no = 1;
  $sql = "SELECT * FROM tbl_users a INNER JOIN level b ON a.id_level=b.id_level";
  $query = $conn->query($sql); // Menggunakan $conn dari koneksi.php
  while ($data = $query->fetch(PDO::FETCH_ASSOC)) {
    $id = $data['id'];
  ?>
    <tr>
      <td align="center" width="3%"><?= $no++; ?>.</td>
      <td><?= $data['fullname']; ?></td>
      <td><?= $data['username']; ?></td>
      <td><?= $data['institution']; ?></td>
      <td><?= $data['email']; ?></td>
      <td><?= $data['level']; ?></td>
      <td align="center" width="15%">
        <?php
        if($data['username']!="admin"){?>
          <a href="login-edit.php?id=<?= $data['id']; ?>" class="badge badge-primary p-2" title="Edit"><i class="fas fa-edit"></i></a> 
          | 
          <a href="login-delete.php?id=<?= $data['id']; ?>" class="badge badge-danger p-2 delete-data" title='Delete'><i class="fas fa-trash"></i></a>
          <?php
        }?>
      </td>
    </tr>
  <?php
  } ?>
</tbody>
      </table>
    </div>
  </div>
</div>



<!-- Modal Tambah-->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">
					Input Pengguna Baru
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<form action="login-simpan.php" method="post">
					<div class="input-group mb-1">
						<span class="input-group-text lebar">Nama Lengkap</span>
            <input type="text" name="fullname" class="form-control form-control-sm" placeholder="Input Nama Lengkap" autocomplete="off" required>
					</div>
			
          <div class="input-group input-group-sm mb-1">
						<span class="input-group-text lebar">Username</span>
						<input type="text" name="username" class="form-control form-control-sm" placeholder="Input Username" autocomplete="off" required>
					</div>

          <div class="input-group mb-1">
						<span class="input-group-text lebar">Institusi</span>
						<input type="text" name="institution" class="form-control form-control-sm" placeholder="Input Institusi" required>
					</div>

          <div class="input-group mb-1">
						<span class="input-group-text lebar">Email</span>
						<input type="email" name="email" class="form-control form-control-sm" placeholder="Input Email" required>
					</div>

          <div class="input-group mb-1">
						<span class="input-group-text lebar">Password</span>
						<input type="password" name="password" class="form-control form-control-sm" placeholder="Input Password" required>
					</div>

          <div class="input-group mb-1">
						<span class="input-group-text lebar">Level</span>
						<select name="id_level" class="form-control form-control-sm" required>
							<option value="" selected>~ Pilih Level ~</option>
							<option value=1>Administrator</option>
							<option value=2>Pengguna</option>
						</select>
					</div>

					<div class="modal-footer">
						<button type="submit" class="btn btn-primary btn-sm">&nbsp;<i class="fas fa-save"></i>&nbsp;&nbsp;Simpan&nbsp;&nbsp;</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('#login').dataTable();

		$('.form-control-chosen').chosen({
			allow_single_deselect: true,
		});

	});
</script>