<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/config.php';
requireAdmin();

$message = '';
$msgType = '';

// Delete user
if (isset($_GET['delete_user']) && is_numeric($_GET['delete_user'])) {
    $uid = (int)$_GET['delete_user'];
    if ($uid != $_SESSION['user_id']) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $stmt->close();
        $message = 'User deleted successfully.';
        $msgType = 'success';
    } else {
        $message = 'You cannot delete your own admin account.';
        $msgType = 'error';
    }
}

// Delete build
if (isset($_GET['delete_build']) && is_numeric($_GET['delete_build'])) {
    $bid = (int)$_GET['delete_build'];
    $stmt = $conn->prepare("DELETE FROM builds WHERE id = ?");
    $stmt->bind_param("i", $bid);
    $stmt->execute();
    $stmt->close();
    $message = 'Build deleted.';
    $msgType = 'success';
}

// Change user role
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_role'])) {
    $uid     = (int)$_POST['user_id'];
    $newRole = $_POST['new_role'];
    if (in_array($newRole, ['user', 'admin']) && $uid != $_SESSION['user_id']) {
        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $newRole, $uid);
        $stmt->execute();
        $stmt->close();
        $message = 'User role updated.';
        $msgType = 'success';
    }
}

// Auto-create products table if missing
$conn->query("CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL DEFAULT 0,
    category VARCHAR(100) DEFAULT '',
    image_url VARCHAR(500) DEFAULT '',
    in_stock TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// ── Product actions ──
// Add product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $pname  = trim($_POST['p_name'] ?? '');
    $pdesc  = trim($_POST['p_desc'] ?? '');
    $pprice = (float)($_POST['p_price'] ?? 0);
    $pcat   = trim($_POST['p_category'] ?? '');
    $pstock = isset($_POST['p_instock']) ? 1 : 0;

    // Handle image upload
    $pimg = '';
    if (!empty($_FILES['p_image_file']['name'])) {
        $uploadDir   = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $allowed  = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $mimeType = mime_content_type($_FILES['p_image_file']['tmp_name']);
        $fileSize = $_FILES['p_image_file']['size'];

        if (!in_array($mimeType, $allowed)) {
            $message = 'Only JPG, PNG, GIF, and WEBP images are allowed.';
            $msgType = 'error';
        } elseif ($fileSize > 5 * 1024 * 1024) {
            $message = 'Image must be under 5MB.';
            $msgType = 'error';
        } else {
            $ext    = pathinfo($_FILES['p_image_file']['name'], PATHINFO_EXTENSION);
            $fname  = 'product_' . time() . '_' . mt_rand(100, 999) . '.' . strtolower($ext);
            $dest   = $uploadDir . $fname;
            if (move_uploaded_file($_FILES['p_image_file']['tmp_name'], $dest)) {
                $pimg = 'uploads/' . $fname;
            } else {
                $message = 'Failed to save image. Check folder permissions.';
                $msgType = 'error';
            }
        }
    }

    if (empty($message) && $pname !== '' && $pprice >= 0) {
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, category, image_url, in_stock) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("ssdssi", $pname, $pdesc, $pprice, $pcat, $pimg, $pstock);
        if ($stmt->execute()) {
            $message = 'Product added successfully!';
            $msgType = 'success';
        } else {
            $message = 'Failed to add product.';
            $msgType = 'error';
        }
        $stmt->close();
    } elseif (empty($message)) {
        $message = 'Product name and price are required.';
        $msgType = 'error';
    }
}

// Toggle product stock
if (isset($_GET['toggle_stock']) && is_numeric($_GET['toggle_stock'])) {
    $pid = (int)$_GET['toggle_stock'];
    $conn->query("UPDATE products SET in_stock = NOT in_stock WHERE id = $pid");
    $message = 'Product stock status updated.';
    $msgType = 'success';
}

