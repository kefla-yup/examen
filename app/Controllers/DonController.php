<?php
namespace App\Controllers;

use Flight;
use App\Models\Don;
use App\Models\Besoin;

class DonController {

    public static function index() {
        $donModel = new Don();
        $besoinModel = new Besoin();

        $dons = $donModel->getAll();
        $types = $besoinModel->getTypes();

        Flight::render('don/index', [
            'title' => 'BNGRC - Dons Reçus',
            'dons' => $dons,
            'types' => $types
        ]);
    }

    public static function create() {
        $besoinModel = new Besoin();

        Flight::render('don/create', [
            'title' => 'BNGRC - Nouveau Don',
            'types' => $besoinModel->getTypes()
        ]);
    }

    public static function store() {
        $donModel = new Don();

        $data = [
            'donateur' => trim($_POST['donateur'] ?? 'Anonyme'),
            'type_besoin_id' => intval($_POST['type_besoin_id'] ?? 0),
            'designation' => trim($_POST['designation'] ?? ''),
            'quantite' => intval($_POST['quantite'] ?? 1),
            'date_don' => $_POST['date_don'] ?? date('Y-m-d')
        ];

        if (empty($data['designation']) || $data['type_besoin_id'] <= 0 || $data['quantite'] <= 0) {
            Flight::flash('error', 'Tous les champs obligatoires doivent être remplis.');
            Flight::redirect(base_url('/dons/nouveau'));
            return;
        }

        try {
            $donModel->create($data);
            Flight::flash('success', 'Don enregistré avec succès.');
            Flight::redirect(base_url('/dons'));
        } catch (\Exception $e) {
            Flight::flash('error', 'Erreur lors de l\'enregistrement du don.');
            Flight::redirect(base_url('/dons/nouveau'));
        }
    }

    public static function edit($id) {
        $donModel = new Don();
        $besoinModel = new Besoin();

        $don = $donModel->findById($id);
        if (!$don) {
            Flight::render('errors/404', ['title' => 'Don non trouvé']);
            return;
        }

        Flight::render('don/edit', [
            'title' => 'BNGRC - Modifier Don',
            'don' => $don,
            'types' => $besoinModel->getTypes()
        ]);
    }

    public static function update($id) {
        $donModel = new Don();

        $data = [
            'donateur' => trim($_POST['donateur'] ?? 'Anonyme'),
            'type_besoin_id' => intval($_POST['type_besoin_id'] ?? 0),
            'designation' => trim($_POST['designation'] ?? ''),
            'quantite' => intval($_POST['quantite'] ?? 1),
            'date_don' => $_POST['date_don'] ?? date('Y-m-d')
        ];

        if (empty($data['designation']) || $data['type_besoin_id'] <= 0) {
            Flight::flash('error', 'Tous les champs obligatoires doivent être remplis.');
            Flight::redirect(base_url('/dons/' . $id . '/editer'));
            return;
        }

        try {
            $donModel->update($id, $data);
            Flight::flash('success', 'Don modifié avec succès.');
            Flight::redirect(base_url('/dons'));
        } catch (\Exception $e) {
            Flight::flash('error', 'Erreur lors de la modification.');
            Flight::redirect(base_url('/dons/' . $id . '/editer'));
        }
    }

    public static function delete($id) {
        $donModel = new Don();
        try {
            $donModel->delete($id);
            Flight::flash('success', 'Don supprimé avec succès.');
        } catch (\Exception $e) {
            Flight::flash('error', 'Erreur lors de la suppression.');
        }
        Flight::redirect(base_url('/dons'));
    }
}
