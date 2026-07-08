<?php


session_start();

$host    = '127.0.0.1';
$db      = 'student_management';
$user    = 'root';  
$pass    = '';        
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die('فشل الاتصال بقاعدة البيانات: ' . $e->getMessage());
}



function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array
{
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
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
