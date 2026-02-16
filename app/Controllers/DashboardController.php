<?php
namespace App\Controllers;

use Flight;
use App\Models\Ville;
use App\Models\Besoin;
use App\Models\Don;
use App\Models\Dispatch;

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
}
