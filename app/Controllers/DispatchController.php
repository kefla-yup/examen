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

        // Vérifier s'il y a une simulation en session
        $simulation = $_SESSION['simulation_result'] ?? null;

        Flight::render('dispatch/index', [
            'title' => 'BNGRC - Distribution des Dons',
            'dispatches' => $dispatches,
            'stats' => $stats,
            'simulation' => $simulation
        ]);
    }

    /**
     * Simuler le dispatch sans valider — affiche le résultat en aperçu
     */
    public static function simuler() {
        $dispatchModel = new Dispatch();
        
        try {
            $result = $dispatchModel->simulerPreview();
            $_SESSION['simulation_result'] = $result;
            Flight::flash('info', 'Simulation terminée ! ' . number_format($result['total'], 0, ',', ' ') . ' unités seraient distribuées. Vérifiez le résultat puis cliquez sur « Valider » pour confirmer.');
        } catch (\Exception $e) {
            Flight::flash('error', 'Erreur lors de la simulation : ' . $e->getMessage());
        }
        
        Flight::redirect(base_url('/dispatches'));
    }

    /**
     * Valider et appliquer le dispatch réellement (depuis la simulation en session)
     */
    public static function valider() {
        $dispatchModel = new Dispatch();
        
        // Vérifier qu'une simulation existe en session
        $simulation = $_SESSION['simulation_result'] ?? null;
        if (empty($simulation) || empty($simulation['dispatches'])) {
            Flight::flash('error', 'Aucune simulation à valider. Veuillez d\'abord lancer une simulation.');
            Flight::redirect(base_url('/dispatches'));
            return;
        }
        
        try {
            $total = $dispatchModel->executerDepuisSimulation($simulation['dispatches']);
            unset($_SESSION['simulation_result']);
            Flight::flash('success', 'Distribution validée ! ' . number_format($total, 0, ',', ' ') . ' unités ont été distribuées avec succès.');
        } catch (\Exception $e) {
            Flight::flash('error', 'Erreur lors de la validation : ' . $e->getMessage());
        }
        
        Flight::redirect(base_url('/dispatches'));
    }

    public static function reset() {
        $dispatchModel = new Dispatch();
        
        try {
            $dispatchModel->resetAll();
            unset($_SESSION['simulation_result']);
            Flight::flash('success', 'Toutes les distributions ont été réinitialisées.');
        } catch (\Exception $e) {
            Flight::flash('error', 'Erreur lors de la réinitialisation.');
        }
        
        Flight::redirect(base_url('/dispatches'));
    }
}
