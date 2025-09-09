<?php
// Backend sederhana untuk Dashboard Monitoring CalonPelanggan.id
// Menggunakan PHP dan MySQL tanpa API

require_once 'config.php';

// Fungsi untuk mendapatkan semua pelanggan
function getPelanggan() {
    global $conn;
    $sql = "SELECT p.*, s.nama as nama_sales FROM pelanggan p LEFT JOIN sales s ON p.sales_id = s.id ORDER BY p.id DESC";
    return $conn->query($sql);
}

// Fungsi untuk menambahkan pelanggan
function tambahPelanggan($data) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO pelanggan (odp, nama, alamat, no_telepon, sales_id, visit, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssiss", $data['odp'], $data['nama'], $data['alamat'], $data['no_telepon'], $data['sales_id'], $data['visit'], $data['keterangan']);
    return $stmt->execute();
}

// Fungsi untuk menghapus pelanggan
function hapusPelanggan($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM pelanggan WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// Fungsi untuk mendapatkan semua sales
function getSales() {
    global $conn;
    $sql = "SELECT * FROM sales ORDER BY nama";
    return $conn->query($sql);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'tambah':
                $data = [
                    'odp' => $_POST['odp'],
                    'nama' => $_POST['nama'],
                    'alamat' => $_POST['alamat'],
                    'no_telepon' => $_POST['no_telepon'],
                    'sales_id' => $_POST['sales_id'],
                    'visit' => $_POST['visit'],
                    'keterangan' => $_POST['keterangan']
                ];
                if (tambahPelanggan($data)) {
                    $message = "Pelanggan berhasil ditambahkan";
                } else {
                    $message = "Gagal menambahkan pelanggan";
                }
                break;
                
            case 'hapus':
                if (isset($_POST['id'])) {
                    if (hapusPelanggan($_POST['id'])) {
                        $message = "Pelanggan berhasil dihapus";
                    } else {
                        $message = "Gagal menghapus pelanggan";
                    }
                }
                break;
        }
    }
}

// Ambil data untuk ditampilkan
$pelanggan = getPelanggan();
$sales = getSales();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Monitoring - CalonPelanggan.id</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <img src="Downloads/download-removebg-preview.png" alt="Logo">
                <span>CalonPelanggan.id</span>
            </div>
            <div class="sales-list">
                <h3>Nama Sales</h3>
                <ul>
                    <?php while($row = $sales->fetch_assoc()): ?>
                        <li><?= htmlspecialchars($row['nama']) ?></li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="dashboard-header">
                <h1>Dashboard Monitoring</h1>
            </header>

            <!-- Info Cards -->
            <section class="info-cards">
                <div class="card">
                    <span class="icon">👤</span>
                    <div>
                        <div class="card-label">Total Pelanggan:</div>
                        <div class="card-value"><?= $pelanggan->num_rows ?></div>
                    </div>
                </div>
            </section>

            <!-- Controls -->
            <section class="controls">
                <button onclick="document.getElementById('modal').style.display='block'" class="btn-add">Tambahkan Pelanggan Baru</button>
            </section>

            <!-- Table -->
            <section class="table-section">
                <table>
                    <thead>
                        <tr>
                            <th>ODP Terdekat</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>No Telepon</th>
                            <th>Nama Sales</th>
                            <th>Visit</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $pelanggan->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['odp']) ?></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['alamat']) ?></td>
                            <td><?= htmlspecialchars($row['no_telepon']) ?></td>
                            <td><?= htmlspecialchars($row['nama_sales']) ?></td>
                            <td><?= htmlspecialchars($row['visit']) ?></td>
                            <td><?= htmlspecialchars($row['keterangan']) ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="hapus">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus data pelanggan ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>
        </main>

        <!-- Modal -->
        <div id="modal" class="modal" style="display: none;">
            <div class="modal-content">
                <h2>Tambahkan Pelanggan Baru</h2>
                <form method="POST">
                    <input type="hidden" name="action" value="tambah">
                    <label>ODP Terdekat:</label>
                    <input type="text" name="odp" required>
                    
                    <label>Nama:</label>
                    <input type="text" name="nama" required>
                    
                    <label>Alamat:</label>
                    <input type="text" name="alamat" required>
                    
                    <label>No Telepon:</label>
                    <input type="tel" name="no_telepon" required>
                    
                    <label>Nama Sales:</label>
                    <select name="sales_id" required>
                        <option value="">Pilih Sales</option>
                        <?php 
                        $sales = getSales();
                        while($row = $sales->fetch_assoc()): 
                        ?>
                            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nama']) ?></option>
                        <?php endwhile; ?>
                    </select>
                    
                    <label>Visit:</label>
                    <input type="date" name="visit">
                    
                    <label>Keterangan:</label>
                    <textarea name="keterangan"></textarea>
                    
                    <button type="submit" class="btn-submit">Simpan</button>
                    <button type="button" onclick="document.getElementById('modal').style.display='none'" class="btn-cancel">Batal</button>
                </form>
            </div>
        </div>

        <?php if (isset($message)): ?>
            <script>alert('<?= $message ?>');</script>
        <?php endif; ?>
    </body>
</html>
