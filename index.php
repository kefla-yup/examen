<?php
require_once __DIR__ . '/vendor/autoload.php';

session_start();

Flight::set('flight.views.path', __DIR__ . '/app/views');
Flight::set('flight.log_errors', true);


$baseUrl = '';
if (!empty($_SERVER['SCRIPT_NAME'])) {
    $dir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
    if ($dir !== '' && $dir !== '.') {
        $baseUrl = $dir;
    }
}

if ($baseUrl === '' && !empty($_SERVER['DOCUMENT_ROOT'])) {
    $docRoot = rtrim(str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'])), '/');
    $appRoot = rtrim(str_replace('\\', '/', __DIR__), '/');
    if (strpos($appRoot, $docRoot) === 0) {
        $baseUrl = substr($appRoot, strlen($docRoot));
    }
}
define('BASE_URL', $baseUrl);

function base_url($path = '') {
    $base = rtrim(BASE_URL, '/');
    if ($path === '' || $path === '/') {
        return $base . '/';
    }
    return $base . '/' . ltrim($path, '/');
}

function asset($path) {
    return base_url('public/assets/' . ltrim($path, '/'));
}

Flight::map('baseUrl', function($path = '') {
    return base_url($path);
});
// Ne pas définir flight.base_url car base_url() inclut déjà le préfixe.
// Flight::_redirect() re-prépend flight.base_url, ce qui double le chemin.
Flight::set('flight.base_url', '/');

$dbConfig = require __DIR__ . '/app/Config/database.php';

try {
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['database']};charset={$dbConfig['charset']}";
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
    
    Flight::register('db', 'PDO', array($dsn, $dbConfig['username'], $dbConfig['password']));
    
    $pdo->exec("SET time_zone = '+03:00'");
    
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}

Flight::set('app.root', __DIR__);
Flight::set('app.public', __DIR__ . '/public');

Flight::map('flash', function($type, $message) {
    if (!isset($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }
    $_SESSION['flash'][$type][] = $message;
});

Flight::map('displayFlash', function() {
    if (!isset($_SESSION['flash'])) {
        return '';
    }
    
    $html = '';
    foreach ($_SESSION['flash'] as $type => $messages) {
        $alertClass = $type === 'error' ? 'danger' : $type;
        $icon = $type === 'success' ? 'bx-check-circle' : ($type === 'error' ? 'bx-error-circle' : 'bx-info-circle');
        foreach ($messages as $message) {
            $html .= '<div class="alert alert-' . $alertClass . ' alert-dismissible fade show d-flex align-items-center" role="alert">';
            $html .= '<i class="bx ' . $icon . ' me-2 fs-5"></i>';
            $html .= '<div>' . htmlspecialchars($message) . '</div>';
            $html .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            $html .= '</div>';
        }
    }
    
    unset($_SESSION['flash']);
    return $html;
});

Flight::map('formatMoney', function($amount) {
    return number_format($amount, 0, ',', ' ') . ' Ar';
});

require_once __DIR__ . '/app/Config/Routes.php';
App\Config\Routes::register();

Flight::start();
