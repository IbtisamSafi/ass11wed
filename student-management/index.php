<?php

require 'db.php';

$per_page = 10;
$page     = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}
$offset = ($page - 1) * $per_page;

$total       = (int) $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
$total_pages = max(1, (int) ceil($total / $per_page));
if ($page > $total_pages) {
    $page   = $total_pages;
    $offset = ($page - 1) * $per_page;
}

$stmt = $pdo->prepare('SELECT * FROM students ORDER BY id DESC LIMIT :limit OFFSET :offset');
$stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$students = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>Student Management System</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<header class="app-header">
    <h1>Student Management System</h1>
    <p>نظام إدارة بيانات الطلاب</p>
</header>

<div class="container">
    <nav class="app-nav">
        <a class="btn" href="index.php">كل الطلاب</a>
        <a class="btn" href="add.php">+ إضافة طالب</a>
        <a class="btn btn-secondary" href="search.php">بحث</a>
    </nav>

    <?php render_flash(); ?>

    <p style="color:#888; font-size:14px;">
        إجمالي الطلاب: <?= (int) $total ?> — صفحة <?= (int) $page ?> من <?= (int) $total_pages ?>
    </p>

    <?php if (empty($students)): ?>
        <div class="empty-state">لا يوجد طلاب حالياً. ابدئي بإضافة طالب جديد.</div>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>الاسم الكامل</th>
                <th>الإيميل</th>
                <th>الهاتف</th>
                <th>التخصص</th>
                <th>المعدل</th>
                <th>تاريخ التسجيل</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($students as $s): ?>
            <tr>
                <td><?= (int) $s['id'] ?></td>
                <td><?= htmlspecialchars($s['first_name'] . ' ' . $s['last_name']) ?></td>
                <td><?= htmlspecialchars($s['email']) ?></td>
                <td><?= htmlspecialchars($s['phone'] ?? '-') ?></td>
                <td><?= htmlspecialchars($s['major'] ?? '-') ?></td>
                <td><?= htmlspecialchars($s['gpa']) ?></td>
                <td><?= htmlspecialchars($s['enrollment_date'] ?? '-') ?></td>
                <td class="actions">
                    <a class="btn" href="edit.php?id=<?= (int) $s['id'] ?>">تعديل</a>
                    <a class="btn btn-danger" href="delete.php?id=<?= (int) $s['id'] ?>">حذف</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>">‹ السابق</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <?php if ($i === $page): ?>
                <span class="active"><?= $i ?></span>
            <?php else: ?>
                <a href="?page=<?= $i ?>"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="?page=<?= $page + 1 ?>">التالي ›</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<footer class="app-footer">Student Management System &copy; <?= date('Y') ?></footer>
</body>
</html>
