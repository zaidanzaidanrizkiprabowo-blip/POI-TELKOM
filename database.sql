-- Database: calonpelanggan_db
-- Struktur tabel untuk aplikasi Dashboard Monitoring CalonPelanggan.id

CREATE DATABASE IF NOT EXISTS calonpelanggan_db;
USE calonpelanggan_db;

-- Tabel untuk data sales
CREATE TABLE IF NOT EXISTS sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel untuk data pelanggan
CREATE TABLE IF NOT EXISTS pelanggan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    odp VARCHAR(50) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    alamat TEXT NOT NULL,
    no_telepon VARCHAR(20) NOT NULL,
    sales_id INT,
    visit DATE,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (sales_id) REFERENCES sales(id) ON DELETE SET NULL
);

-- Insert data sales awal
INSERT INTO sales (nama) VALUES 
('Nandi'),
('Andi'),
('Yandi'),
('April'),
('Octa'),
('Toteng'),
('Yusdi'),
('Syarif');

-- Insert data pelanggan awal
INSERT INTO pelanggan (odp, nama, alamat, no_telepon, sales_id, visit, keterangan) VALUES
('ODP-001', 'Budi', 'Jl. Merdeka No.1', '08123456789', 1, '2024-06-01', 'Prospect'),
('ODP-002', 'Siti', 'Jl. Sudirman No.2', '08198765432', 2, '2024-06-02', 'Follow up');
