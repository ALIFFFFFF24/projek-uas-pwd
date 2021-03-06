<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="assets/global_style.css">
    <title>Rak Buku</title>
</head>
<?php include("../user/layout/header.php") ?>
<?php

if (isset($_GET['pesan'])) {
    if ($_GET['pesan'] == 'berhasil') {

?>
        <script>
            document.addEventListener("DOMContentLoaded", function(event) {
                Swal.fire({
                    title: 'Login Berhasil!',
                    html: 'Selamat Datang <b><?php echo $_SESSION['nama_lengkap']; ?></b>',
                    icon: 'success',
                    confirmButtonColor: '#0275d8'
                })
            });
        </script>
    <?php } ?>
<?php } ?>
<div class="col-10 container">
    <h1>Daftar Koleksi Buku</h1>
    <table border="2" class="table">
        <div class="row justify-content-end">
            <div class="col-5">
                <form action="index.php" method="get">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="cari"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" placeholder="Cari Judul Buku" name="cari">
                        <button class="btn btn-primary" type="submit" value="cari">Cari</button>
                    </div>
                </form>
            </div>
        </div>
        <tr class="text-center">
            <th>No.</th>
            <th>Kode Buku</th>
            <th>Judul Buku</th>
            <th>Pengarang</th>
            <th>Kategori Buku</th>
            <th>Penerbit</th>

            <th>Aksi</th>
        </tr>
        <!-- Pagenation -->
        <tbody>
            <?php
            include 'koneksi.php';
            $batas = 5;
            $halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
            $halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

            $previous = $halaman - 1;
            $next = $halaman + 1;

            $data = mysqli_query($koneksi, "select buku.kd_buku, buku.judul_buku, buku.pengarang, buku.kategori ,kategori.nama_kategori, buku.penerbit, buku.rak, rak.nama_rak from buku
            inner join kategori on buku.kategori = kategori.id_kategori
            inner join rak on buku.rak = rak.id_rak");
            $jumlah_data = mysqli_num_rows($data);
            $total_halaman = ceil($jumlah_data / $batas);

            if (isset($_GET['cari'])) {
                $cari = $_GET['cari'];
                $data = mysqli_query($koneksi, "select buku.kd_buku, buku.judul_buku, buku.pengarang, buku.kategori ,kategori.nama_kategori, buku.penerbit, buku.rak, rak.nama_rak from buku
                inner join kategori on buku.kategori = kategori.id_kategori
                inner join rak on buku.rak = rak.id_rak where judul_buku like '%" . $cari . "%'");
                $jumlah_data = mysqli_num_rows($data);
                $total_halaman = ceil($jumlah_data / $batas);
                $data_rak = mysqli_query($koneksi, "select buku.kd_buku, buku.judul_buku, buku.pengarang, buku.kategori ,kategori.nama_kategori, buku.penerbit, buku.rak, rak.nama_rak from buku
                inner join kategori on buku.kategori = kategori.id_kategori
                inner join rak on buku.rak = rak.id_rak where judul_buku like '%" . $cari . "%' limit $halaman_awal, $batas");
            } else {
                $data_rak = mysqli_query($koneksi, "select buku.kd_buku, buku.judul_buku, buku.pengarang, buku.kategori ,kategori.nama_kategori, buku.penerbit, buku.rak, rak.nama_rak from buku
            inner join kategori on buku.kategori = kategori.id_kategori
            inner join rak on buku.rak = rak.id_rak limit $halaman_awal, $batas");
            }
            $nomor = $halaman_awal + 1;
            while ($d = mysqli_fetch_array($data_rak)) {
            ?>
                <tr class="text-center">
                    <td><?php echo $nomor++; ?></td>
                    <td><?php echo $d['kd_buku']; ?></td>
                    <td><?php echo $d['judul_buku']; ?></td>
                    <td><?php echo $d['pengarang']; ?></td>
                    <td><?php echo $d['nama_kategori']; ?></td>
                    <td><?php echo $d['penerbit']; ?></td>

                    <td>
                        <a id="tombolDetail" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#detailModal<?php echo $d['kd_buku'] ?>">Lihat Detail</a>
                        <div class="modal fade" id="detailModal<?php echo $d['kd_buku'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Form Ubah Data Buku</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">

                                        <label class="form-group">Kode Buku</label><br />
                                        <input class="form-control" readonly name="kd_buku" value="<?php echo $d['kd_buku']; ?>">

                                        <br>

                                        <label class="form-group">Judul Buku</label><br />
                                        <input class="form-control" readonly type="text" name="judul_buku" value="<?php echo $d['judul_buku']; ?>">

                                        <br>

                                        <label class="form-group">Pengarang</label><br />
                                        <input class="form-control" readonly type="text" name="pengarang" value="<?php echo $d['pengarang']; ?>">

                                        <br>

                                        <label class="form-group">Kategori Buku</label><br />
                                        <input class="form-control" readonly type="text" name="penerbit" value="<?php echo $d['nama_kategori']; ?>">

                                        <br>

                                        <label class="form-group">penerbit</label><br />
                                        <input class="form-control" readonly type="text" name="penerbit" value="<?php echo $d['penerbit']; ?>">

                                        <br>

                                        <label class="form-group">Rak</label><br />
                                        <input class="form-control" readonly type="text" name="penerbit" value="<?php echo $d['nama_rak']; ?>">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <li class="page-item">
                <a class="page-link" <?php if ($halaman > 1) {
                                            echo "href='?halaman=$previous'";
                                        } ?>>Previous
                </a>
            </li>
            <?php
            for ($x = 1; $x <= $total_halaman; $x++) {
            ?>
                <li class="page-item"><a class="page-link" href="?halaman=<?php echo $x ?>"><?php echo $x; ?></a></li>
            <?php
            }
            ?>
            <li class="page-item">
                <a class="page-link" <?php if ($halaman < $total_halaman) {
                                            echo "href='?halaman=$next'";
                                        } ?>>Next
                </a>
            </li>
        </ul>
    </nav>

</div>

<?php include('layout/footer.php') ?>