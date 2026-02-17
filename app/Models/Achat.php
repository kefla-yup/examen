<?php
namespace App\Models;

use Flight;

class Achat extends BaseModel {

    /**
     * Frais d'achat en pourcentage (configurable)
     */
    const FRAIS_POURCENTAGE = 10;

    public function getAll() {
        $sql = "SELECT a.*, b.designation as besoin_designation, b.prix_unitaire,
                    v.nom as ville_nom, tb.nom as type_nom
                FROM achats a
                JOIN besoins b ON a.besoin_id = b.id
                JOIN villes v ON a.ville_id = v.id
                JOIN types_besoin tb ON b.type_besoin_id = tb.id
                ORDER BY a.date_achat DESC, a.id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getAllFiltered($villeId = null) {
        $sql = "SELECT a.*, b.designation as besoin_designation, b.prix_unitaire,
                    v.nom as ville_nom, tb.nom as type_nom
                FROM achats a
                JOIN besoins b ON a.besoin_id = b.id
                JOIN villes v ON a.ville_id = v.id
                JOIN types_besoin tb ON b.type_besoin_id = tb.id";
        $params = [];
        if ($villeId) {
            $sql .= " WHERE a.ville_id = ?";
            $params[] = $villeId;
        }
        $sql .= " ORDER BY a.date_achat DESC, a.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $sql = "SELECT a.*, b.designation as besoin_designation, b.prix_unitaire,
                    v.nom as ville_nom
                FROM achats a
                JOIN besoins b ON a.besoin_id = b.id
                JOIN villes v ON a.ville_id = v.id
                WHERE a.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO achats (besoin_id, ville_id, quantite, montant_ht, frais_pourcent, montant_ttc, date_achat) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['besoin_id'],
            $data['ville_id'],
            $data['quantite'],
            $data['montant_ht'],
            $data['frais_pourcent'],
            $data['montant_ttc'],
            $data['date_achat'] ?? date('Y-m-d')
        ]);
        return $this->db->lastInsertId();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM achats WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Vérifie si un achat existe déjà pour ce besoin dans les dons restants (Argent)
     */
    public function achatExistePourBesoin($besoinId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM achats WHERE besoin_id = ?");
        $stmt->execute([$besoinId]);
        $row = $stmt->fetch();
        return $row['total'] > 0;
    }

    /**
     * Obtenir le total des dons en Argent non encore utilisés
     */
    public function getDonsArgentDisponibles() {
        $sql = "SELECT d.*, 
                    COALESCE(disp.quantite_dispatched, 0) as quantite_dispatched,
                    COALESCE(ach.montant_utilise, 0) as montant_utilise,
                    (d.quantite - COALESCE(disp.quantite_dispatched, 0) - COALESCE(ach.montant_utilise, 0)) as montant_restant
                FROM dons d
                JOIN types_besoin tb ON d.type_besoin_id = tb.id
                LEFT JOIN (
                    SELECT don_id, SUM(quantite_attribuee) as quantite_dispatched
                    FROM dispatches GROUP BY don_id
                ) disp ON d.id = disp.don_id
                LEFT JOIN (
                    SELECT SUM(montant_ttc) as montant_utilise FROM achats
                ) ach ON 1=1
                WHERE tb.nom = 'Argent'
                HAVING montant_restant > 0
                ORDER BY d.date_don ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Obtenir le solde total des dons Argent disponibles
     */
    public function getSoldeArgentDisponible() {
        $sql = "SELECT 
                    COALESCE(SUM(d.quantite), 0) as total_dons_argent,
                    COALESCE((SELECT SUM(quantite_attribuee) FROM dispatches disp 
                              JOIN dons don2 ON disp.don_id = don2.id 
                              JOIN types_besoin tb2 ON don2.type_besoin_id = tb2.id 
                              WHERE tb2.nom = 'Argent'), 0) as total_dispatched_argent,
                    COALESCE((SELECT SUM(montant_ttc) FROM achats), 0) as total_achats
                FROM dons d
                JOIN types_besoin tb ON d.type_besoin_id = tb.id
                WHERE tb.nom = 'Argent'";
        $stmt = $this->db->query($sql);
        $row = $stmt->fetch();
        return $row['total_dons_argent'] - $row['total_dispatched_argent'] - $row['total_achats'];
    }

    /**
     * Besoins restants (Nature et Matériaux) qui peuvent être achetés
     */
    public function getBesoinsRestantsAchetables($villeId = null) {
        $sql = "SELECT b.*, v.nom as ville_nom, v.id as ville_id, tb.nom as type_nom,
                    COALESCE(d.quantite_distribuee, 0) as quantite_distribuee,
                    COALESCE(ach.quantite_achetee, 0) as quantite_achetee,
                    (b.quantite - COALESCE(d.quantite_distribuee, 0) - COALESCE(ach.quantite_achetee, 0)) as quantite_restante
                FROM besoins b
                JOIN villes v ON b.ville_id = v.id
                JOIN types_besoin tb ON b.type_besoin_id = tb.id
                LEFT JOIN (
                    SELECT besoin_id, SUM(quantite_attribuee) as quantite_distribuee
                    FROM dispatches GROUP BY besoin_id
                ) d ON b.id = d.besoin_id
                LEFT JOIN (
                    SELECT besoin_id, SUM(quantite) as quantite_achetee
                    FROM achats GROUP BY besoin_id
                ) ach ON b.id = ach.besoin_id
                WHERE tb.nom IN ('Nature', 'Matériaux')";
        $params = [];
        if ($villeId) {
            $sql .= " AND b.ville_id = ?";
            $params[] = $villeId;
        }
        $sql .= " HAVING quantite_restante > 0
                ORDER BY v.nom ASC, b.designation ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getTotalAchats() {
        $stmt = $this->db->query("SELECT COALESCE(SUM(montant_ttc), 0) as total FROM achats");
        $row = $stmt->fetch();
        return $row['total'];
    }

    public function countAll() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM achats");
        $row = $stmt->fetch();
        return $row['total'];
    }

    /**
     * Calculer le montant avec frais
     */
    public static function calculerMontantTTC($montantHT, $fraisPourcent = null) {
        if ($fraisPourcent === null) {
            $fraisPourcent = self::FRAIS_POURCENTAGE;
        }
        return $montantHT * (1 + $fraisPourcent / 100);
    }
}
