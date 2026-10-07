<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
requireLogin();

$user_id  = $_SESSION['user_id'];
$username = $_SESSION['username'];
$message  = '';
$msgType  = '';

// Save Build
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_build'])) {
    $build_name        = trim($_POST['build_name'] ?? 'My Build');
    $cpu               = $_POST['cpu'] ?? '';
    $cpu_price         = (float)($_POST['cpu_price'] ?? 0);
    $gpu               = $_POST['gpu'] ?? '';
    $gpu_price         = (float)($_POST['gpu_price'] ?? 0);
    $ram               = $_POST['ram'] ?? '';
    $ram_price         = (float)($_POST['ram_price'] ?? 0);
    $storage           = $_POST['storage'] ?? '';
    $storage_price     = (float)($_POST['storage_price'] ?? 0);
    $motherboard       = $_POST['motherboard'] ?? '';
    $motherboard_price = (float)($_POST['motherboard_price'] ?? 0);
    $psu               = $_POST['psu'] ?? '';
    $psu_price         = (float)($_POST['psu_price'] ?? 0);
    $pc_case           = $_POST['pc_case'] ?? '';
    $case_price        = (float)($_POST['case_price'] ?? 0);
    $total             = $cpu_price + $gpu_price + $ram_price + $storage_price + $motherboard_price + $psu_price + $case_price;

    if (empty($build_name)) $build_name = 'My Build';

    $stmt = $conn->prepare("INSERT INTO builds (user_id, build_name, cpu, cpu_price, gpu, gpu_price, ram, ram_price, storage, storage_price, motherboard, motherboard_price, psu, psu_price, pc_case, case_price, total_price) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param("issdsdsdsdsdsdsdd",
        $user_id, $build_name,
        $cpu, $cpu_price,
        $gpu, $gpu_price,
        $ram, $ram_price,
        $storage, $storage_price,
        $motherboard, $motherboard_price,
        $psu, $psu_price,
        $pc_case, $case_price,
        $total
    );

    if ($stmt->execute()) {
        $message = 'Build saved successfully!';
        $msgType = 'success';
    } else {
        $message = 'Failed to save build.';
        $msgType = 'error';
    }
    $stmt->close();
}

// Delete Build
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $bid  = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM builds WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $bid, $user_id);
    $stmt->execute();
    $stmt->close();
    $message = 'Build deleted.';
    $msgType = 'success';
}

// Get user's builds
$builds_result = $conn->query("SELECT * FROM builds WHERE user_id = $user_id ORDER BY created_at DESC");
$builds = $builds_result->fetch_all(MYSQLI_ASSOC);

