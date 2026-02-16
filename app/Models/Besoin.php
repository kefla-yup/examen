<?php
namespace App\Models;

use Flight;

class Besoin extends BaseModel {

    public function getAll() {
        $sql = "SELECT b.*, v.nom as ville_nom, v.region, tb.nom as type_nom
                FROM besoins b
                JOIN villes v ON b.ville_id = v.id
                JOIN types_besoin tb ON b.type_besoin_id = tb.id
                ORDER BY v.nom ASC, tb.nom ASC, b.designation ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $sql = "SELECT b.*, v.nom as ville_nom, tb.nom as type_nom
                FROM besoins b
                JOIN villes v ON b.ville_id = v.id
                JOIN types_besoin tb ON b.type_besoin_id = tb.id
                WHERE b.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByVille($villeId) {
        $sql = "SELECT b.*, tb.nom as type_nom,
                    COALESCE(d.quantite_distribuee, 0) as quantite_distribuee,
                    (b.quantite - COALESCE(d.quantite_distribuee, 0)) as quantite_restante
                FROM besoins b
                JOIN types_besoin tb ON b.type_besoin_id = tb.id
                LEFT JOIN (
                    SELECT besoin_id, SUM(quantite_attribuee) as quantite_distribuee
                    FROM dispatches GROUP BY besoin_id
                ) d ON b.id = d.besoin_id
                WHERE b.ville_id = ?
                ORDER BY tb.nom ASC, b.designation ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$villeId]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO besoins (ville_id, type_besoin_id, designation, prix_unitaire, quantite) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['ville_id'],
            $data['type_besoin_id'],
            $data['designation'],
            $data['prix_unitaire'],
            $data['quantite']
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE besoins SET ville_id = ?, type_besoin_id = ?, designation = ?, quantite = ? WHERE id = ?");
        return $stmt->execute([
            $data['ville_id'],
            $data['type_besoin_id'],
            $data['designation'],
            $data['quantite'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM besoins WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function countAll() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM besoins");
        $row = $stmt->fetch();
        return $row['total'];
    }

    public function getTotalValue() {
        $stmt = $this->db->query("SELECT COALESCE(SUM(prix_unitaire * quantite), 0) as total FROM besoins");
        $row = $stmt->fetch();
        return $row['total'];
    }

    public function getTypes() {
        $stmt = $this->db->query("SELECT * FROM types_besoin ORDER BY id ASC");
        return $stmt->fetchAll();
    }

    public function getUnfulfilled() {
        $sql = "SELECT b.*, v.nom as ville_nom, tb.nom as type_nom,
                    COALESCE(d.quantite_distribuee, 0) as quantite_distribuee,
                    (b.quantite - COALESCE(d.quantite_distribuee, 0)) as quantite_restante
                FROM besoins b
                JOIN villes v ON b.ville_id = v.id
                JOIN types_besoin tb ON b.type_besoin_id = tb.id
                LEFT JOIN (
                    SELECT besoin_id, SUM(quantite_attribuee) as quantite_distribuee
                    FROM dispatches GROUP BY besoin_id
                ) d ON b.id = d.besoin_id
                HAVING quantite_restante > 0
                ORDER BY v.nom ASC, b.designation ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
