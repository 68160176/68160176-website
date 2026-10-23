<?php
session_start();
require_once 'config/db.php'; // ไฟล์เชื่อมต่อ Database ของเรา[cite: 5]

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        // ใช้ Prepared Statement เพื่อป้องกัน SQL Injection[cite: 8, 18]
        $sql = "SELECT id, password_hash FROM users WHERE email = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        // ตรวจสอบว่ามีผู้ใช้ และรหัสผ่านตรงกับ Hash หรือไม่[cite: 11, 14]
        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true); // ป้องกัน Session Fixation[cite: 14, 18]
            $_SESSION['user_id'] = $user['id']; // บันทึกสถานะว่า Login แล้ว[cite: 11, 14]
            header("Location: skills.php"); // ส่งไปหน้าหลักของโปรเจกต์[cite: 5, 14]
            exit;
        } else {
            $error = "Email หรือ Password ไม่ถูกต้อง!";[cite: 30]
        }
    } else {
        $error = "กรุณากรอกข้อมูลให้ครบถ้วน";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เข้าสู่ระบบ</title>
</head>
<body>
    <h2>เข้าสู่ระบบ (Login)</h2>

    <?php if ($error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div>
            <label>Email:</label><br>
            <input type="email" name="email" required>
        </div>
        <br>
        <div>
            <label>Password:</label><br>
            <input type="password" name="password" required>
        </div>
        <br>
        <button type="submit">Login</button>
    </form>
</body>
</html>