// Component data
$components = [
    'cpu' => [
        'label' => 'CPU', 'icon' => '🧠',
        'options' => [
            ''                              => ['name' => '— Select CPU —', 'price' => 0],
            'Intel Core i3-13100'           => ['name' => 'Intel Core i3-13100', 'price' => 139],
            'Intel Core i5-13600K'          => ['name' => 'Intel Core i5-13600K', 'price' => 289],
            'Intel Core i7-13700K'          => ['name' => 'Intel Core i7-13700K', 'price' => 409],
            'Intel Core i9-13900K'          => ['name' => 'Intel Core i9-13900K', 'price' => 589],
            'AMD Ryzen 5 7600'              => ['name' => 'AMD Ryzen 5 7600', 'price' => 229],
            'AMD Ryzen 7 7700X'             => ['name' => 'AMD Ryzen 7 7700X', 'price' => 349],
            'AMD Ryzen 9 7900X'             => ['name' => 'AMD Ryzen 9 7900X', 'price' => 449],
            'AMD Ryzen 9 7950X'             => ['name' => 'AMD Ryzen 9 7950X', 'price' => 699],
        ]
    ],
    'gpu' => [
        'label' => 'GPU', 'icon' => '🎮',
        'options' => [
            ''                              => ['name' => '— Select GPU —', 'price' => 0],
            'NVIDIA RTX 3060'               => ['name' => 'NVIDIA RTX 3060 12GB', 'price' => 329],
            'NVIDIA RTX 3070'               => ['name' => 'NVIDIA RTX 3070 8GB', 'price' => 449],
            'NVIDIA RTX 4060'               => ['name' => 'NVIDIA RTX 4060 8GB', 'price' => 299],
            'NVIDIA RTX 4070'               => ['name' => 'NVIDIA RTX 4070 12GB', 'price' => 599],
            'NVIDIA RTX 4080'               => ['name' => 'NVIDIA RTX 4080 16GB', 'price' => 1199],
            'NVIDIA RTX 4090'               => ['name' => 'NVIDIA RTX 4090 24GB', 'price' => 1599],
            'AMD RX 6700 XT'                => ['name' => 'AMD RX 6700 XT 12GB', 'price' => 329],
            'AMD RX 7900 XTX'               => ['name' => 'AMD RX 7900 XTX 24GB', 'price' => 999],
        ]
    ],
    'ram' => [
        'label' => 'RAM', 'icon' => '💾',
        'options' => [
            ''                              => ['name' => '— Select RAM —', 'price' => 0],
            '8GB DDR4-3200'                 => ['name' => '8GB DDR4-3200', 'price' => 29],
            '16GB DDR4-3200'                => ['name' => '16GB DDR4-3200', 'price' => 49],
            '32GB DDR4-3600'                => ['name' => '32GB DDR4-3600', 'price' => 89],
            '16GB DDR5-5600'                => ['name' => '16GB DDR5-5600', 'price' => 79],
            '32GB DDR5-6000'                => ['name' => '32GB DDR5-6000', 'price' => 129],
            '64GB DDR5-5600'                => ['name' => '64GB DDR5-5600', 'price' => 219],
        ]
    ],
    'storage' => [
        'label' => 'Storage', 'icon' => '💿',
        'options' => [
            ''                              => ['name' => '— Select Storage —', 'price' => 0],
            '500GB NVMe SSD'                => ['name' => '500GB NVMe SSD', 'price' => 49],
            '1TB NVMe SSD'                  => ['name' => '1TB NVMe SSD', 'price' => 79],
            '2TB NVMe SSD'                  => ['name' => '2TB NVMe SSD', 'price' => 139],
            '1TB SATA SSD'                  => ['name' => '1TB SATA SSD', 'price' => 69],
            '2TB HDD'                       => ['name' => '2TB HDD 7200rpm', 'price' => 49],
            '4TB HDD'                       => ['name' => '4TB HDD 7200rpm', 'price' => 79],
        ]
    ],
    'motherboard' => [
        'label' => 'Motherboard', 'icon' => '🔌',
        'options' => [
            ''                              => ['name' => '— Select Motherboard —', 'price' => 0],
            'ASUS B660M-A'                  => ['name' => 'ASUS B660M-A (LGA1700)', 'price' => 129],
            'MSI Z790 Tomahawk'             => ['name' => 'MSI Z790 Tomahawk (LGA1700)', 'price' => 239],
            'ASUS ROG Z790 Hero'            => ['name' => 'ASUS ROG Z790 Hero (LGA1700)', 'price' => 499],
            'MSI B650 Tomahawk'             => ['name' => 'MSI B650 Tomahawk (AM5)', 'price' => 199],
            'ASUS ROG X670E Gene'           => ['name' => 'ASUS ROG X670E Gene (AM5)', 'price' => 429],
            'Gigabyte X670E Aorus'          => ['name' => 'Gigabyte X670E Aorus (AM5)', 'price' => 349],
        ]
    ],
    'psu' => [
        'label' => 'PSU', 'icon' => '⚡',
        'options' => [
            ''                              => ['name' => '— Select PSU —', 'price' => 0],
            'Corsair 550W 80+ Bronze'       => ['name' => 'Corsair 550W 80+ Bronze', 'price' => 69],
            'Seasonic 650W 80+ Gold'        => ['name' => 'Seasonic 650W 80+ Gold', 'price' => 99],
            'EVGA 750W 80+ Gold'            => ['name' => 'EVGA 750W 80+ Gold', 'price' => 109],
            'Corsair 850W 80+ Gold'         => ['name' => 'Corsair 850W 80+ Gold', 'price' => 139],
            'Seasonic 1000W 80+ Platinum'   => ['name' => 'Seasonic 1000W 80+ Platinum', 'price' => 199],
        ]
    ],
    'pc_case' => [
        'label' => 'Case', 'icon' => '🖥',
        'options' => [
            ''                              => ['name' => '— Select Case —', 'price' => 0],
            'NZXT H510'                     => ['name' => 'NZXT H510 Mid Tower', 'price' => 79],
            'Fractal Design Pop Air'        => ['name' => 'Fractal Design Pop Air', 'price' => 89],
            'Lian Li PC-O11 Dynamic'        => ['name' => 'Lian Li O11 Dynamic', 'price' => 149],
            'Corsair 4000D Airflow'         => ['name' => 'Corsair 4000D Airflow', 'price' => 109],
            'be quiet! Pure Base 500DX'     => ['name' => 'be quiet! Pure Base 500DX', 'price' => 119],
        ]
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PC Builder — My Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav>
    <div class="nav-brand">⚡ <span>PC BUILDER</span></div>
    <div class="nav-links">
        <a href="products.php">🛒 Shop</a>
        <a href="user.php" class="active">Builder</a>
        <a href="user.php#my-builds">My Builds</a>
    </div>
    <div class="nav-user">
        <span>👤 <?= htmlspecialchars($username) ?></span>
        <a href="logout.php" class="btn btn-outline btn-sm">Sign Out</a>
    </div>
</nav>

<div class="container">

    <?php if ($message): ?>
        <div class="alert alert-<?= $msgType ?>" style="margin-bottom:1.5rem;">
            <?= $msgType === 'success' ? '✓' : '⚠' ?> <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- TABS -->
    <div class="tabs">
        <button class="tab-btn active" onclick="switchTab('builder', this)">🔧 PC Builder</button>
        <button class="tab-btn" onclick="switchTab('my-builds', this)">📋 My Builds (<?= count($builds) ?>)</button>
    </div>

    <!-- BUILDER TAB -->
    <div id="tab-builder" class="tab-content active">
        <div class="page-header">
            <h1>Build Your PC</h1>
            <p>Select your components and save your custom build</p>
        </div>

        <form method="POST" action="">
            <div class="builder-grid">
                <!-- Components -->
                <div class="card">
                    <div class="card-header">
                        <h3>🔧 Select Components</h3>
                    </div>
                    <div class="component-grid">
                        <?php foreach ($components as $key => $comp): ?>
                        <div class="component-card">
                            <div class="comp-icon"><?= $comp['icon'] ?></div>
                            <label><?= $comp['label'] ?></label>
                            <select name="<?= $key ?>" id="sel-<?= $key ?>"
                                    onchange="updateSummary('<?= $key ?>', this)">
                                <?php foreach ($comp['options'] as $val => $opt): ?>
                                    <option value="<?= htmlspecialchars($val) ?>"
                                            data-price="<?= $opt['price'] ?>">
                                        <?= htmlspecialchars($opt['name']) ?>
                                        <?= $opt['price'] > 0 ? ' ($' . $opt['price'] . ')' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" name="<?= $key ?>_price" id="price-<?= $key ?>" value="0">
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Summary Panel -->
                <div class="summary-panel">
                    <h3>📊 Build Summary</h3>

                    <input type="text" name="build_name" class="build-name-input"
                           placeholder="Name your build (e.g. Gaming Beast)" value="My Build">

                    <?php foreach ($components as $key => $comp): ?>
                    <div class="summary-item" id="sum-<?= $key ?>">
                        <span class="comp-name"><?= $comp['icon'] ?> <?= $comp['label'] ?></span>
                        <span class="comp-val" id="sum-val-<?= $key ?>">—</span>
                    </div>
                    <?php endforeach; ?>

                    <div class="total-row">
                        <span class="total-label">Total</span>
                        <span class="total-price" id="total-price">$0</span>
                    </div>

                    <button type="submit" name="save_build" class="btn btn-primary"
                            style="width:100%;justify-content:center;margin-top:1.2rem;">
                        💾 Save This Build
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- MY BUILDS TAB -->
    <div id="tab-my-builds" class="tab-content">
        <div class="page-header">
            <h1>My Saved Builds</h1>
            <p>All your custom PC configurations</p>
        </div>

        <?php if (empty($builds)): ?>
        <div class="card">
            <div class="empty-state">
                <div class="empty-icon">🖥</div>
                <h3>No builds yet</h3>
                <p>Head over to the PC Builder tab and create your first build!</p>
            </div>
        </div>
        <?php else: ?>
        <div class="builds-list">
            <?php foreach ($builds as $b): ?>
            <div class="build-item">
                <div class="build-info">
                    <h4>🖥 <?= htmlspecialchars($b['build_name']) ?></h4>
                    <p>
                        <?= htmlspecialchars($b['cpu'] ?: '—') ?> ·
                        <?= htmlspecialchars($b['gpu'] ?: '—') ?> ·
                        <?= htmlspecialchars($b['ram'] ?: '—') ?>
                    </p>
                    <p style="margin-top:2px;font-size:0.75rem;">Saved: <?= date('M d, Y', strtotime($b['created_at'])) ?></p>
                </div>
                <div style="display:flex;align-items:center;gap:1rem;">
                    <span class="build-price">$<?= number_format($b['total_price'], 2) ?></span>
                    <a href="?delete=<?= $b['id'] ?>"
                       onclick="return confirm('Delete this build?')"
                       class="btn btn-danger btn-sm">🗑 Delete</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

</div>

<script>
const prices = {};
let total = 0;

function updateSummary(key, sel) {
    const opt   = sel.options[sel.selectedIndex];
    const price = parseFloat(opt.dataset.price) || 0;
    const name  = opt.value;

    prices[key] = price;
    document.getElementById('price-' + key).value = price;

    const sumVal = document.getElementById('sum-val-' + key);
    sumVal.textContent = name ? name.split(' ($')[0] + (price > 0 ? ' — $' + price : '') : '—';

    total = Object.values(prices).reduce((a, b) => a + b, 0);
    document.getElementById('total-price').textContent = '$' + total.toLocaleString('en-US', {minimumFractionDigits: 0});
}

function switchTab(tab, btn) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    btn.classList.add('active');
}

// Check URL hash on load
if (window.location.hash === '#my-builds') {
    document.querySelectorAll('.tab-btn')[1].click();
}
</script>
</body>
</html>
