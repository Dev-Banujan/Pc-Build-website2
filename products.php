<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/config.php';

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

$search      = trim($_GET['search']   ?? '');
$filterCat   = trim($_GET['category'] ?? '');
$wa          = WHATSAPP_NUMBER;
$isLoggedIn  = isLoggedIn();

// All categories in display order
$allCategories = [
    'CPU'         => '🧠',
    'GPU'         => '🎮',
    'RAM'         => '💾',
    'HDD'         => '🖴',
    'SSD'         => '💿',
    'Motherboard' => '🔌',
    'Monitor'     => '🖥',
    'PC'          => '💻',
    'Keyboard'    => '⌨',
    'Mouse'       => '🖱',
    'Cooler'      => '❄',
    'PSU'         => '⚡',
    'Casing'      => '📦',
];

// Fetch products grouped by category
$grouped = [];
if ($search !== '' || $filterCat !== '') {
    // Search / filter mode — flat list
    $where  = ["in_stock = 1"];
    $params = [];
    $types  = '';
    if ($search !== '') {
        $where[]  = "(name LIKE ? OR description LIKE ?)";
        $like     = "%$search%";
        $params[] = $like;
        $params[] = $like;
        $types   .= 'ss';
    }
    if ($filterCat !== '') {
        $where[]  = "category = ?";
        $params[] = $filterCat;
        $types   .= 's';
    }
    $sql  = "SELECT * FROM products WHERE " . implode(' AND ', $where) . " ORDER BY category, created_at DESC";
    $stmt = $conn->prepare($sql);
    if ($params) { $stmt->bind_param($types, ...$params); }
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    foreach ($rows as $row) { $grouped[$row['category']][] = $row; }
} else {
    // Full shop — show 5 per category
    $cats = array_keys($allCategories);
    foreach ($cats as $cat) {
        $escaped = $conn->real_escape_string($cat);
        $result  = $conn->query("SELECT * FROM products WHERE category='$escaped' AND in_stock=1 ORDER BY created_at ASC LIMIT 5");
        $rows    = $result->fetch_all(MYSQLI_ASSOC);
        if (!empty($rows)) { $grouped[$cat] = $rows; }
    }
}

