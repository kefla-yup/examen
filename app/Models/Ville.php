<?php
namespace App\Models;

use Flight;

class Ville extends BaseModel {
    
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM villes ORDER BY nom ASC");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM villes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO villes (nom, region, population) VALUES (?, ?, ?)");
        $stmt->execute([$data['nom'], $data['region'], $data['population'] ?? 0]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE villes SET nom = ?, region = ?, population = ? WHERE id = ?");
        return $stmt->execute([$data['nom'], $data['region'], $data['population'] ?? 0, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM villes WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function countAll() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM villes");
        $row = $stmt->fetch();
        return $row['total'];
    }

    public function getWithStats() {
        $sql = "SELECT v.*, 
                    COALESCE(b.total_besoins, 0) as total_besoins,
                    COALESCE(b.valeur_besoins, 0) as valeur_besoins,
                    COALESCE(d.total_dispatched, 0) as total_dispatched
                FROM villes v
                LEFT JOIN (
                    SELECT ville_id, COUNT(*) as total_besoins, SUM(prix_unitaire * quantite) as valeur_besoins
                    FROM besoins GROUP BY ville_id
                ) b ON v.id = b.ville_id
                LEFT JOIN (
                    SELECT ville_id, SUM(quantite_attribuee) as total_dispatched
                    FROM dispatches GROUP BY ville_id
                ) d ON v.id = d.ville_id
                ORDER BY v.nom ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getRegions() {
        $stmt = $this->db->query("SELECT DISTINCT region FROM villes ORDER BY region ASC");
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
}
