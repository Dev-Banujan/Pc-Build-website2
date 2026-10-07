-- ============================================================
--  PC BUILDER - 65 Sample Products (13 categories x 5 each)
--  How to use:
--  1. Open phpMyAdmin -> select "pcbuilder" database
--  2. Click the "SQL" tab
--  3. Paste this entire file and click "Go"
-- ============================================================

USE pcbuilder;

-- CPU (5 products)
INSERT INTO products (name, description, price, category, image_url, in_stock) VALUES
('Intel Core i3-13100', 'Quad-core 3.4GHz, up to 4.5GHz boost, LGA1700. Great entry-level CPU for everyday tasks and light gaming.', 139.00, 'CPU', 'uploads/cpu_1.jpg', 1),
('Intel Core i5-13600K', '14-core (6P+8E), up to 5.1GHz, LGA1700. Best mid-range CPU for gaming - excellent price to performance.', 289.00, 'CPU', 'uploads/cpu_2.jpg', 1),
('Intel Core i7-13700K', '16-core (8P+8E), up to 5.4GHz, LGA1700. Excellent for gaming, streaming, and content creation.', 409.00, 'CPU', 'uploads/cpu_3.jpg', 1),
('Intel Core i9-13900K', '24-core (8P+16E), up to 5.8GHz, LGA1700. Flagship performance for professionals and enthusiasts.', 589.00, 'CPU', 'uploads/cpu_4.jpg', 1),
('AMD Ryzen 9 7950X', '16-core, up to 5.7GHz, AM5 socket. Top-tier AMD CPU for workstation and heavy multitasking workloads.', 699.00, 'CPU', 'uploads/cpu_5.jpg', 1);

-- GPU (5 products)
INSERT INTO products (name, description, price, category, image_url, in_stock) VALUES
('NVIDIA RTX 3060 12GB', 'DLSS 2, ray tracing, 12GB GDDR6. Solid 1080p and 1440p gaming card at a great price.', 329.00, 'GPU', 'uploads/gpu_1.jpg', 1),
('NVIDIA RTX 4060 8GB', 'DLSS 3, Ada Lovelace architecture, 8GB GDDR6. Efficient and powerful 1080p gaming GPU.', 299.00, 'GPU', 'uploads/gpu_2.jpg', 1),
('NVIDIA RTX 4070 12GB', 'DLSS 3, ray tracing, 12GB GDDR6X. Ideal for 1440p high-refresh gaming with DLSS 3 Frame Generation.', 599.00, 'GPU', 'uploads/gpu_3.jpg', 1),
('NVIDIA RTX 4080 16GB', 'DLSS 3, 16GB GDDR6X, outstanding 4K gaming performance. Top-end enthusiast graphics card.', 1199.00, 'GPU', 'uploads/gpu_4.jpg', 1),
('AMD RX 7900 XTX 24GB', 'RDNA 3 architecture, 24GB GDDR6, outstanding 4K rasterization and ray tracing performance.', 999.00, 'GPU', 'uploads/gpu_5.jpg', 1);

-- RAM (5 products)
INSERT INTO products (name, description, price, category, image_url, in_stock) VALUES
('Corsair 8GB DDR4-3200', 'Single 8GB DDR4 stick, 3200MHz CL16. Budget-friendly upgrade for older Intel and AMD platforms.', 29.00, 'RAM', 'uploads/ram_1.jpg', 1),
('Kingston 16GB DDR4-3600', '2x8GB kit, DDR4-3600 CL18, XMP 2.0 support. Best value RAM kit for AM4 and LGA1700 builds.', 49.00, 'RAM', 'uploads/ram_2.jpg', 1),
('G.Skill Trident Z Neo 32GB DDR4-3600', '2x16GB, DDR4-3600 CL16, RGB lighting. Optimized specifically for AMD Ryzen platforms.', 89.00, 'RAM', 'uploads/ram_3.jpg', 1),
('Corsair Vengeance 16GB DDR5-5600', '2x8GB DDR5, 5600MHz CL36. Next-generation memory for Intel 12th/13th Gen and AMD AM5 builds.', 79.00, 'RAM', 'uploads/ram_4.jpg', 1),
('G.Skill Trident Z5 32GB DDR5-6000', '2x16GB, DDR5-6000 CL30, XMP 3.0. Premium flagship DDR5 kit for maximum gaming performance.', 129.00, 'RAM', 'uploads/ram_5.jpg', 1);

