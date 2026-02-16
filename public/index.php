<?php
// Charger l'autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Démarrer la session
session_start();

// Configuration de Flight
Flight::set('flight.views.path', __DIR__ . '/../app/views');
Flight::set('flight.log_errors', true);

// ===== BASE URL =====
// Changer cette valeur selon l'environnement de déploiement
// Ex: '/buzz/public' si l'app est dans un sous-dossier
// Ex: '' ou '/' pour la racine
define('BASE_URL', '');

// Helper pour générer les URLs avec le base_url
function base_url($path = '') {
    $base = rtrim(BASE_URL, '/');
    if ($path === '' || $path === '/') {
        return $base . '/';
    }
    return $base . '/' . ltrim($path, '/');
}

// Helper pour les assets
function asset($path) {
    return base_url('assets/' . ltrim($path, '/'));
}

// Rendre les helpers disponibles dans Flight
Flight::map('baseUrl', function($path = '') {
    return base_url($path);
});
Flight::set('flight.base_url', BASE_URL);

// Charger la configuration de la base de données
$dbConfig = require __DIR__ . '/../app/Config/database.php';

// Connexion à la base de données
try {
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['database']};charset={$dbConfig['charset']}";
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
    
    // Enregistrer la connexion PDO dans Flight
    Flight::register('db', 'PDO', array($dsn, $dbConfig['username'], $dbConfig['password']));
    
    // Définir le fuseau horaire
    $pdo->exec("SET time_zone = '+03:00'");
    
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}

// Configuration des chemins
Flight::set('app.root', realpath(__DIR__ . '/../'));
Flight::set('app.public', __DIR__);

// Helper pour les messages flash
Flight::map('flash', function($type, $message) {
    if (!isset($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }
    $_SESSION['flash'][$type][] = $message;
});

// Helper pour afficher les messages flash
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

// Helper pour formater les nombres en Ariary
Flight::map('formatMoney', function($amount) {
    return number_format($amount, 0, ',', ' ') . ' Ar';
});

// Charger les routes
require_once __DIR__ . '/../app/Config/Routes.php';
App\Config\Routes::register();

// Démarrer l'application
Flight::start();
