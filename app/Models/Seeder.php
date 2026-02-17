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
            ['Mananjary', 'Vatovavy-Fitovinany', 35000],
            ['Ikongo', 'Vatovavy-Fitovinany', 22000],
            ['Nosy Varika', 'Vatovavy-Fitovinany', 18000],
            ['Farafangana', 'Atsimo-Atsinanana', 40000],
            ['Vangaindrano', 'Atsimo-Atsinanana', 28000],
            ['Mahanoro', 'Atsinanana', 30000]
        ];
        foreach ($villes as $v) {
            $stmt->execute($v);
        }
    }

    private function seedBesoins() {
        $stmt = $this->db->prepare("INSERT INTO besoins (ville_id, type_besoin_id, designation, prix_unitaire, quantite) VALUES (?, ?, ?, ?, ?)");

        // Mananjary (id=1)
        $besoins = [
            [1, 1, 'Riz (kg)', 2500.00, 5000],
            [1, 1, 'Huile (litre)', 8000.00, 1000],
            [1, 2, 'Tôle (feuille)', 45000.00, 500],
            [1, 2, 'Clou (kg)', 6000.00, 200],
            [1, 3, 'Aide financière (Ar)', 1.00, 50000000],

            // Ikongo (id=2)
            [2, 1, 'Riz (kg)', 2500.00, 3000],
            [2, 1, 'Eau potable (litre)', 500.00, 10000],
            [2, 2, 'Bois (planche)', 15000.00, 300],
            [2, 3, 'Aide financière (Ar)', 1.00, 20000000],

            // Nosy Varika (id=3)
            [3, 1, 'Riz (kg)', 2500.00, 2000],
            [3, 2, 'Ciment (sac)', 35000.00, 100],
            [3, 2, 'Tôle (feuille)', 45000.00, 200],

            // Farafangana (id=4)
            [4, 1, 'Riz (kg)', 2500.00, 8000],
            [4, 1, 'Huile (litre)', 8000.00, 2000],
            [4, 2, 'Tôle (feuille)', 45000.00, 800],
            [4, 3, 'Aide financière (Ar)', 1.00, 80000000],
        ];

        foreach ($besoins as $b) {
            $stmt->execute($b);
        }
    }

    private function seedDons() {
        $stmt = $this->db->prepare("INSERT INTO dons (donateur, type_besoin_id, designation, quantite, date_don) VALUES (?, ?, ?, ?, ?)");

        $dons = [
            ['Croix Rouge', 1, 'Riz (kg)', 3000, '2026-02-01'],
            ['UNICEF', 1, 'Huile (litre)', 500, '2026-02-02'],
            ['Gouvernement', 2, 'Tôle (feuille)', 300, '2026-02-03'],
            ['Association Solidarité', 1, 'Riz (kg)', 2000, '2026-02-05'],
            ['Banque Mondiale', 3, 'Aide financière (Ar)', 30000000, '2026-02-06'],
            ['Anonyme', 2, 'Clou (kg)', 150, '2026-02-07'],
            ['ONG Care', 1, 'Eau potable (litre)', 5000, '2026-02-08'],
            ['Communauté locale', 2, 'Bois (planche)', 100, '2026-02-10'],
        ];

        foreach ($dons as $d) {
            $stmt->execute($d);
        }
    }
}
