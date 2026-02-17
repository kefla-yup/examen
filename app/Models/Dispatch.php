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


    public function simulerPreview() {
        // Simule le dispatch SANS écrire en base : retourne le résultat prévu
        $donsSql = "SELECT d.* FROM dons d ORDER BY d.date_don ASC, d.id ASC";
        $donsStmt = $this->db->query($donsSql);
        $dons = $donsStmt->fetchAll();

        // Charger les dispatches existants pour calculer les restes
        $existingDisp = [];
        $edStmt = $this->db->query("SELECT besoin_id, SUM(quantite_attribuee) as qty FROM dispatches GROUP BY besoin_id");
        foreach ($edStmt->fetchAll() as $row) {
            $existingDisp[$row['besoin_id']] = (int)$row['qty'];
        }

        // Charger les dons déjà dispatched
        $donDisp = [];
        $ddStmt = $this->db->query("SELECT don_id, SUM(quantite_attribuee) as qty FROM dispatches GROUP BY don_id");
        foreach ($ddStmt->fetchAll() as $row) {
            $donDisp[$row['don_id']] = (int)$row['qty'];
        }

        $simulationResults = [];
        $totalDispatched = 0;
        // Copier les restes pour la simulation
        $simBesoinReste = $existingDisp;

        foreach ($dons as $don) {
            $resteDon = $don['quantite'] - ($donDisp[$don['id']] ?? 0);
            if ($resteDon <= 0) continue;

            $besoinsSql = "SELECT b.*, v.nom as ville_nom
                          FROM besoins b
                          JOIN villes v ON b.ville_id = v.id
                          WHERE b.designation = ? AND b.type_besoin_id = ?
                          ORDER BY b.quantite DESC, b.ville_id ASC";
            $besoinsStmt = $this->db->prepare($besoinsSql);
            $besoinsStmt->execute([$don['designation'], $don['type_besoin_id']]);
            $besoins = $besoinsStmt->fetchAll();

            foreach ($besoins as $besoin) {
                if ($resteDon <= 0) break;

                $dejaDist = $simBesoinReste[$besoin['id']] ?? 0;
                $resteBesoin = $besoin['quantite'] - $dejaDist;
                if ($resteBesoin <= 0) continue;

                $aDistribuer = min($resteDon, $resteBesoin);

                if ($aDistribuer > 0) {
                    $simulationResults[] = [
                        'donateur' => $don['donateur'],
                        'don_designation' => $don['designation'],
                        'ville_nom' => $besoin['ville_nom'],
                        'ville_id' => $besoin['ville_id'],
                        'besoin_designation' => $besoin['designation'],
                        'besoin_id' => $besoin['id'],
                        'don_id' => $don['id'],
                        'quantite_attribuee' => $aDistribuer
                    ];
                    $resteDon -= $aDistribuer;
                    $totalDispatched += $aDistribuer;
                    $simBesoinReste[$besoin['id']] = $dejaDist + $aDistribuer;
                }
            }
        }

        return [
            'dispatches' => $simulationResults,
            'total' => $totalDispatched
        ];
    }

    /**
     * Exécute le dispatch à partir des données de simulation stockées en session
     */
    public function executerDepuisSimulation($simulationDispatches) {
        $totalDispatched = 0;
        
        $this->db->beginTransaction();
        try {
            foreach ($simulationDispatches as $sim) {
                $this->create([
                    'don_id' => $sim['don_id'],
                    'ville_id' => $sim['ville_id'],
                    'besoin_id' => $sim['besoin_id'],
                    'quantite_attribuee' => $sim['quantite_attribuee']
                ]);
                $totalDispatched += $sim['quantite_attribuee'];
            }
            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
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
