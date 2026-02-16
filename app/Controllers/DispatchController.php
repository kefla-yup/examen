<?php
namespace App\Controllers;

use Flight;
use App\Models\Dispatch;
use App\Models\Don;
use App\Models\Besoin;
use App\Models\Ville;

class DispatchController {

    public static function index() {
        $dispatchModel = new Dispatch();
        $dispatches = $dispatchModel->getAll();
        $stats = $dispatchModel->getStatsByVille();

        Flight::render('dispatch/index', [
            'title' => 'BNGRC - Distribution des Dons',
            'dispatches' => $dispatches,
            'stats' => $stats
        ]);
    }

    public static function simuler() {
        $dispatchModel = new Dispatch();
        
        try {
            $total = $dispatchModel->simulerDispatch();
            Flight::flash('success', 'Simulation terminée ! ' . number_format($total, 0, ',', ' ') . ' unités ont été distribuées.');
        } catch (\Exception $e) {
            Flight::flash('error', 'Erreur lors de la simulation : ' . $e->getMessage());
        }
        
        Flight::redirect(base_url('/dispatches'));
    }

    public static function reset() {
        $dispatchModel = new Dispatch();
        
        try {
            $dispatchModel->resetAll();
            Flight::flash('success', 'Toutes les distributions ont été réinitialisées.');
        } catch (\Exception $e) {
            Flight::flash('error', 'Erreur lors de la réinitialisation.');
        }
        
        Flight::redirect(base_url('/dispatches'));
    }
}
