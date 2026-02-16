<?php
namespace App\Models;

use Flight;

class Don extends BaseModel {

    public function getAll() {
        $sql = "SELECT d.*, tb.nom as type_nom
                FROM dons d
                JOIN types_besoin tb ON d.type_besoin_id = tb.id
                ORDER BY d.date_don DESC, d.id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $sql = "SELECT d.*, tb.nom as type_nom
                FROM dons d
                JOIN types_besoin tb ON d.type_besoin_id = tb.id
                WHERE d.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO dons (donateur, type_besoin_id, designation, quantite, date_don) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['donateur'] ?: 'Anonyme',
            $data['type_besoin_id'],
            $data['designation'],
            $data['quantite'],
            $data['date_don']
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE dons SET donateur = ?, type_besoin_id = ?, designation = ?, quantite = ?, date_don = ? WHERE id = ?");
        return $stmt->execute([
            $data['donateur'] ?: 'Anonyme',
            $data['type_besoin_id'],
            $data['designation'],
            $data['quantite'],
            $data['date_don'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM dons WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function countAll() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM dons");
        $row = $stmt->fetch();
        return $row['total'];
    }

    public function getTotalQuantity() {
        $stmt = $this->db->query("SELECT COALESCE(SUM(quantite), 0) as total FROM dons");
        $row = $stmt->fetch();
        return $row['total'];
    }

    public function getUndispatched() {
        $sql = "SELECT d.*, tb.nom as type_nom,
                    COALESCE(disp.quantite_dispatched, 0) as quantite_dispatched,
                    (d.quantite - COALESCE(disp.quantite_dispatched, 0)) as quantite_restante
                FROM dons d
                JOIN types_besoin tb ON d.type_besoin_id = tb.id
                LEFT JOIN (
                    SELECT don_id, SUM(quantite_attribuee) as quantite_dispatched
                    FROM dispatches GROUP BY don_id
                ) disp ON d.id = disp.don_id
                HAVING quantite_restante > 0
                ORDER BY d.date_don ASC, d.id ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getRecentDons($limit = 10) {
        $limit = (int) $limit;
        $sql = "SELECT d.*, tb.nom as type_nom
                FROM dons d
                JOIN types_besoin tb ON d.type_besoin_id = tb.id
                ORDER BY d.date_don DESC, d.id DESC LIMIT {$limit}";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