-- HDD (5 products)
INSERT INTO products (name, description, price, category, image_url, in_stock) VALUES
('Seagate Barracuda 1TB', '1TB 7200RPM SATA 3.5-inch. Reliable and affordable mass storage for games, media, and backups.', 39.00, 'HDD', 'uploads/hdd_1.jpg', 1),
('Western Digital Blue 2TB', '2TB 5400RPM SATA 3.5-inch. Great secondary drive for media libraries and file storage.', 54.00, 'HDD', 'uploads/hdd_2.jpg', 1),
('Seagate Barracuda 4TB', '4TB 5400RPM SATA, 256MB cache. Massive storage capacity for large game and media collections.', 79.00, 'HDD', 'uploads/hdd_3.jpg', 1),
('Toshiba X300 6TB', '6TB 7200RPM SATA, 256MB cache. High-performance desktop hard drive for intensive workloads.', 119.00, 'HDD', 'uploads/hdd_4.jpg', 1),
('Western Digital Black 4TB', '4TB 7200RPM SATA, 256MB cache. Performance-focused HDD designed for gaming and creative work.', 99.00, 'HDD', 'uploads/hdd_5.jpg', 1);

-- SSD (5 products)
INSERT INTO products (name, description, price, category, image_url, in_stock) VALUES
('Samsung 870 EVO 500GB SATA', '500GB SATA SSD, 560MB/s read, 530MB/s write. Fast and reliable boot drive or OS replacement.', 49.00, 'SSD', 'uploads/ssd_1.jpg', 1),
('WD Blue SN570 1TB NVMe', '1TB PCIe 3.0 NVMe M.2, 3500MB/s read. Excellent value PCIe 3 drive for budget to mid-range builds.', 69.00, 'SSD', 'uploads/ssd_2.jpg', 1),
('Samsung 990 Pro 1TB NVMe', '1TB PCIe 4.0 NVMe, up to 7450MB/s read. Premium performance M.2 SSD for high-end gaming PCs.', 109.00, 'SSD', 'uploads/ssd_3.jpg', 1),
('Samsung 990 Pro 2TB NVMe', '2TB PCIe 4.0 NVMe, up to 7450MB/s read. Best overall 2TB NVMe SSD for gaming and workstations.', 169.00, 'SSD', 'uploads/ssd_4.jpg', 1),
('WD Black SN850X 2TB NVMe', '2TB PCIe 4.0 NVMe, 7300MB/s read, 6600MB/s write. Optimized for gaming PCs and PlayStation 5.', 179.00, 'SSD', 'uploads/ssd_5.jpg', 1);

-- Motherboard (5 products)
INSERT INTO products (name, description, price, category, image_url, in_stock) VALUES
('ASUS B660M-A DDR4 (LGA1700)', 'Micro-ATX, PCIe 4.0, DDR4 support, dual M.2 slots. Solid budget motherboard for Intel 12th/13th Gen.', 129.00, 'Motherboard', 'uploads/mb_1.jpg', 1),
('MSI Z790 Tomahawk WiFi (LGA1700)', 'ATX, PCIe 5.0, DDR5, WiFi 6E, 2.5G LAN. Best mid-range Intel Z790 motherboard for enthusiasts.', 239.00, 'Motherboard', 'uploads/mb_2.jpg', 1),
('MSI B650 Tomahawk WiFi (AM5)', 'ATX, PCIe 4.0, DDR5, WiFi 6, 2.5G LAN. Excellent value AM5 motherboard for AMD Ryzen 7000 series.', 199.00, 'Motherboard', 'uploads/mb_3.jpg', 1),
('ASUS ROG Strix X670E-F Gaming WiFi (AM5)', 'ATX, PCIe 5.0, DDR5, WiFi 6E, 10G LAN. High-end AM5 gaming and overclocking motherboard.', 399.00, 'Motherboard', 'uploads/mb_4.jpg', 1),
('Gigabyte Z790 Aorus Elite AX (LGA1700)', 'ATX, PCIe 4.0, DDR5, WiFi 6E, 2.5G LAN. Feature-packed Intel board with excellent VRM design.', 299.00, 'Motherboard', 'uploads/mb_5.jpg', 1);

