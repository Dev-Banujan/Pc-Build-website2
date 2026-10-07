-- PC Builder Website Database Setup
-- Run this in phpMyAdmin or MySQL CLI

CREATE DATABASE IF NOT EXISTS pcbuilder;
USE pcbuilder;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS builds (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    build_name VARCHAR(100) NOT NULL,
    cpu VARCHAR(100), cpu_price DECIMAL(10,2) DEFAULT 0,
    gpu VARCHAR(100), gpu_price DECIMAL(10,2) DEFAULT 0,
    ram VARCHAR(100), ram_price DECIMAL(10,2) DEFAULT 0,
    storage VARCHAR(100), storage_price DECIMAL(10,2) DEFAULT 0,
    motherboard VARCHAR(100), motherboard_price DECIMAL(10,2) DEFAULT 0,
    psu VARCHAR(100), psu_price DECIMAL(10,2) DEFAULT 0,
    pc_case VARCHAR(100), case_price DECIMAL(10,2) DEFAULT 0,
    total_price DECIMAL(10,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL DEFAULT 0,
    category VARCHAR(100) DEFAULT '',
    image_url VARCHAR(500) DEFAULT '',
    in_stock TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default admin account (password: admin123)
-- NOTE: Use setup_admin.php in your browser to create the admin with the correct password.
INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@pcbuilder.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- ── CPU (5 products) ──
INSERT INTO products (name, description, price, category, image_url) VALUES
('Intel Core i3-13100', 'Quad-core 3.4GHz, up to 4.5GHz boost, LGA1700. Great entry-level CPU for everyday tasks.', 139.00, 'CPU', 'uploads/cpu_1.jpg'),
('Intel Core i5-13600K', '14-core (6P+8E), up to 5.1GHz, LGA1700. Best mid-range CPU for gaming.', 289.00, 'CPU', 'uploads/cpu_2.jpg'),
('Intel Core i7-13700K', '16-core (8P+8E), up to 5.4GHz, LGA1700. Excellent for gaming and content creation.', 409.00, 'CPU', 'uploads/cpu_3.jpg'),
('Intel Core i9-13900K', '24-core (8P+16E), up to 5.8GHz, LGA1700. Flagship performance for professionals.', 589.00, 'CPU', 'uploads/cpu_4.jpg'),
('AMD Ryzen 9 7950X', '16-core, up to 5.7GHz, AM5 socket. Top-tier AMD CPU for workstation use.', 699.00, 'CPU', 'uploads/cpu_5.jpg');

-- ── GPU (5 products) ──
INSERT INTO products (name, description, price, category, image_url) VALUES
('NVIDIA RTX 3060 12GB', 'DLSS 2, ray tracing, 12GB GDDR6. Solid 1080p/1440p gaming card.', 329.00, 'GPU', 'uploads/gpu_1.jpg'),
('NVIDIA RTX 4060 8GB', 'DLSS 3, Ada Lovelace, 8GB GDDR6. Efficient 1080p powerhouse.', 299.00, 'GPU', 'uploads/gpu_2.jpg'),
('NVIDIA RTX 4070 12GB', 'DLSS 3, ray tracing, 12GB GDDR6X. Ideal for 1440p high-refresh gaming.', 599.00, 'GPU', 'uploads/gpu_3.jpg'),
('NVIDIA RTX 4080 16GB', 'DLSS 3, 16GB GDDR6X, 4K gaming performance. Top-end enthusiast card.', 1199.00, 'GPU', 'uploads/gpu_4.jpg'),
('AMD RX 7900 XTX 24GB', 'RDNA 3, 24GB GDDR6, outstanding 4K rasterization performance.', 999.00, 'GPU', 'uploads/gpu_5.jpg');

-- ── RAM (5 products) ──
INSERT INTO products (name, description, price, category, image_url) VALUES
('Corsair 8GB DDR4-3200', 'Single 8GB DDR4 stick, 3200MHz CL16. Budget upgrade for older platforms.', 29.00, 'RAM', 'uploads/ram_1.jpg'),
('Kingston 16GB DDR4-3600', '2x8GB kit, DDR4-3600 CL18, XMP 2.0 ready. Best value for AM4/LGA1700.', 49.00, 'RAM', 'uploads/ram_2.jpg'),
('G.Skill 32GB DDR4-3600', '2x16GB Trident Z Neo, DDR4-3600 CL16. Optimized for AMD Ryzen.', 89.00, 'RAM', 'uploads/ram_3.jpg'),
('Corsair 16GB DDR5-5600', '2x8GB Vengeance DDR5, 5600MHz CL36. Next-gen platform upgrade.', 79.00, 'RAM', 'uploads/ram_4.jpg'),
('G.Skill 32GB DDR5-6000', '2x16GB Trident Z5, DDR5-6000 CL30. Flagship DDR5 gaming kit.', 129.00, 'RAM', 'uploads/ram_5.jpg');

-- ── HDD (5 products) ──
INSERT INTO products (name, description, price, category, image_url) VALUES
('Seagate Barracuda 1TB', '1TB 7200RPM SATA HDD. Reliable mass storage for games and files.', 39.00, 'HDD', 'uploads/hdd_1.jpg'),
('Western Digital Blue 2TB', '2TB 5400RPM SATA. Great secondary drive for media storage.', 54.00, 'HDD', 'uploads/hdd_2.jpg'),
('Seagate Barracuda 4TB', '4TB 5400RPM SATA. Massive storage for large game libraries.', 79.00, 'HDD', 'uploads/hdd_3.jpg'),
('Toshiba X300 6TB', '6TB 7200RPM SATA, 256MB cache. Performance desktop hard drive.', 119.00, 'HDD', 'uploads/hdd_4.jpg'),
('Western Digital Black 4TB', '4TB 7200RPM performance HDD. Designed for intensive workloads.', 99.00, 'HDD', 'uploads/hdd_5.jpg');

-- ── SSD (5 products) ──
INSERT INTO products (name, description, price, category, image_url) VALUES
('Samsung 870 EVO 500GB', '500GB SATA SSD, 560MB/s read. Fast boot drive replacement.', 49.00, 'SSD', 'uploads/ssd_1.jpg'),
('WD Blue SN570 1TB NVMe', '1TB PCIe 3.0 NVMe, 3500MB/s read. Excellent value M.2 drive.', 69.00, 'SSD', 'uploads/ssd_2.jpg'),
('Samsung 990 Pro 1TB', '1TB PCIe 4.0, up to 7450MB/s read. Premium NVMe performance.', 109.00, 'SSD', 'uploads/ssd_3.png'),
('Samsung 990 Pro 2TB', '2TB PCIe 4.0, up to 7450MB/s. Best overall 2TB NVMe SSD.', 169.00, 'SSD', 'uploads/ssd_4.png'),
('WD Black SN850X 2TB', '2TB PCIe 4.0, 7300MB/s read. Optimized for PlayStation 5 and PC.', 179.00, 'SSD', 'uploads/ssd_5.jpg');

-- ── Motherboard (5 products) ──
INSERT INTO products (name, description, price, category, image_url) VALUES
('ASUS B660M-A (LGA1700)', 'Micro-ATX, PCIe 4.0, DDR4, solid budget board for Intel 12th/13th Gen.', 129.00, 'Motherboard', 'uploads/mb_1.jpg'),
('MSI Z790 Tomahawk (LGA1700)', 'ATX, PCIe 5.0, DDR5, WiFi 6E. Best mid-range Intel Z790 board.', 239.00, 'Motherboard', 'uploads/mb_2.jpg'),
('MSI B650 Tomahawk (AM5)', 'ATX, PCIe 4.0, DDR5, WiFi 6. Excellent value AM5 motherboard.', 199.00, 'Motherboard', 'uploads/mb_3.jpg'),
('ASUS ROG Strix X670E-F (AM5)', 'ATX, PCIe 5.0, DDR5, WiFi 6E. High-end AM5 gaming board.', 399.00, 'Motherboard', 'uploads/mb_4.jpg'),
('Gigabyte Z790 Aorus Elite (LGA1700)', 'ATX, PCIe 4.0, DDR5, 2.5G LAN. Feature-packed Intel board.', 299.00, 'Motherboard', 'uploads/mb_5.jpg');

-- ── Monitor (5 products) ──
INSERT INTO products (name, description, price, category, image_url) VALUES
('LG 24GN650-B 24" 144Hz', '24" FHD IPS, 144Hz, 1ms, FreeSync. Sharp budget gaming monitor.', 179.00, 'Monitor', 'uploads/monitor_1.jpg'),
('ASUS TUF VG27AQ 27" 165Hz', '27" QHD IPS, 165Hz, HDR10, G-Sync Compatible. Top 1440p monitor.', 329.00, 'Monitor', 'uploads/monitor_2.jpg'),
('Samsung Odyssey G7 32" 240Hz', '32" QHD VA curved, 240Hz, HDR600. Immersive curved gaming display.', 549.00, 'Monitor', 'uploads/monitor_3.jpg'),
('LG 27GP950-B 27" 4K 160Hz', '27" 4K Nano IPS, 160Hz, HDR600, HDMI 2.1. Premium 4K gaming.', 699.00, 'Monitor', 'uploads/monitor_4.jpg'),
('ASUS ROG Swift PG32UQX 32" 4K', '32" 4K IPS, 144Hz, HDR1400, Mini LED. Flagship gaming monitor.', 1999.00, 'Monitor', 'uploads/monitor_5.jpg');

-- ── PC / Prebuilt (5 products) ──
INSERT INTO products (name, description, price, category, image_url) VALUES
('Starter Gaming PC (GTX 1660 + i5)', 'Intel Core i5-12400, GTX 1660 Super, 16GB DDR4, 512GB NVMe. Ready-to-play 1080p gaming PC.', 799.00, 'PC', 'uploads/pc_1.jpg'),
('Mid-Range Gaming PC (RTX 3060 + i5)', 'Intel Core i5-13600K, RTX 3060, 16GB DDR4, 1TB NVMe. Great 1440p gaming performance.', 1099.00, 'PC', 'uploads/pc_2.jpg'),
('High-End Gaming PC (RTX 4070 + i7)', 'Intel Core i7-13700K, RTX 4070, 32GB DDR5, 1TB NVMe. Elite 1440p/4K gaming rig.', 1799.00, 'PC', 'uploads/pc_3.jpg'),
('AMD Gaming PC (RX 7900 + Ryzen 7)', 'AMD Ryzen 7 7700X, RX 7900 XT, 32GB DDR5, 2TB NVMe. Powerful AMD build.', 1699.00, 'PC', 'uploads/pc_4.jpg'),
('Ultimate Workstation (RTX 4090 + i9)', 'Intel Core i9-13900K, RTX 4090, 64GB DDR5, 4TB NVMe. No-compromise beast.', 3999.00, 'PC', 'uploads/pc_5.jpg');

-- ── Keyboard (5 products) ──
INSERT INTO products (name, description, price, category, image_url) VALUES
('Logitech G213 Prodigy', 'Membrane gaming keyboard, RGB Lightsync, spill-resistant. Great budget option.', 49.00, 'Keyboard', 'uploads/keyboard_1.jpg'),
('Corsair K70 RGB MK.2', 'Cherry MX Red switches, full RGB, aluminum frame. Classic mechanical gaming keyboard.', 109.00, 'Keyboard', 'uploads/keyboard_2.jpg'),
('SteelSeries Apex Pro TKL', 'OmniPoint adjustable mechanical switches, OLED display, TKL layout.', 149.00, 'Keyboard', 'uploads/keyboard_3.jpg'),
('Razer BlackWidow V3 Pro', 'Wireless mechanical keyboard, Razer Yellow switches, RGB Chroma.', 169.00, 'Keyboard', 'uploads/keyboard_4.jpg'),
('HyperX Alloy Origins 65', 'Compact 65% layout, HyperX Red linear switches, RGB per-key lighting.', 89.00, 'Keyboard', 'uploads/keyboard_5.jpg');

-- ── Mouse (5 products) ──
INSERT INTO products (name, description, price, category, image_url) VALUES
('Logitech G203 Lightsync', '8000 DPI optical sensor, 6 programmable buttons, lightweight at 85g.', 29.00, 'Mouse', 'uploads/mouse_1.jpg'),
('Razer DeathAdder V3', 'Focus Pro 30K optical sensor, ergonomic right-hand design, 59g.', 69.00, 'Mouse', 'uploads/mouse_2.jpg'),
('Logitech G Pro X Superlight 2', 'HERO 2 25K sensor, ultra-lightweight 60g, wireless. Tournament-grade mouse.', 159.00, 'Mouse', 'uploads/mouse_3.jpg'),
('SteelSeries Aerox 5 Wireless', 'TrueMove Air 18K sensor, 9 buttons, honeycomb shell, 74g.', 129.00, 'Mouse', 'uploads/mouse_4.jpg'),
('Razer Viper V2 Pro', 'Focus Pro 30K sensor, 58g ultralight, 80-hour battery life, wireless.', 149.00, 'Mouse', 'uploads/mouse_5.jpg');

-- ── Cooler (5 products) ──
INSERT INTO products (name, description, price, category, image_url) VALUES
('Cooler Master Hyper 212', 'Tower air cooler, 4 heat pipes, 120mm fan, supports LGA1700/AM5.', 34.00, 'Cooler', 'uploads/cooler_1.jpg'),
('Noctua NH-D15', 'Dual-tower air cooler, two 140mm fans, best-in-class quiet cooling.', 99.00, 'Cooler', 'uploads/cooler_2.jpg'),
('Corsair H100i Elite 240mm AIO', '240mm liquid cooler, iCUE ARGB fans, zero RPM mode.', 129.00, 'Cooler', 'uploads/cooler_3.jpg'),
('NZXT Kraken X63 280mm AIO', '280mm radiator, Aer P fans, LCD display head. Premium liquid cooling.', 149.00, 'Cooler', 'uploads/cooler_4.jpg'),
('Lian Li Galahad 360mm AIO', '360mm liquid cooler, triple 120mm ARGB fans, powerful and quiet.', 169.00, 'Cooler', 'uploads/cooler_5.jpg');

-- ── PSU / Power Supply (5 products) ──
INSERT INTO products (name, description, price, category, image_url) VALUES
('Corsair CV550 550W 80+ Bronze', 'Non-modular, 550W, 80+ Bronze certified. Reliable budget PSU.', 59.00, 'PSU', 'uploads/psu_1.jpg'),
('Seasonic Focus GX-650 650W Gold', 'Fully modular, 650W, 80+ Gold, 10-year warranty. Excellent quality.', 99.00, 'PSU', 'uploads/psu_2.jpg'),
('EVGA SuperNOVA 750W Gold', 'Fully modular, 750W, 80+ Gold, compact design. Industry standard.', 109.00, 'PSU', 'uploads/psu_3.jpg'),
('Corsair RM850x 850W Gold', 'Fully modular, 850W, 80+ Gold, zero RPM fan mode. Silent PSU.', 139.00, 'PSU', 'uploads/psu_4.jpg'),
('Seasonic Prime TX-1000 1000W Titanium', 'Fully modular, 1000W, 80+ Titanium, ultra-quiet. For RTX 4090 builds.', 249.00, 'PSU', 'uploads/psu_5.jpg');

-- ── Casing (5 products) ──
INSERT INTO products (name, description, price, category, image_url) VALUES
('Fractal Design Define R5', 'Mid-tower ATX, noise-dampened panels, excellent cable management.', 99.00, 'Casing', 'uploads/casing_1.jpg'),
('NZXT H510 Mid Tower', 'ATX mid-tower, tempered glass panel, two 120mm fans included, clean aesthetic.', 79.00, 'Casing', 'uploads/casing_2.jpg'),
('Corsair 4000D Airflow', 'ATX mid-tower, mesh front panel, high-airflow design, two 120mm fans.', 109.00, 'Casing', 'uploads/casing_3.jpg'),
('Lian Li PC-O11 Dynamic', 'ATX mid-tower, dual-chamber design, three-sided tempered glass. Stunning showcase build.', 149.00, 'Casing', 'uploads/casing_4.jpg'),
('Phanteks Enthoo Pro 2', 'Full-tower ATX/E-ATX, massive interior, multiple radiator support, ARGB lighting.', 179.00, 'Casing', 'uploads/casing_5.jpg');
