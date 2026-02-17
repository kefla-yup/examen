<?php
namespace App\Models;

use Flight;

class Seeder extends BaseModel {

    /**
     * Réinitialiser la base de données aux données par défaut
     */
    public function resetToDefault() {
        $this->db->beginTransaction();
        try {
            // Désactiver les contraintes FK temporairement
            $this->db->exec("SET FOREIGN_KEY_CHECKS = 0");

            // Vider toutes les tables
            $this->db->exec("TRUNCATE TABLE achats");
            $this->db->exec("TRUNCATE TABLE dispatches");
            $this->db->exec("TRUNCATE TABLE dons");
            $this->db->exec("TRUNCATE TABLE besoins");
            $this->db->exec("TRUNCATE TABLE villes");
            $this->db->exec("TRUNCATE TABLE types_besoin");

            // Réactiver les contraintes FK
            $this->db->exec("SET FOREIGN_KEY_CHECKS = 1");

            // Insérer les types de besoin
            $this->seedTypesBesoin();

            // Insérer les villes
            $this->seedVilles();

            // Insérer les besoins
            $this->seedBesoins();

            // Insérer les dons
            $this->seedDons();

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    private function seedTypesBesoin() {
        $stmt = $this->db->prepare("INSERT INTO types_besoin (nom, description) VALUES (?, ?)");
        $types = [
            ['Nature', 'Besoins en nature : riz, huile, eau, nourriture...'],
            ['Matériaux', 'Besoins en matériaux : tôle, clou, bois, ciment...'],
            ['Argent', 'Besoins financiers en Ariary']
        ];
        foreach ($types as $t) {
            $stmt->execute($t);
        }
    }

    private function seedVilles() {
        $stmt = $this->db->prepare("INSERT INTO villes (nom, region, population) VALUES (?, ?, ?)");
        $villes = [
            ['Toamasina', 'Atsinanana', 55000],
            ['Mananjary', 'Vatovavy-Fitovinany', 35000],
            ['Farafangana', 'Atsimo-Atsinanana', 40000],
            ['Nosy Be', 'Diana', 45000],
            ['Morondava', 'Menabe', 38000]
        ];
        foreach ($villes as $v) {
            $stmt->execute($v);
        }
    }

    private function seedBesoins() {
        $stmt = $this->db->prepare("INSERT INTO besoins (ville_id, type_besoin_id, designation, prix_unitaire, quantite) VALUES (?, ?, ?, ?, ?)");

        $besoins = [
            // Toamasina (id=1)
            [1, 1, 'Riz (kg)', 3000.00, 800],
            [1, 1, 'Eau (L)', 1000.00, 1500],
            [1, 2, 'Tôle', 25000.00, 120],
            [1, 2, 'Bâche', 15000.00, 200],
            [1, 3, 'Argent', 1.00, 12000000],
            [1, 2, 'Groupe', 6750000.00, 3],

            // Mananjary (id=2)
            [2, 1, 'Riz (kg)', 3000.00, 500],
            [2, 1, 'Huile (L)', 6000.00, 120],
            [2, 2, 'Tôle', 25000.00, 80],
            [2, 2, 'Clous (kg)', 8000.00, 60],
            [2, 3, 'Argent', 1.00, 6000000],

            // Farafangana (id=3)
            [3, 1, 'Riz (kg)', 3000.00, 600],
            [3, 1, 'Eau (L)', 1000.00, 1000],
            [3, 2, 'Bâche', 15000.00, 150],
            [3, 2, 'Bois', 10000.00, 100],
            [3, 3, 'Argent', 1.00, 8000000],

            // Nosy Be (id=4)
            [4, 1, 'Riz (kg)', 3000.00, 300],
            [4, 1, 'Haricots', 4000.00, 200],
            [4, 2, 'Tôle', 25000.00, 40],
            [4, 2, 'Clous (kg)', 8000.00, 30],
            [4, 3, 'Argent', 1.00, 4000000],

            // Morondava (id=5)
            [5, 1, 'Riz (kg)', 3000.00, 700],
            [5, 1, 'Eau (L)', 1000.00, 1200],
            [5, 2, 'Bâche', 15000.00, 180],
            [5, 2, 'Bois', 10000.00, 150],
            [5, 3, 'Argent', 1.00, 10000000],
        ];

        foreach ($besoins as $b) {
            $stmt->execute($b);
        }
    }

    private function seedDons() {
        $stmt = $this->db->prepare("INSERT INTO dons (donateur, type_besoin_id, designation, quantite, date_don) VALUES (?, ?, ?, ?, ?)");

        $dons = [
            ['Anonyme', 3, 'Argent', 5000000, '2026-02-16'],
            ['Anonyme', 3, 'Argent', 3000000, '2026-02-16'],
            ['Anonyme', 3, 'Argent', 4000000, '2026-02-17'],
            ['Anonyme', 3, 'Argent', 1500000, '2026-02-17'],
            ['Anonyme', 3, 'Argent', 6000000, '2026-02-17'],
            ['Anonyme', 1, 'Riz (kg)', 400, '2026-02-16'],
            ['Anonyme', 1, 'Eau (L)', 600, '2026-02-16'],
            ['Anonyme', 2, 'Tôle', 50, '2026-02-17'],
            ['Anonyme', 2, 'Bâche', 70, '2026-02-17'],
            ['Anonyme', 1, 'Haricots', 100, '2026-02-17'],
            ['Anonyme', 1, 'Riz (kg)', 2000, '2026-02-18'],
            ['Anonyme', 2, 'Tôle', 300, '2026-02-18'],
            ['Anonyme', 1, 'Eau (L)', 5000, '2026-02-18'],
            ['Anonyme', 3, 'Argent', 20000000, '2026-02-19'],
            ['Anonyme', 2, 'Bâche', 500, '2026-02-19'],
            ['Anonyme', 1, 'Haricots', 88, '2026-02-17'],
        ];

        foreach ($dons as $d) {
            $stmt->execute($d);
        }
    }
}
