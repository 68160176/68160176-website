<?php
session_start();
$_SESSION = array(); // ล้างตัวแปร Session ทั้งหมด

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy(); // ทำลาย Session[cite: 17]
header("Location: login.php"); // ส่งกลับไปหน้า login[cite: 17]
exit;
?>