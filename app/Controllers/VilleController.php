<?php
namespace App\Controllers;

use Flight;
use App\Models\Ville;
use App\Models\Besoin;
use App\Models\Dispatch;

class VilleController {

    public static function index() {
        $villeModel = new Ville();
        $villes = $villeModel->getWithStats();

        Flight::render('ville/index', [
            'title' => 'BNGRC - Villes Sinistrées',
            'villes' => $villes
        ]);
    }

    public static function show($id) {
        $villeModel = new Ville();
        $besoinModel = new Besoin();
        $dispatchModel = new Dispatch();

        $ville = $villeModel->findById($id);
        if (!$ville) {
            Flight::render('errors/404', ['title' => 'Ville non trouvée']);
            return;
        }

        $besoins = $besoinModel->getByVille($id);
        $dispatches = $dispatchModel->getByVille($id);

        Flight::render('ville/show', [
            'title' => 'BNGRC - ' . $ville['nom'],
            'ville' => $ville,
            'besoins' => $besoins,
            'dispatches' => $dispatches
        ]);
    }

    public static function create() {
        Flight::render('ville/create', [
            'title' => 'BNGRC - Nouvelle Ville'
        ]);
    }

    public static function store() {
        $villeModel = new Ville();
        
        $data = [
            'nom' => trim($_POST['nom'] ?? ''),
            'region' => trim($_POST['region'] ?? ''),
            'population' => intval($_POST['population'] ?? 0)
        ];

        if (empty($data['nom']) || empty($data['region'])) {
            Flight::flash('error', 'Le nom et la région sont obligatoires.');
            Flight::redirect(base_url('/villes/nouveau'));
            return;
        }

        try {
            $villeModel->create($data);
            Flight::flash('success', 'Ville ajoutée avec succès.');
            Flight::redirect(base_url('/villes'));
        } catch (\Exception $e) {
            Flight::flash('error', 'Erreur: cette ville existe peut-être déjà.');
            Flight::redirect(base_url('/villes/nouveau'));
        }
    }

    public static function edit($id) {
        $villeModel = new Ville();
        $ville = $villeModel->findById($id);

        if (!$ville) {
            Flight::render('errors/404', ['title' => 'Ville non trouvée']);
            return;
        }

        Flight::render('ville/edit', [
            'title' => 'BNGRC - Modifier ' . $ville['nom'],
            'ville' => $ville
        ]);
    }

    public static function update($id) {
        $villeModel = new Ville();
        
        $data = [
            'nom' => trim($_POST['nom'] ?? ''),
            'region' => trim($_POST['region'] ?? ''),
            'population' => intval($_POST['population'] ?? 0)
        ];

        if (empty($data['nom']) || empty($data['region'])) {
            Flight::flash('error', 'Le nom et la région sont obligatoires.');
            Flight::redirect(base_url('/villes/' . $id . '/editer'));
            return;
        }

        try {
            $villeModel->update($id, $data);
            Flight::flash('success', 'Ville modifiée avec succès.');
            Flight::redirect(base_url('/villes'));
        } catch (\Exception $e) {
            Flight::flash('error', 'Erreur lors de la modification.');
            Flight::redirect(base_url('/villes/' . $id . '/editer'));
        }
    }

    public static function delete($id) {
        $villeModel = new Ville();
        try {
            $villeModel->delete($id);
            Flight::flash('success', 'Ville supprimée avec succès.');
        } catch (\Exception $e) {
            Flight::flash('error', 'Erreur lors de la suppression.');
        }
        Flight::redirect(base_url('/villes'));
    }
}
