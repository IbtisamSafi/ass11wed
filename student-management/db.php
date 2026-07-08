<?php
// =========================================================
// db.php
// الاتصال بقاعدة البيانات + دوال مساعدة (flash messages)
// هاد الملف لازم يكون أول سطر بالملف، وكل صفحة بتعمل له require
// =========================================================

// session_start() لازم تنحط قبل أي إخراج (echo/HTML) عشان نقدر نستخدم $_SESSION
session_start();

// -------- إعدادات الاتصال بقاعدة البيانات --------
$host    = '127.0.0.1';
$db      = 'student_management';
$user    = 'root';   // غيّريها إذا عندك يوزر مختلف بـ XAMPP/phpMyAdmin
$pass    = '';        // غيّريها إذا عندك باسورد على MySQL
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // أي خطأ SQL يطلع Exception بدل ما يختفي بصمت
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // نتائج SELECT ترجع array associative
    PDO::ATTR_EMULATE_PREPARES   => false,                    // نستخدم prepared statements حقيقية (أأمن)
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // ما منطلع رسالة الخطأ الكاملة للمستخدم بالإنتاج، بس هون تعليمي فبنعرضها
    die('فشل الاتصال بقاعدة البيانات: ' . $e->getMessage());
}

// =========================================================
// Flash messages
// طريقة نعرض فيها رسالة نجاح/خطأ مرة وحدة بعد ما نعمل redirect
// مثال استخدام: set_flash('success', 'تمت الإضافة بنجاح');
// =========================================================

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array
{
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']); // نحذفها عشان ما تظهر مرة ثانية عند تحديث الصفحة
        return $flash;
    }
    return null;
}

function render_flash(): void
{
    $flash = get_flash();
    if ($flash) {
        $class = $flash['type'] === 'success' ? 'alert-success' : 'alert-error';
        echo '<div class="alert ' . $class . '">' . htmlspecialchars($flash['message']) . '</div>';
    }
}
