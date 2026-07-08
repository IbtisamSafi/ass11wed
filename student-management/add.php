<?php
// =========================================================
// add.php
// فورم لإضافة طالب جديد + تحقق (validation) + INSERT بـ prepared statement
// =========================================================
require 'db.php';

$errors = [];
// قيم افتراضية فاضية عشان الفورم يبقى شغال حتى لو صار خطأ (منرجع القيم القديمة)
$values = [
    'first_name'      => '',
    'last_name'       => '',
    'email'           => '',
    'phone'           => '',
    'major'           => '',
    'gpa'             => '',
    'enrollment_date' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    foreach ($values as $key => $default) {
        $values[$key] = trim($_POST[$key] ?? '');
    }

    // ---------- Validation ----------
    if ($values['first_name'] === '') {
        $errors['first_name'] = 'الاسم الأول مطلوب';
    }
    if ($values['last_name'] === '') {
        $errors['last_name'] = 'الاسم الأخير مطلوب';
    }
    if ($values['email'] === '') {
        $errors['email'] = 'الإيميل مطلوب';
    } elseif (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'صيغة الإيميل غير صحيحة';
    }
    if ($values['major'] === '') {
        $errors['major'] = 'التخصص مطلوب';
    }
    if ($values['gpa'] !== '' && (!is_numeric($values['gpa']) || $values['gpa'] < 0 || $values['gpa'] > 4)) {
        $errors['gpa'] = 'المعدل لازم يكون رقم بين 0 و 4';
    }
    if ($values['enrollment_date'] !== '') {
        $d = DateTime::createFromFormat('Y-m-d', $values['enrollment_date']);
        if (!$d) {
            $errors['enrollment_date'] = 'صيغة التاريخ غير صحيحة';
        }
    }

    // ---------- إذا ما في أخطاء، ننفذ INSERT ----------
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO students (first_name, last_name, email, phone, major, gpa, enrollment_date)
                 VALUES (:first_name, :last_name, :email, :phone, :major, :gpa, :enrollment_date)'
            );
            $stmt->execute([
                ':first_name'      => $values['first_name'],
                ':last_name'       => $values['last_name'],
                ':email'           => $values['email'],
                ':phone'           => $values['phone'] !== '' ? $values['phone'] : null,
                ':major'           => $values['major'],
                ':gpa'             => $values['gpa'] !== '' ? $values['gpa'] : 0,
                ':enrollment_date' => $values['enrollment_date'] !== '' ? $values['enrollment_date'] : null,
            ]);

            set_flash('success', 'تمت إضافة الطالب بنجاح');
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            // duplicate email -> MySQL error code 23000
            if ($e->getCode() === '23000') {
                $errors['email'] = 'هذا الإيميل مستخدم مسبقاً';
            } else {
                $errors['general'] = 'حدث خطأ أثناء الإضافة: ' . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>إضافة طالب - Student Management System</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<header class="app-header">
    <h1>Student Management System</h1>
    <p>إضافة طالب جديد</p>
</header>

<div class="container">
    <nav class="app-nav">
        <a class="btn" href="index.php">كل الطلاب</a>
        <a class="btn" href="add.php">+ إضافة طالب</a>
        <a class="btn btn-secondary" href="search.php">بحث</a>
    </nav>

    <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($errors['general']) ?></div>
    <?php endif; ?>

    <form class="card" method="post" action="add.php" novalidate>

        <div class="form-group">
            <label for="first_name">الاسم الأول</label>
            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($values['first_name']) ?>">
            <?php if (!empty($errors['first_name'])): ?><div class="field-error"><?= $errors['first_name'] ?></div><?php endif; ?>
        </div>

        <div class="form-group">
            <label for="last_name">الاسم الأخير</label>
            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($values['last_name']) ?>">
            <?php if (!empty($errors['last_name'])): ?><div class="field-error"><?= $errors['last_name'] ?></div><?php endif; ?>
        </div>

        <div class="form-group">
            <label for="email">الإيميل</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($values['email']) ?>">
            <?php if (!empty($errors['email'])): ?><div class="field-error"><?= $errors['email'] ?></div><?php endif; ?>
        </div>

        <div class="form-group">
            <label for="phone">الهاتف</label>
            <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($values['phone']) ?>">
        </div>

        <div class="form-group">
            <label for="major">التخصص</label>
            <input type="text" id="major" name="major" value="<?= htmlspecialchars($values['major']) ?>">
            <?php if (!empty($errors['major'])): ?><div class="field-error"><?= $errors['major'] ?></div><?php endif; ?>
        </div>

        <div class="form-group">
            <label for="gpa">المعدل (0 - 4)</label>
            <input type="number" step="0.01" min="0" max="4" id="gpa" name="gpa" value="<?= htmlspecialchars($values['gpa']) ?>">
            <?php if (!empty($errors['gpa'])): ?><div class="field-error"><?= $errors['gpa'] ?></div><?php endif; ?>
        </div>

        <div class="form-group">
            <label for="enrollment_date">تاريخ التسجيل</label>
            <input type="date" id="enrollment_date" name="enrollment_date" value="<?= htmlspecialchars($values['enrollment_date']) ?>">
            <?php if (!empty($errors['enrollment_date'])): ?><div class="field-error"><?= $errors['enrollment_date'] ?></div><?php endif; ?>
        </div>

        <button type="submit" class="btn">حفظ الطالب</button>
        <a href="index.php" class="btn btn-secondary">إلغاء</a>
    </form>
</div>

<footer class="app-footer">Student Management System &copy; <?= date('Y') ?></footer>
</body>
</html>