// Delete product
if (isset($_GET['delete_product']) && is_numeric($_GET['delete_product'])) {
    $pid  = (int)$_GET['delete_product'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $stmt->close();
    $message = 'Product deleted.';
    $msgType = 'success';
}

// Stats
$total_users    = $conn->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc()['c'];
$total_builds   = $conn->query("SELECT COUNT(*) AS c FROM builds")->fetch_assoc()['c'];
$total_admins   = $conn->query("SELECT COUNT(*) AS c FROM users WHERE role = 'admin'")->fetch_assoc()['c'];
$avg_price      = $conn->query("SELECT AVG(total_price) AS a FROM builds")->fetch_assoc()['a'];
$total_products = $conn->query("SELECT COUNT(*) AS c FROM products")->fetch_assoc()['c'];

// Users
$users_result = $conn->query("SELECT u.*, COUNT(b.id) AS build_count FROM users u LEFT JOIN builds b ON b.user_id = u.id GROUP BY u.id ORDER BY u.created_at DESC");
$users = $users_result->fetch_all(MYSQLI_ASSOC);

// All builds
$builds_result = $conn->query("SELECT b.*, u.username FROM builds b JOIN users u ON u.id = b.user_id ORDER BY b.created_at DESC LIMIT 50");
$builds = $builds_result->fetch_all(MYSQLI_ASSOC);

// Products
$products_result = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $products_result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel — PC Builder</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav>
    <div class="nav-brand">⚡ <span>PC BUILDER</span></div>
    <div class="nav-links">
        <a href="admin.php" class="active">Dashboard</a>
        <a href="products.php" target="_blank">🛒 Shop</a>
    </div>
    <div class="nav-user">
        <span style="color:var(--accent2);font-weight:700;">⚙ ADMIN</span>
        <span>👤 <?= htmlspecialchars($_SESSION['username']) ?></span>
        <a href="logout.php" class="btn btn-outline btn-sm">Sign Out</a>
    </div>
</nav>

<div class="container">

    <?php if ($message): ?>
        <div class="alert alert-<?= $msgType ?>" style="margin-bottom:1.5rem;">
            <?= $msgType === 'success' ? '✓' : '⚠' ?> <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="page-header">
        <h1>⚙ Admin Dashboard</h1>
        <p>Manage users, builds, and platform statistics</p>
    </div>

    <!-- STATS -->
    <div class="grid-4" style="margin-bottom:2rem;">
        <div class="stat-card">
            <div class="stat-value"><?= $total_users ?></div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $total_builds ?></div>
            <div class="stat-label">Total Builds</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $total_products ?></div>
            <div class="stat-label">Products</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">$<?= number_format($avg_price ?? 0, 0) ?></div>
            <div class="stat-label">Avg Build Price</div>
        </div>
    </div>

    <!-- TABS -->
    <div class="tabs">
        <button class="tab-btn active" onclick="switchTab('products', this)">🛒 Products (<?= $total_products ?>)</button>
        <button class="tab-btn" onclick="switchTab('users', this)">👥 Users (<?= $total_users ?>)</button>
        <button class="tab-btn" onclick="switchTab('builds', this)">🖥 Builds (<?= $total_builds ?>)</button>
    </div>

    <!-- PRODUCTS TAB -->
    <div id="tab-products" class="tab-content active">
        <div class="grid-2" style="align-items:start;">

            <!-- Add Product Form -->
            <div class="card">
                <div class="card-header">
                    <h3>➕ Add New Product</h3>
                </div>
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Product Name *</label>
                        <input type="text" name="p_name" placeholder="e.g. ASUS ROG RTX 4070" required>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="p_category">
                            <option value="">— Select Category —</option>
                            <option value="CPU">CPU</option>
                            <option value="GPU">GPU</option>
                            <option value="RAM">RAM</option>
                            <option value="Storage">Storage</option>
                            <option value="Motherboard">Motherboard</option>
                            <option value="PSU">PSU</option>
                            <option value="Case">Case</option>
                            <option value="Monitor">Monitor</option>
                            <option value="Keyboard">Keyboard</option>
                            <option value="Mouse">Mouse</option>
                            <option value="Headset">Headset</option>
                            <option value="Prebuilt">Prebuilt PC</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Price (USD) *</label>
                        <input type="number" name="p_price" placeholder="0.00" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="p_desc" rows="3" placeholder="Product details, specs..."
                            style="width:100%;padding:10px 14px;background:var(--bg-primary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:0.9rem;resize:vertical;outline:none;font-family:inherit;"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Product Image <span style="color:var(--text-secondary);font-weight:400;">(optional · JPG/PNG/WEBP, max 5MB)</span></label>
                        <input type="file" name="p_image_file" id="p_image_file" accept="image/*"
                               onchange="previewImg(this)"
                               style="width:100%;padding:10px;background:var(--bg-primary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:0.85rem;cursor:pointer;">
                        <div id="img-preview" style="display:none;margin-top:10px;text-align:center;">
                            <img id="img-thumb" src="" alt="Preview"
                                 style="max-width:100%;max-height:160px;border-radius:8px;border:1px solid var(--border);object-fit:cover;">
                            <div style="font-size:0.75rem;color:var(--text-secondary);margin-top:4px;">Preview</div>
                        </div>
                    </div>
                    <div class="form-group" style="display:flex;align-items:center;gap:10px;">
                        <input type="checkbox" name="p_instock" id="p_instock" value="1" checked style="width:auto;accent-color:var(--accent);">
                        <label for="p_instock" style="text-transform:none;font-size:0.9rem;cursor:pointer;">In Stock</label>
                    </div>
                    <button type="submit" name="add_product" class="btn btn-primary" style="width:100%;justify-content:center;">
                        ➕ Add Product
                    </button>
                </form>
            </div>

            <!-- Product List -->
            <div class="card">
                <div class="card-header">
                    <h3>📦 All Products</h3>
                    <a href="products.php" target="_blank" class="btn btn-outline btn-sm">View Shop →</a>
                </div>
                <?php if (empty($products)): ?>
                <div class="empty-state">
                    <div class="empty-icon">📦</div>
                    <h3>No products yet</h3>
                    <p>Add your first product using the form.</p>
                </div>
                <?php else: ?>
                <div style="display:flex;flex-direction:column;gap:0.8rem;max-height:600px;overflow-y:auto;">
                    <?php foreach ($products as $p):
                        $waMsg = urlencode("Hi! I'm interested in buying: *" . $p['name'] . "* (Price: $" . number_format($p['price'], 2) . "). Can you provide more details?");
                        $waUrl = "https://wa.me/" . WHATSAPP_NUMBER . "?text={$waMsg}";
                    ?>
                    <div style="background:var(--bg-primary);border:1px solid var(--border);border-radius:10px;padding:1rem;display:flex;gap:1rem;align-items:center;">
                        <?php if (!empty($p['image_url'])): ?>
                            <img src="<?= htmlspecialchars($p['image_url']) ?>"
                                 style="width:56px;height:56px;object-fit:cover;border-radius:8px;flex-shrink:0;"
                                 onerror="this.style.display='none'">
                        <?php endif; ?>
                        <div style="flex:1;min-width:0;">
                            <div style="font-weight:700;font-size:0.9rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                <?= htmlspecialchars($p['name']) ?>
                            </div>
                            <div style="font-size:0.75rem;color:var(--text-secondary);margin-top:2px;">
                                <?= htmlspecialchars($p['category'] ?: 'Uncategorised') ?> ·
                                <span style="color:var(--accent);font-weight:700;">$<?= number_format($p['price'], 2) ?></span>
                            </div>
                            <div style="margin-top:4px;">
                                <span style="font-size:0.72rem;padding:2px 8px;border-radius:12px;
                                    <?= $p['in_stock'] ? 'background:rgba(0,196,140,0.12);color:#34d399;border:1px solid rgba(0,196,140,0.3);' : 'background:rgba(233,69,96,0.12);color:#f87171;border:1px solid rgba(233,69,96,0.3);' ?>">
                                    <?= $p['in_stock'] ? '✓ In Stock' : '✗ Out of Stock' ?>
                                </span>
                            </div>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:5px;flex-shrink:0;">
                            <a href="?toggle_stock=<?= $p['id'] ?>" class="btn btn-outline btn-sm"
                               style="font-size:0.72rem;padding:4px 10px;">
                                <?= $p['in_stock'] ? '🚫 Hide' : '✓ Show' ?>
                            </a>
                            <a href="<?= $waUrl ?>" target="_blank" class="btn btn-sm"
                               style="background:#25d366;color:#fff;font-size:0.72rem;padding:4px 10px;">
                                💬 Test WA
                            </a>
                            <a href="?delete_product=<?= $p['id'] ?>"
                               onclick="return confirm('Delete this product?')"
                               class="btn btn-danger btn-sm" style="font-size:0.72rem;padding:4px 10px;">
                                🗑 Del
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <!-- USERS TAB -->
    <div id="tab-users" class="tab-content">
        <div class="card">
            <div class="card-header">
                <h3>👥 Registered Users</h3>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Builds</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                        <tr>
                            <td style="color:var(--text-secondary);"><?= $u['id'] ?></td>
                            <td>
                                <strong><?= htmlspecialchars($u['username']) ?></strong>
                                <?php if ($u['id'] == $_SESSION['user_id']): ?>
                                    <span style="font-size:0.7rem;color:var(--accent);margin-left:4px;">(you)</span>
                                <?php endif; ?>
                            </td>
                            <td style="color:var(--text-secondary);"><?= htmlspecialchars($u['email']) ?></td>
                            <td>
                                <span class="badge badge-<?= $u['role'] ?>"><?= ucfirst($u['role']) ?></span>
                            </td>
                            <td><?= $u['build_count'] ?></td>
                            <td style="color:var(--text-secondary);"><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
                            <td>
                                <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                    <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                        <input type="hidden" name="new_role" value="<?= $u['role'] === 'admin' ? 'user' : 'admin' ?>">
                                        <button type="submit" name="toggle_role"
                                                class="btn btn-outline btn-sm"
                                                onclick="return confirm('Change role to <?= $u['role'] === 'admin' ? 'user' : 'admin' ?>?')">
                                            <?= $u['role'] === 'admin' ? '👤 Make User' : '⚙ Make Admin' ?>
                                        </button>
                                    </form>
                                    <a href="?delete_user=<?= $u['id'] ?>"
                                       onclick="return confirm('Delete user <?= htmlspecialchars($u['username']) ?> and all their builds?')"
                                       class="btn btn-danger btn-sm">🗑 Delete</a>
                                    <?php else: ?>
                                    <span style="font-size:0.8rem;color:var(--text-secondary);">—</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- BUILDS TAB -->
    <div id="tab-builds" class="tab-content">
        <div class="card">
            <div class="card-header">
                <h3>🖥 All PC Builds</h3>
                <span style="font-size:0.8rem;color:var(--text-secondary);">Latest 50</span>
            </div>
            <?php if (empty($builds)): ?>
            <div class="empty-state">
                <div class="empty-icon">🖥</div>
                <h3>No builds yet</h3>
                <p>Users haven't saved any builds yet.</p>
            </div>
            <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Build Name</th>
                            <th>User</th>
                            <th>CPU</th>
                            <th>GPU</th>
                            <th>RAM</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($builds as $b): ?>
                        <tr>
                            <td style="color:var(--text-secondary);"><?= $b['id'] ?></td>
                            <td><strong><?= htmlspecialchars($b['build_name']) ?></strong></td>
                            <td><span class="badge badge-user"><?= htmlspecialchars($b['username']) ?></span></td>
                            <td style="font-size:0.8rem;"><?= htmlspecialchars($b['cpu'] ?: '—') ?></td>
                            <td style="font-size:0.8rem;"><?= htmlspecialchars($b['gpu'] ?: '—') ?></td>
                            <td style="font-size:0.8rem;"><?= htmlspecialchars($b['ram'] ?: '—') ?></td>
                            <td style="color:var(--accent);font-weight:700;">$<?= number_format($b['total_price'], 0) ?></td>
                            <td style="color:var(--text-secondary);font-size:0.8rem;"><?= date('M d, Y', strtotime($b['created_at'])) ?></td>
                            <td>
                                <a href="?delete_build=<?= $b['id'] ?>"
                                   onclick="return confirm('Delete this build?')"
                                   class="btn btn-danger btn-sm">🗑</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<script>
function previewImg(input) {
    var preview = document.getElementById('img-preview');
    var thumb   = document.getElementById('img-thumb');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            thumb.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}

function switchTab(tab, btn) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    btn.classList.add('active');
}
if (window.location.hash === '#builds') {
    document.querySelectorAll('.tab-btn')[1].click();
}
</script>
</body>
</html>
