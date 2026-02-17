<?php
namespace App\Controllers;

use Flight;
use App\Models\Ville;
use App\Models\Besoin;
use App\Models\Don;
use App\Models\Dispatch;
use App\Models\Seeder;

class DashboardController {
    
    public static function index() {
        $villeModel = new Ville();
        $besoinModel = new Besoin();
        $donModel = new Don();
        $dispatchModel = new Dispatch();

        $villes = $villeModel->getWithStats();
        $totalVilles = $villeModel->countAll();
        $totalBesoins = $besoinModel->countAll();
        $totalDons = $donModel->countAll();
        $totalDispatches = $dispatchModel->countAll();
        $valeurBesoins = $besoinModel->getTotalValue();
        $recentDons = $donModel->getRecentDons(5);
        $types = $besoinModel->getTypes();

        Flight::render('dashboard/index', [
            'title' => 'BNGRC - Tableau de Bord',
            'villes' => $villes,
            'totalVilles' => $totalVilles,
            'totalBesoins' => $totalBesoins,
            'totalDons' => $totalDons,
            'totalDispatches' => $totalDispatches,
            'valeurBesoins' => $valeurBesoins,
            'recentDons' => $recentDons,
            'types' => $types
        ]);
    }

    public static function reinitialiser() {
        $seeder = new Seeder();
        $redirect = $_POST['redirect'] ?? '/';
        
        try {
            $seeder->resetToDefault();
            // Nettoyer la session de simulation
            unset($_SESSION['simulation_result']);
            Flight::flash('success', 'Données réinitialisées aux valeurs par défaut avec succès.');
        } catch (\Exception $e) {
            Flight::flash('error', 'Erreur lors de la réinitialisation : ' . $e->getMessage());
        }
        
        Flight::redirect(base_url($redirect));
    }
}