-- Monitor (5 products)
INSERT INTO products (name, description, price, category, image_url, in_stock) VALUES
('LG 24GN650-B 24" 144Hz', '24-inch FHD IPS, 144Hz, 1ms (GtG), FreeSync Premium. Sharp and fast budget gaming monitor.', 179.00, 'Monitor', 'uploads/monitor_1.jpg', 1),
('ASUS TUF VG27AQ 27" 165Hz', '27-inch QHD IPS, 165Hz, HDR10, G-Sync Compatible. Top 1440p gaming monitor with great colours.', 329.00, 'Monitor', 'uploads/monitor_2.jpg', 1),
('Samsung Odyssey G7 32" 240Hz', '32-inch QHD VA curved (1000R), 240Hz, HDR600. Immersive curved gaming with exceptional contrast.', 549.00, 'Monitor', 'uploads/monitor_3.jpg', 1),
('LG 27GP950-B 27" 4K 160Hz', '27-inch 4K UHD Nano IPS, 160Hz, HDR600, HDMI 2.1. Premium 4K gaming monitor for PC and consoles.', 699.00, 'Monitor', 'uploads/monitor_4.jpg', 1),
('ASUS ROG Swift PG32UQX 32" 4K', '32-inch 4K IPS, 144Hz, HDR1400, Mini LED backlighting. Flagship gaming monitor - the absolute best.', 1999.00, 'Monitor', 'uploads/monitor_5.jpg', 1);

-- PC Prebuilt (5 products)
INSERT INTO products (name, description, price, category, image_url, in_stock) VALUES
('Starter Gaming PC (GTX 1660 + i5-12400)', 'Intel Core i5-12400, GTX 1660 Super, 16GB DDR4, 512GB NVMe SSD. Ready-to-play 1080p gaming PC.', 799.00, 'PC', 'uploads/pc_1.jpg', 1),
('Mid-Range Gaming PC (RTX 3060 + i5-13600K)', 'Intel Core i5-13600K, RTX 3060 12GB, 16GB DDR4, 1TB NVMe. Excellent 1440p performance out of the box.', 1099.00, 'PC', 'uploads/pc_2.jpg', 1),
('High-End Gaming PC (RTX 4070 + i7-13700K)', 'Intel Core i7-13700K, RTX 4070 12GB, 32GB DDR5, 1TB NVMe. Elite 1440p and 4K gaming rig.', 1799.00, 'PC', 'uploads/pc_3.jpg', 1),
('AMD Gaming PC (RX 7900 XT + Ryzen 7 7700X)', 'AMD Ryzen 7 7700X, RX 7900 XT 20GB, 32GB DDR5, 2TB NVMe. Powerful all-AMD gaming powerhouse.', 1699.00, 'PC', 'uploads/pc_4.jpg', 1),
('Ultimate Workstation (RTX 4090 + i9-13900K)', 'Intel Core i9-13900K, RTX 4090 24GB, 64GB DDR5, 4TB NVMe. No-compromise ultimate beast machine.', 3999.00, 'PC', 'uploads/pc_5.jpg', 1);

-- Keyboard (5 products)
INSERT INTO products (name, description, price, category, image_url, in_stock) VALUES
('Logitech G213 Prodigy RGB', 'Membrane gaming keyboard, Lightsync RGB, spill-resistant, dedicated media controls. Great budget pick.', 49.00, 'Keyboard', 'uploads/keyboard_1.jpg', 1),
('Corsair K70 RGB MK.2 Cherry MX Red', 'Cherry MX Red linear switches, full RGB per-key lighting, brushed aluminum frame. Classic mechanical.', 109.00, 'Keyboard', 'uploads/keyboard_2.jpg', 1),
('SteelSeries Apex Pro TKL Wireless', 'OmniPoint 2.0 adjustable mechanical switches, OLED smart display, TKL compact layout, wireless.', 199.00, 'Keyboard', 'uploads/keyboard_3.jpg', 1),
('Razer BlackWidow V3 Pro Wireless', 'Razer Yellow linear switches, wireless with 200-hour battery, Chroma RGB, multi-device support.', 169.00, 'Keyboard', 'uploads/keyboard_4.jpg', 1),
('HyperX Alloy Origins 65', 'Compact 65% layout, HyperX Red linear switches, per-key RGB, aircraft-grade aluminum body.', 89.00, 'Keyboard', 'uploads/keyboard_5.jpg', 1);

-- Mouse (5 products)
INSERT INTO products (name, description, price, category, image_url, in_stock) VALUES
('Logitech G203 Lightsync', '8000 DPI HERO optical sensor, 6 programmable buttons, Lightsync RGB, lightweight at 85g.', 29.00, 'Mouse', 'uploads/mouse_1.jpg', 1),
('Razer DeathAdder V3', 'Focus Pro 30K optical sensor, ergonomic right-hand design, 6 buttons, ultralight 59g, wired.', 69.00, 'Mouse', 'uploads/mouse_2.jpg', 1),
('Logitech G Pro X Superlight 2', 'HERO 2 25K DPI sensor, ultralight 60g, wireless 2.4GHz, zero-compromise tournament mouse.', 159.00, 'Mouse', 'uploads/mouse_3.jpg', 1),
('SteelSeries Aerox 5 Wireless', 'TrueMove Air 18K sensor, 9 programmable buttons, honeycomb shell, 74g, wireless gaming mouse.', 129.00, 'Mouse', 'uploads/mouse_4.jpg', 1),
('Razer Viper V2 Pro', 'Focus Pro 30K optical sensor, 58g ultralight, 80-hour battery, wireless 2.4GHz. Pro esports mouse.', 149.00, 'Mouse', 'uploads/mouse_5.jpg', 1);

