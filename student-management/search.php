<?php

require 'db.php';

$name  = trim($_GET['name'] ?? '');
$email = trim($_GET['email'] ?? '');
$major = trim($_GET['major'] ?? '');

$did_search = isset($_GET['search_submitted']);
$students   = [];

if ($did_search) {
    $conditions = [];
    $params     = [];

    if ($name !== '') {
        $conditions[] = "(first_name LIKE :name OR last_name LIKE :name OR CONCAT(first_name, ' ', last_name) LIKE :name)";
        $params[':name'] = '%' . $name . '%';
    }
    if ($email !== '') {
        $conditions[] = 'email LIKE :email';
        $params[':email'] = '%' . $email . '%';
    }
    if ($major !== '') {
        $conditions[] = 'major LIKE :major';
        $params[':major'] = '%' . $major . '%';
    }

    $sql = 'SELECT * FROM students';
    if (!empty($conditions)) {
        $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }
    $sql .= ' ORDER BY id DESC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $students = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>بحث عن طالب - Student Management System</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<header class="app-header">
    <h1>Student Management System</h1>
    <p>البحث عن طالب</p>
</header>

<div class="container">
    <nav class="app-nav">
        <a class="btn" href="index.php">كل الطلاب</a>
        <a class="btn" href="add.php">+ إضافة طالب</a>
        <a class="btn btn-secondary" href="search.php">بحث</a>
    </nav>

    <?php render_flash(); ?>

    <form method="get" action="search.php" class="search-box">
        <input type="hidden" name="search_submitted" value="1">
        <input type="text" name="name" placeholder="بحث بالاسم" value="<?= htmlspecialchars($name) ?>">
        <input type="text" name="email" placeholder="بحث بالإيميل" value="<?= htmlspecialchars($email) ?>">
        <input type="text" name="major" placeholder="بحث بالتخصص" value="<?= htmlspecialchars($major) ?>">
        <button type="submit" class="btn">بحث</button>
    </form>

    <?php if ($did_search): ?>
        <p style="color:#888; font-size:14px; margin-top:15px;">
            عدد النتائج: <?= count($students) ?>
        </p>

        <?php if (empty($students)): ?>
            <div class="empty-state">ما في نتائج مطابقة لبحثك.</div>
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
        <?php endif; ?>
    <?php endif; ?>
</div>

<footer class="app-footer">Student Management System &copy; <?= date('Y') ?></footer>
</body>
</html>
