<?php
namespace App\Config;

use Flight;

class Routes {
    public static function register() {

        Flight::route('/', ['App\Controllers\DashboardController', 'index']);
        Flight::route('/dashboard', ['App\Controllers\DashboardController', 'index']);

        Flight::route('GET /villes', ['App\Controllers\VilleController', 'index']);
        Flight::route('GET /villes/nouveau', ['App\Controllers\VilleController', 'create']);
        Flight::route('POST /villes/nouveau', ['App\Controllers\VilleController', 'store']);
        Flight::route('GET /villes/@id', ['App\Controllers\VilleController', 'show']);
        Flight::route('GET /villes/@id/editer', ['App\Controllers\VilleController', 'edit']);
        Flight::route('POST /villes/@id/editer', ['App\Controllers\VilleController', 'update']);
        Flight::route('POST /villes/@id/supprimer', ['App\Controllers\VilleController', 'delete']);

        Flight::route('GET /besoins', ['App\Controllers\BesoinController', 'index']);
        Flight::route('GET /besoins/nouveau', ['App\Controllers\BesoinController', 'create']);
        Flight::route('POST /besoins/nouveau', ['App\Controllers\BesoinController', 'store']);
        Flight::route('GET /besoins/@id/editer', ['App\Controllers\BesoinController', 'edit']);
        Flight::route('POST /besoins/@id/editer', ['App\Controllers\BesoinController', 'update']);
        Flight::route('POST /besoins/@id/supprimer', ['App\Controllers\BesoinController', 'delete']);

        Flight::route('GET /dons', ['App\Controllers\DonController', 'index']);
        Flight::route('GET /dons/nouveau', ['App\Controllers\DonController', 'create']);
        Flight::route('POST /dons/nouveau', ['App\Controllers\DonController', 'store']);
        Flight::route('GET /dons/@id/editer', ['App\Controllers\DonController', 'edit']);
        Flight::route('POST /dons/@id/editer', ['App\Controllers\DonController', 'update']);
        Flight::route('POST /dons/@id/supprimer', ['App\Controllers\DonController', 'delete']);

        Flight::route('GET /dispatches', ['App\Controllers\DispatchController', 'index']);
        Flight::route('POST /dispatches/simuler', ['App\Controllers\DispatchController', 'simuler']);
        Flight::route('POST /dispatches/valider', ['App\Controllers\DispatchController', 'valider']);
        Flight::route('POST /dispatches/reset', ['App\Controllers\DispatchController', 'reset']);

        // Achats
        Flight::route('GET /achats', ['App\Controllers\AchatController', 'index']);
        Flight::route('POST /achats/effectuer', ['App\Controllers\AchatController', 'store']);
        Flight::route('POST /achats/@id/supprimer', ['App\Controllers\AchatController', 'delete']);

        // Récapitulation
        Flight::route('GET /recap', ['App\Controllers\RecapController', 'index']);
        Flight::route('GET /recap/ajax', ['App\Controllers\RecapController', 'ajaxData']);

        // Réinitialisation des données
        Flight::route('POST /reinitialiser', ['App\Controllers\DashboardController', 'reinitialiser']);

        Flight::map('notFound', function() {
            Flight::render('errors/404', ['title' => 'Page non trouvée']);
        });
    }
}