// Category counts for filter pills
$catCounts = [];
$countResult = $conn->query("SELECT category, COUNT(*) AS c FROM products WHERE in_stock=1 GROUP BY category");
while ($row = $countResult->fetch_assoc()) { $catCounts[$row['category']] = $row['c']; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop — PC Builder</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* ── Category nav pills ── */
        .cat-nav {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }
        .cat-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: 1px solid var(--border);
            background: var(--bg-card);
            color: var(--text-secondary);
            transition: all 0.18s;
        }
        .cat-pill:hover { border-color: var(--accent); color: var(--accent); }
        .cat-pill.active { background: var(--accent); border-color: var(--accent); color: #000; font-weight: 700; }
        .cat-pill .count {
            background: rgba(0,0,0,0.15);
            border-radius: 10px;
            padding: 1px 7px;
            font-size: 0.72rem;
        }

        /* ── Category section ── */
        .cat-section { margin-bottom: 3rem; }
        .cat-heading {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.2rem;
            padding-bottom: 0.8rem;
            border-bottom: 2px solid var(--border);
        }
        .cat-heading h2 {
            font-size: 1.2rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .cat-heading .see-all {
            margin-left: auto;
            font-size: 0.8rem;
            color: var(--accent);
        }

        /* ── Products grid ── */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1rem;
        }
        @media (max-width: 1200px) { .products-grid { grid-template-columns: repeat(4, 1fr); } }
        @media (max-width: 900px)  { .products-grid { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 600px)  { .products-grid { grid-template-columns: repeat(2, 1fr); } }

        /* ── Product card ── */
        .product-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s, border-color 0.2s, box-shadow 0.2s;
        }
        .product-card:hover {
            transform: translateY(-4px);
            border-color: var(--accent);
            box-shadow: 0 10px 28px rgba(0,212,255,0.1);
        }
        .product-img {
            width: 100%;
            height: 160px;
            background: var(--bg-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            overflow: hidden;
            flex-shrink: 0;
        }
        .product-img img {
            width: 100%; height: 100%;
            object-fit: cover;
        }
        .product-body {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .product-name {
            font-size: 0.88rem;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 6px;
            color: var(--text-primary);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .product-desc {
            font-size: 0.76rem;
            color: var(--text-secondary);
            line-height: 1.4;
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 10px;
        }
        .product-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
            flex-wrap: wrap;
            margin-top: auto;
        }
        .product-price {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--accent);
        }
        .btn-whatsapp {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #25d366;
            color: #fff;
            padding: 7px 12px;
            border-radius: 7px;
            font-size: 0.78rem;
            font-weight: 700;
            text-decoration: none;
            transition: background 0.2s, transform 0.15s;
            white-space: nowrap;
        }
        .btn-whatsapp:hover { background: #1ebe5d; transform: translateY(-1px); color: #fff; }

        /* ── Search bar ── */
        .search-wrap {
            display: flex;
            gap: 10px;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        .search-wrap input {
            flex: 1; min-width: 200px;
            padding: 11px 16px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 0.9rem;
            outline: none;
        }
        .search-wrap input:focus { border-color: var(--accent); }

        /* ── Back to top ── */
        .back-to-top {
            position: fixed;
            bottom: 28px;
            right: 28px;
            background: var(--accent);
            color: #000;
            border: none;
            border-radius: 50%;
            width: 44px; height: 44px;
            font-size: 1.2rem;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(0,212,255,0.3);
            z-index: 200;
            transition: opacity 0.2s;
        }
    </style>
</head>
<body>

<nav>
    <div class="nav-brand">⚡ <span>PC BUILDER</span></div>
    <div class="nav-links">
        <a href="products.php" class="active">🛒 Shop</a>
        <?php if ($isLoggedIn): ?>
            <a href="user.php">Builder</a>
        <?php endif; ?>
    </div>
    <div class="nav-user">
        <?php if ($isLoggedIn): ?>
            <span>👤 <?= htmlspecialchars($_SESSION['username']) ?></span>
            <?php if (isAdmin()): ?>
                <a href="admin.php" class="btn btn-outline btn-sm">⚙ Admin</a>
            <?php endif; ?>
            <a href="logout.php" class="btn btn-outline btn-sm">Sign Out</a>
        <?php else: ?>
            <a href="login.php" class="btn btn-outline btn-sm">Sign In</a>
            <a href="register.php" class="btn btn-primary btn-sm">Register</a>
        <?php endif; ?>
    </div>
</nav>

<div class="container">

    <div class="page-header" style="margin-bottom:1.2rem;">
        <h1>🛒 PC Components Shop</h1>
        <p>Browse by category — click <strong style="color:#25d366;">Order via WhatsApp</strong> to buy</p>
    </div>

    <!-- Search -->
    <form method="GET" class="search-wrap">
        <input type="text" name="search" placeholder="🔍 Search products..."
               value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn btn-primary">Search</button>
        <?php if ($search || $filterCat): ?>
            <a href="products.php" class="btn btn-outline">✕ Clear</a>
        <?php endif; ?>
    </form>

    <!-- Category pills -->
    <div class="cat-nav">
        <a href="products.php" class="cat-pill <?= (!$filterCat && !$search) ? 'active' : '' ?>">
            All <span class="count"><?= array_sum($catCounts) ?></span>
        </a>
        <?php foreach ($allCategories as $cat => $icon): if (!isset($catCounts[$cat])) continue; ?>
        <a href="products.php?category=<?= urlencode($cat) ?>"
           class="cat-pill <?= $filterCat === $cat ? 'active' : '' ?>">
            <?= $icon ?> <?= $cat ?> <span class="count"><?= $catCounts[$cat] ?></span>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- Results info when filtering -->
    <?php if ($search || $filterCat): ?>
        <p style="font-size:0.85rem;color:var(--text-secondary);margin-bottom:1.5rem;">
            <?= array_sum(array_map('count', $grouped)) ?> product(s) found
            <?= $search ? ' for "<strong style="color:var(--text-primary);">'.htmlspecialchars($search).'</strong>"' : '' ?>
            <?= $filterCat ? ' in <strong style="color:var(--accent);">'.htmlspecialchars($filterCat).'</strong>' : '' ?>
        </p>
    <?php endif; ?>

    <!-- Products by category -->
    <?php if (empty($grouped)): ?>
        <div class="card">
            <div class="empty-state">
                <div class="empty-icon">🔍</div>
                <h3>No products found</h3>
                <p>Try a different search or <a href="products.php">view all products</a>.</p>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($grouped as $cat => $prods):
            $icon = isset($allCategories[$cat]) ? $allCategories[$cat] : '📦';
        ?>
        <div class="cat-section" id="cat-<?= strtolower($cat) ?>">
            <div class="cat-heading">
                <h2><?= $icon ?> <?= htmlspecialchars($cat) ?></h2>
                <a href="products.php?category=<?= urlencode($cat) ?>" class="see-all">
                    View all →
                </a>
            </div>
            <div class="products-grid">
                <?php foreach ($prods as $p):
                    $waMsg = urlencode("Hi! I want to buy: *" . $p['name'] . "* — Price: $" . number_format($p['price'], 2) . ". Please confirm availability.");
                    $waUrl = "https://wa.me/{$wa}?text={$waMsg}";
                ?>
                <div class="product-card">
                    <div class="product-img">
                        <?php if (!empty($p['image_url'])): ?>
                            <img src="<?= htmlspecialchars($p['image_url']) ?>"
                                 alt="<?= htmlspecialchars($p['name']) ?>"
                                 onerror="this.parentElement.innerHTML='<?= $icon ?>'">
                        <?php else: ?>
                            <?= $icon ?>
                        <?php endif; ?>
                    </div>
                    <div class="product-body">
                        <div class="product-name"><?= htmlspecialchars($p['name']) ?></div>
                        <?php if ($p['description']): ?>
                            <div class="product-desc"><?= htmlspecialchars($p['description']) ?></div>
                        <?php endif; ?>
                        <div class="product-footer">
                            <span class="product-price">$<?= number_format($p['price'], 2) ?></span>
                            <a href="<?= $waUrl ?>" target="_blank" class="btn-whatsapp">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="#fff">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                Order
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

<button class="back-to-top" id="btt" onclick="window.scrollTo({top:0,behavior:'smooth'})">↑</button>

<script>
var btt = document.getElementById('btt');
window.addEventListener('scroll', function() {
    btt.style.display = window.scrollY > 400 ? 'flex' : 'none';
});
</script>
</body>
</html>
