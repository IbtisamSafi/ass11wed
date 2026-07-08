<?php

require 'db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    set_flash('error', 'رقم الطالب غير صحيح');
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM students WHERE id = :id');
$stmt->execute([':id' => $id]);
$student = $stmt->fetch();

if (!$student) {
    set_flash('error', 'هذا الطالب غير موجود');
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    $del = $pdo->prepare('DELETE FROM students WHERE id = :id');
    $del->execute([':id' => $id]);

    set_flash('success', 'تم حذف الطالب بنجاح');
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>حذف طالب - Student Management System</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<header class="app-header">
    <h1>Student Management System</h1>
    <p>تأكيد حذف طالب</p>
</header>

<div class="container">
    <nav class="app-nav">
        <a class="btn" href="index.php">كل الطلاب</a>
        <a class="btn" href="add.php">+ إضافة طالب</a>
        <a class="btn btn-secondary" href="search.php">بحث</a>
    </nav>

    <div class="confirm-box">
        <h2 style="color:#f44336; margin-bottom:15px;">⚠ هل أنتي متأكدة إنك بدك تحذفي هالطالب؟</h2>
        <p><strong>الاسم:</strong> <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></p>
        <p><strong>الإيميل:</strong> <?= htmlspecialchars($student['email']) ?></p>
        <p><strong>التخصص:</strong> <?= htmlspecialchars($student['major'] ?? '-') ?></p>
        <p style="color:#ef9a9a; margin-top:15px;">هذا الإجراء لا يمكن التراجع عنه.</p>

        <form method="post" action="delete.php?id=<?= $id ?>" style="margin-top:20px;">
            <input type="hidden" name="confirm_delete" value="1">
            <button type="submit" class="btn btn-danger">نعم، احذف نهائياً</button>
            <a href="index.php" class="btn btn-secondary">إلغاء، رجّعني</a>
        </form>
    </div>
</div>

<footer class="app-footer">Student Management System &copy; <?= date('Y') ?></footer>
</body>
</html>
