<?php
require_once 'includes/auth.php';

if (isLoggedIn()) {
    if (isAdmin()) {
        header('Location: admin.php');
    } else {
        header('Location: user.php');
    }
} else {
    header('Location: login.php');
}
exit();
?>
