<?php
namespace App\Controllers;

use Flight;
use App\Models\Besoin;
use App\Models\Ville;

class BesoinController {

    public static function index() {
        $besoinModel = new Besoin();
        $villeModel = new Ville();

        $besoins = $besoinModel->getAll();
        $villes = $villeModel->getAll();
        $types = $besoinModel->getTypes();

        
        $villeFilter = $_GET['ville_id'] ?? null;
        if ($villeFilter) {
            $besoins = array_filter($besoins, function($b) use ($villeFilter) {
                return $b['ville_id'] == $villeFilter;
            });
        }

        Flight::render('besoin/index', [
            'title' => 'BNGRC - Besoins des Sinistrés',
            'besoins' => $besoins,
            'villes' => $villes,
            'types' => $types,
            'villeFilter' => $villeFilter
        ]);
    }

    public static function create() {
        $villeModel = new Ville();
        $besoinModel = new Besoin();

        Flight::render('besoin/create', [
            'title' => 'BNGRC - Nouveau Besoin',
            'villes' => $villeModel->getAll(),
            'types' => $besoinModel->getTypes(),
            'preselected_ville' => $_GET['ville_id'] ?? null
        ]);
    }

    public static function store() {
        $besoinModel = new Besoin();

        $data = [
            'ville_id' => intval($_POST['ville_id'] ?? 0),
            'type_besoin_id' => intval($_POST['type_besoin_id'] ?? 0),
            'designation' => trim($_POST['designation'] ?? ''),
            'prix_unitaire' => floatval($_POST['prix_unitaire'] ?? 0),
            'quantite' => intval($_POST['quantite'] ?? 1)
        ];

        if (empty($data['designation']) || $data['ville_id'] <= 0 || $data['type_besoin_id'] <= 0 || $data['prix_unitaire'] <= 0) {
            Flight::flash('error', 'Tous les champs obligatoires doivent être remplis.');
            Flight::redirect(base_url('/besoins/nouveau'));
            return;
        }

        try {
            $besoinModel->create($data);
            Flight::flash('success', 'Besoin ajouté avec succès.');
            Flight::redirect(base_url('/besoins'));
        } catch (\Exception $e) {
            Flight::flash('error', 'Erreur lors de l\'ajout du besoin.');
            Flight::redirect(base_url('/besoins/nouveau'));
        }
    }

    public static function edit($id) {
        $besoinModel = new Besoin();
        $villeModel = new Ville();

        $besoin = $besoinModel->findById($id);
        if (!$besoin) {
            Flight::render('errors/404', ['title' => 'Besoin non trouvé']);
            return;
        }

        Flight::render('besoin/edit', [
            'title' => 'BNGRC - Modifier Besoin',
            'besoin' => $besoin,
            'villes' => $villeModel->getAll(),
            'types' => $besoinModel->getTypes()
        ]);
    }

    public static function update($id) {
        $besoinModel = new Besoin();

        $data = [
            'ville_id' => intval($_POST['ville_id'] ?? 0),
            'type_besoin_id' => intval($_POST['type_besoin_id'] ?? 0),
            'designation' => trim($_POST['designation'] ?? ''),
            'quantite' => intval($_POST['quantite'] ?? 1)
        ];

        if (empty($data['designation']) || $data['ville_id'] <= 0 || $data['type_besoin_id'] <= 0) {
            Flight::flash('error', 'Tous les champs obligatoires doivent être remplis.');
            Flight::redirect(base_url('/besoins/' . $id . '/editer'));
            return;
        }

        try {
            $besoinModel->update($id, $data);
            Flight::flash('success', 'Besoin modifié avec succès.');
            Flight::redirect(base_url('/besoins'));
        } catch (\Exception $e) {
            Flight::flash('error', 'Erreur lors de la modification.');
            Flight::redirect(base_url('/besoins/' . $id . '/editer'));
        }
    }

    public static function delete($id) {
        $besoinModel = new Besoin();
        try {
            $besoinModel->delete($id);
            Flight::flash('success', 'Besoin supprimé avec succès.');
        } catch (\Exception $e) {
            Flight::flash('error', 'Erreur lors de la suppression.');
        }
        Flight::redirect(base_url('/besoins'));
    }
}