-- Cooler (5 products)
INSERT INTO products (name, description, price, category, image_url, in_stock) VALUES
('Cooler Master Hyper 212 Black Edition', 'Single-tower air cooler, 4 heat pipes, 120mm PWM fan, supports LGA1700 and AM5. Budget king.', 34.00, 'Cooler', 'uploads/cooler_1.jpg', 1),
('Noctua NH-D15', 'Dual-tower air cooler, two 140mm NF-A15 fans, 250W TDP rating. Best-in-class silent air cooling.', 99.00, 'Cooler', 'uploads/cooler_2.jpg', 1),
('Corsair iCUE H100i Elite Capellix 240mm', '240mm AIO liquid cooler, LL120 RGB fans, zero RPM mode below 30% load. iCUE software control.', 129.00, 'Cooler', 'uploads/cooler_3.jpg', 1),
('NZXT Kraken X63 280mm AIO', '280mm AIO, dual 140mm Aer P fans, LCD head display, infinite mirror effect. Premium all-in-one.', 149.00, 'Cooler', 'uploads/cooler_4.jpg', 1),
('Lian Li Galahad 360mm ARGB AIO', '360mm AIO liquid cooler, three 120mm ARGB fans, powerful yet quiet. Great value 360mm option.', 169.00, 'Cooler', 'uploads/cooler_5.jpg', 1);

-- PSU (5 products)
INSERT INTO products (name, description, price, category, image_url, in_stock) VALUES
('Corsair CV550 550W 80+ Bronze', 'Non-modular PSU, 550W, 80+ Bronze certified. Reliable and affordable for mid-range builds.', 59.00, 'PSU', 'uploads/psu_1.jpg', 1),
('Seasonic Focus GX-650 650W Gold', 'Fully modular, 650W, 80+ Gold, 10-year warranty, fanless mode below 30% load. Superb quality.', 99.00, 'PSU', 'uploads/psu_2.jpg', 1),
('EVGA SuperNOVA 750G6 750W Gold', 'Fully modular, 750W, 80+ Gold, compact 140mm size, ECO mode for silent operation.', 109.00, 'PSU', 'uploads/psu_3.jpg', 1),
('Corsair RM850x 850W Gold', 'Fully modular, 850W, 80+ Gold, zero RPM fan mode below 40% load. Ultra-silent premium PSU.', 139.00, 'PSU', 'uploads/psu_4.jpg', 1),
('Seasonic Prime TX-1000 1000W Titanium', 'Fully modular, 1000W, 80+ Titanium efficiency, fanless mode up to 40% load. For RTX 4090 builds.', 249.00, 'PSU', 'uploads/psu_5.jpg', 1);

-- Casing (5 products)
INSERT INTO products (name, description, price, category, image_url, in_stock) VALUES
('Fractal Design Define R5', 'Mid-tower ATX, noise-dampened panels, ModuVent technology, great cable management. Silent build case.', 99.00, 'Casing', 'uploads/casing_1.jpg', 1),
('NZXT H510 Flow Mid Tower', 'ATX mid-tower, mesh front panel, tempered glass side, two 120mm fans included. Clean modern design.', 79.00, 'Casing', 'uploads/casing_2.jpg', 1),
('Corsair 4000D Airflow', 'ATX mid-tower, mesh front panel, high-airflow design, two 120mm fans, tool-free drive bays.', 109.00, 'Casing', 'uploads/casing_3.jpg', 1),
('Lian Li PC-O11 Dynamic EVO', 'ATX mid-tower, dual-chamber design, three-sided tempered glass, supports up to 3 radiators.', 149.00, 'Casing', 'uploads/casing_4.jpg', 1),
('Phanteks Enthoo Pro 2 Full Tower', 'Full-tower ATX/E-ATX, massive interior, multiple radiator mounts, ARGB lighting, front USB-C port.', 179.00, 'Casing', 'uploads/casing_5.jpg', 1);

-- Done! 65 products inserted across 13 categories (CPU, GPU, RAM, HDD, SSD, Motherboard, Monitor, PC, Keyboard, Mouse, Cooler, PSU, Casing).
