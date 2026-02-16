<?php
namespace App\Models;

use Flight;

class Dispatch extends BaseModel {

    public function getAll() {
        $sql = "SELECT disp.*, d.donateur, d.designation as don_designation, 
                    v.nom as ville_nom, b.designation as besoin_designation
                FROM dispatches disp
                JOIN dons d ON disp.don_id = d.id
                JOIN villes v ON disp.ville_id = v.id
                JOIN besoins b ON disp.besoin_id = b.id
                ORDER BY disp.date_dispatch DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getByVille($villeId) {
        $sql = "SELECT disp.*, d.donateur, d.designation as don_designation,
                    b.designation as besoin_designation
                FROM dispatches disp
                JOIN dons d ON disp.don_id = d.id
                JOIN besoins b ON disp.besoin_id = b.id
                WHERE disp.ville_id = ?
                ORDER BY disp.date_dispatch DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$villeId]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO dispatches (don_id, ville_id, besoin_id, quantite_attribuee) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data['don_id'],
            $data['ville_id'],
            $data['besoin_id'],
            $data['quantite_attribuee']
        ]);
        return $this->db->lastInsertId();
    }

    public function countAll() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM dispatches");
        $row = $stmt->fetch();
        return $row['total'];
    }

    public function resetAll() {
        $this->db->exec("DELETE FROM dispatches");
        return true;
    }


    public function simulerDispatch() {
        // Reset existing dispatches
        $this->resetAll();

        // Get all dons ordered by date and entry
        $donsSql = "SELECT d.* FROM dons d ORDER BY d.date_don ASC, d.id ASC";
        $donsStmt = $this->db->query($donsSql);
        $dons = $donsStmt->fetchAll();

        $totalDispatched = 0;

        foreach ($dons as $don) {
            $resteDon = $don['quantite'];

            $besoinsSql = "SELECT b.*, v.nom as ville_nom,
                            COALESCE(disp.qty, 0) as deja_distribue,
                            (b.quantite - COALESCE(disp.qty, 0)) as reste_besoin
                          FROM besoins b
                          JOIN villes v ON b.ville_id = v.id
                          LEFT JOIN (
                              SELECT besoin_id, SUM(quantite_attribuee) as qty FROM dispatches GROUP BY besoin_id
                          ) disp ON b.id = disp.besoin_id
                          WHERE b.designation = ? AND b.type_besoin_id = ?
                          HAVING reste_besoin > 0
                          ORDER BY reste_besoin DESC, b.ville_id ASC";
            
            $besoinsStmt = $this->db->prepare($besoinsSql);
            $besoinsStmt->execute([$don['designation'], $don['type_besoin_id']]);
            $besoins = $besoinsStmt->fetchAll();

            foreach ($besoins as $besoin) {
                if ($resteDon <= 0) break;

                $aDistribuer = min($resteDon, $besoin['reste_besoin']);

                if ($aDistribuer > 0) {
                    $this->create([
                        'don_id' => $don['id'],
                        'ville_id' => $besoin['ville_id'],
                        'besoin_id' => $besoin['id'],
                        'quantite_attribuee' => $aDistribuer
                    ]);
                    $resteDon -= $aDistribuer;
                    $totalDispatched += $aDistribuer;
                }
            }
        }

        return $totalDispatched;
    }

    public function getStatsByVille() {
        $sql = "SELECT v.id, v.nom as ville_nom, v.region,
                    COUNT(disp.id) as nb_dispatches,
                    COALESCE(SUM(disp.quantite_attribuee), 0) as total_distribue
                FROM villes v
                LEFT JOIN dispatches disp ON v.id = disp.ville_id
                GROUP BY v.id
                ORDER BY v.nom ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
