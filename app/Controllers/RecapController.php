<?php
namespace App\Controllers;

use Flight;
use App\Models\Besoin;
use App\Models\Dispatch;
use App\Models\Don;
use App\Models\Ville;
use App\Models\Achat;

class RecapController {

    public static function index() {
        $data = self::getRecapData();
        $data['title'] = 'BNGRC - Récapitulation';

        Flight::render('recap/index', $data);
    }

    /**
     * Endpoint Ajax pour actualiser les données de récap
     */
    public static function ajaxData() {
        $data = self::getRecapData();
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    private static function getRecapData() {
        $besoinModel = new Besoin();
        $dispatchModel = new Dispatch();
        $donModel = new Don();
        $villeModel = new Ville();

        $db = Flight::db();

        // Besoins totaux en montant
        $stmtTotal = $db->query("SELECT COALESCE(SUM(prix_unitaire * quantite), 0) as montant_total FROM besoins");
        $montantBesoinsTotal = $stmtTotal->fetch()['montant_total'];

        // Besoins satisfaits en montant (via dispatches)
        $stmtSatisfaits = $db->query("
            SELECT COALESCE(SUM(b.prix_unitaire * disp.quantite_attribuee), 0) as montant_satisfait
            FROM dispatches disp
            JOIN besoins b ON disp.besoin_id = b.id
        ");
        $montantBesoinsSatisfaits = $stmtSatisfaits->fetch()['montant_satisfait'];

        // Montant satisfait via achats
        $stmtAchats = $db->query("
            SELECT COALESCE(SUM(b.prix_unitaire * a.quantite), 0) as montant_achats
            FROM achats a
            JOIN besoins b ON a.besoin_id = b.id
        ");
        $montantAchats = $stmtAchats->fetch()['montant_achats'];

        $montantTotalSatisfait = $montantBesoinsSatisfaits + $montantAchats;

        // Montant besoins restants
        $montantBesoinsRestants = $montantBesoinsTotal - $montantTotalSatisfait;
        if ($montantBesoinsRestants < 0) $montantBesoinsRestants = 0;

        // Pourcentage de couverture
        $pourcentageCouverture = $montantBesoinsTotal > 0 
            ? round(($montantTotalSatisfait / $montantBesoinsTotal) * 100, 1) 
            : 0;

        // Détails par ville
        $stmtParVille = $db->query("
            SELECT v.id, v.nom as ville_nom, v.region,
                COALESCE(bt.montant_besoins, 0) as montant_besoins,
                COALESCE(ds.montant_distribue, 0) as montant_distribue,
                COALESCE(ac.montant_achete, 0) as montant_achete
            FROM villes v
            LEFT JOIN (
                SELECT ville_id, SUM(prix_unitaire * quantite) as montant_besoins
                FROM besoins GROUP BY ville_id
            ) bt ON v.id = bt.ville_id
            LEFT JOIN (
                SELECT disp.ville_id, SUM(b.prix_unitaire * disp.quantite_attribuee) as montant_distribue
                FROM dispatches disp
                JOIN besoins b ON disp.besoin_id = b.id
                GROUP BY disp.ville_id
            ) ds ON v.id = ds.ville_id
            LEFT JOIN (
                SELECT a.ville_id, SUM(b.prix_unitaire * a.quantite) as montant_achete
                FROM achats a
                JOIN besoins b ON a.besoin_id = b.id
                GROUP BY a.ville_id
            ) ac ON v.id = ac.ville_id
            ORDER BY v.nom ASC
        ");
        $detailsParVille = $stmtParVille->fetchAll();

        // Calculer le restant par ville
        foreach ($detailsParVille as &$ville) {
            $ville['montant_satisfait'] = $ville['montant_distribue'] + $ville['montant_achete'];
            $ville['montant_restant'] = max(0, $ville['montant_besoins'] - $ville['montant_satisfait']);
            $ville['pourcentage'] = $ville['montant_besoins'] > 0 
                ? round(($ville['montant_satisfait'] / $ville['montant_besoins']) * 100, 1) 
                : 0;
        }

        return [
            'montantBesoinsTotal' => $montantBesoinsTotal,
            'montantBesoinsSatisfaits' => $montantTotalSatisfait,
            'montantBesoinsSatisfaitsDispatch' => $montantBesoinsSatisfaits,
            'montantBesoinsSatisfaitsAchats' => $montantAchats,
            'montantBesoinsRestants' => $montantBesoinsRestants,
            'pourcentageCouverture' => $pourcentageCouverture,
            'detailsParVille' => $detailsParVille
        ];
    }
}
