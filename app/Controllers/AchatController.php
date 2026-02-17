<?php
namespace App\Controllers;

use Flight;
use App\Models\Achat;
use App\Models\Besoin;
use App\Models\Ville;

class AchatController {

    public static function index() {
        $achatModel = new Achat();
        $villeModel = new Ville();

        $villeFilter = $_GET['ville_id'] ?? null;
        $achats = $achatModel->getAllFiltered($villeFilter);
        $villes = $villeModel->getAll();
        $soldeArgent = $achatModel->getSoldeArgentDisponible();
        $totalAchats = $achatModel->getTotalAchats();
        $besoinsRestants = $achatModel->getBesoinsRestantsAchetables($villeFilter);
        $fraisPourcent = Achat::FRAIS_POURCENTAGE;

        Flight::render('achat/index', [
            'title' => 'BNGRC - Achats via Dons Argent',
            'achats' => $achats,
            'villes' => $villes,
            'villeFilter' => $villeFilter,
            'soldeArgent' => $soldeArgent,
            'totalAchats' => $totalAchats,
            'besoinsRestants' => $besoinsRestants,
            'fraisPourcent' => $fraisPourcent
        ]);
    }

    public static function store() {
        $achatModel = new Achat();
        $besoinModel = new Besoin();

        $besoinId = intval($_POST['besoin_id'] ?? 0);
        $quantite = intval($_POST['quantite'] ?? 0);
        $fraisPourcent = floatval($_POST['frais_pourcent'] ?? Achat::FRAIS_POURCENTAGE);

        if ($besoinId <= 0 || $quantite <= 0) {
            Flight::flash('error', 'Données invalides. Veuillez remplir tous les champs.');
            Flight::redirect(base_url('/achats'));
            return;
        }

        // Vérifier si le besoin existe
        $besoin = $besoinModel->findById($besoinId);
        if (!$besoin) {
            Flight::flash('error', 'Besoin non trouvé.');
            Flight::redirect(base_url('/achats'));
            return;
        }

        // Vérifier si un achat existe déjà pour ce besoin dans les dons restants
        if ($achatModel->achatExistePourBesoin($besoinId)) {
            Flight::flash('error', 'Un achat existe déjà pour ce besoin. Impossible de dupliquer.');
            Flight::redirect(base_url('/achats'));
            return;
        }

        // Calculer le montant
        $montantHT = $besoin['prix_unitaire'] * $quantite;
        $montantTTC = Achat::calculerMontantTTC($montantHT, $fraisPourcent);

        // Vérifier le solde disponible
        $solde = $achatModel->getSoldeArgentDisponible();
        if ($montantTTC > $solde) {
            Flight::flash('error', 'Solde insuffisant ! Solde disponible: ' . number_format($solde, 0, ',', ' ') . ' Ar. Montant nécessaire: ' . number_format($montantTTC, 0, ',', ' ') . ' Ar.');
            Flight::redirect(base_url('/achats'));
            return;
        }

        // Vérifier que la quantité ne dépasse pas le besoin restant
        $besoinsRestants = $achatModel->getBesoinsRestantsAchetables();
        $besoinRestant = null;
        foreach ($besoinsRestants as $br) {
            if ($br['id'] == $besoinId) {
                $besoinRestant = $br;
                break;
            }
        }

        if (!$besoinRestant || $quantite > $besoinRestant['quantite_restante']) {
            Flight::flash('error', 'La quantité demandée dépasse le besoin restant.');
            Flight::redirect(base_url('/achats'));
            return;
        }

        try {
            $achatModel->create([
                'besoin_id' => $besoinId,
                'ville_id' => $besoin['ville_id'],
                'quantite' => $quantite,
                'montant_ht' => $montantHT,
                'frais_pourcent' => $fraisPourcent,
                'montant_ttc' => $montantTTC,
                'date_achat' => date('Y-m-d')
            ]);
            Flight::flash('success', 'Achat effectué avec succès ! Montant: ' . number_format($montantTTC, 0, ',', ' ') . ' Ar (dont ' . $fraisPourcent . '% de frais).');
            Flight::redirect(base_url('/achats'));
        } catch (\Exception $e) {
            Flight::flash('error', 'Erreur lors de l\'achat : ' . $e->getMessage());
            Flight::redirect(base_url('/achats'));
        }
    }

    public static function delete($id) {
        $achatModel = new Achat();
        try {
            $achatModel->delete($id);
            Flight::flash('success', 'Achat supprimé avec succès.');
        } catch (\Exception $e) {
            Flight::flash('error', 'Erreur lors de la suppression.');
        }
        Flight::redirect(base_url('/achats'));
    }
}
