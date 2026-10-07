<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'pcbuilder');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die('<div style="font-family:sans-serif;padding:40px;background:#1a1a2e;color:#e94560;text-align:center;">
        <h2>Database Connection Failed</h2>
        <p>' . $conn->connect_error . '</p>
        <p style="color:#aaa;">Make sure XAMPP MySQL is running and you have run <strong>setup.sql</strong></p>
    </div>');
}

$conn->set_charset("utf8");
?>
