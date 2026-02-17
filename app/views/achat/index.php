<?php include __DIR__ . '/../layout/header.php'; ?>

<section class="container py-4">
    <div class="page-header">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('/'); ?>"><i class='bx bxs-dashboard'></i> Tableau de bord</a></li>
                    <li class="breadcrumb-item active">Achats</li>
                </ol>
            </nav>
            <h1 class="page-title"><i class='bx bxs-cart me-2'></i>Achats via Dons Argent</h1>
            <p class="page-subtitle">Acheter les besoins en Nature et Matériaux avec les dons en Argent</p>
        </div>
        <form method="POST" action="<?php echo base_url('reinitialiser'); ?>" class="d-inline">
            <input type="hidden" name="redirect" value="achats">
            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Réinitialiser toutes les données aux valeurs par défaut ? Les données ajoutées seront perdues.');">
                <i class='bx bx-reset me-1'></i>Réinitialiser
            </button>
        </form>
    </div>

    <!-- Solde et infos -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card stat-card-success">
                <div class="stat-card-icon">
                    <i class='bx bx-money'></i>
                </div>
                <div class="stat-card-content">
                    <span class="stat-card-value"><?php echo number_format($soldeArgent, 0, ',', ' '); ?></span>
                    <span class="stat-card-label">Solde Argent Disponible (Ar)</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card stat-card-warning">
                <div class="stat-card-icon">
                    <i class='bx bx-cart'></i>
                </div>
                <div class="stat-card-content">
                    <span class="stat-card-value"><?php echo number_format($totalAchats, 0, ',', ' '); ?></span>
                    <span class="stat-card-label">Total Achats (Ar)</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card stat-card-primary">
                <div class="stat-card-icon">
                    <i class='bx bx-receipt'></i>
                </div>
                <div class="stat-card-content">
                    <span class="stat-card-value"><?php echo $fraisPourcent; ?>%</span>
                    <span class="stat-card-label">Frais d'achat</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Card -->
    <div class="info-card mb-4">
        <div class="info-card-icon">
            <i class='bx bx-info-circle'></i>
        </div>
        <div>
            <h6 class="mb-1">Comment fonctionnent les achats ?</h6>
            <p class="mb-0 small text-muted">
                Vous pouvez acheter les besoins en <strong>Nature</strong> et <strong>Matériaux</strong> en utilisant les dons en <strong>Argent</strong> disponibles.
                Un frais d'achat de <strong><?php echo $fraisPourcent; ?>%</strong> est appliqué automatiquement.
                Exemple : un achat de 100 000 Ar coûtera <strong><?php echo number_format(100000 * (1 + $fraisPourcent/100), 0, ',', ' '); ?> Ar</strong>.
                Un seul achat est autorisé par besoin.
            </p>
        </div>
    </div>

    <!-- Filtre par ville -->
    <div class="filter-bar mb-4">
        <form method="GET" action="<?php echo base_url('achats'); ?>" class="d-flex align-items-center gap-3 flex-wrap">
            <label class="fw-semibold text-muted"><i class='bx bx-filter me-1'></i>Filtrer par ville :</label>
            <select name="ville_id" class="form-select form-select-sm" style="max-width: 250px;" onchange="this.form.submit()">
                <option value="">Toutes les villes</option>
                <?php foreach($villes as $v): ?>
                    <option value="<?php echo $v['id']; ?>" <?php echo $villeFilter == $v['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($v['nom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <!-- Besoins restants achetables -->
    <div class="section-header mt-4">
        <h3 class="section-title"><i class='bx bx-list-check me-2'></i>Besoins Restants à Acheter</h3>
    </div>
    <div class="data-table-card mb-5">
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ville</th>
                        <th>Désignation</th>
                        <th>Type</th>
                        <th>Prix Unitaire</th>
                        <th>Qté Restante</th>
                        <th>Coût HT</th>
                        <th>Coût TTC (<?php echo $fraisPourcent; ?>%)</th>
                        <th>Acheter</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($besoinsRestants)): ?>
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class='bx bx-check-circle fs-3 d-block mb-2 text-success'></i>
                            Tous les besoins en Nature et Matériaux sont satisfaits !
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php $i = 1; foreach($besoinsRestants as $besoin): ?>
                        <?php 
                            $coutHT = $besoin['prix_unitaire'] * $besoin['quantite_restante'];
                            $coutTTC = $coutHT * (1 + $fraisPourcent / 100);
                        ?>
                        <tr>
                            <td class="text-muted"><?php echo $i++; ?></td>
                            <td>
                                <i class='bx bxs-map-pin text-primary me-1'></i><?php echo htmlspecialchars($besoin['ville_nom']); ?>
                            </td>
                            <td class="fw-medium"><?php echo htmlspecialchars($besoin['designation']); ?></td>
                            <td>
                                <span class="type-badge type-<?php echo strtolower($besoin['type_nom']); ?>">
                                    <?php echo htmlspecialchars($besoin['type_nom']); ?>
                                </span>
                            </td>
                            <td><?php echo number_format($besoin['prix_unitaire'], 0, ',', ' '); ?> Ar</td>
                            <td class="fw-bold"><?php echo number_format($besoin['quantite_restante'], 0, ',', ' '); ?></td>
                            <td><?php echo number_format($coutHT, 0, ',', ' '); ?> Ar</td>
                            <td class="fw-bold text-danger"><?php echo number_format($coutTTC, 0, ',', ' '); ?> Ar</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#achatModal"
                                    data-besoin-id="<?php echo $besoin['id']; ?>"
                                    data-designation="<?php echo htmlspecialchars($besoin['designation']); ?>"
                                    data-ville="<?php echo htmlspecialchars($besoin['ville_nom']); ?>"
                                    data-prix="<?php echo $besoin['prix_unitaire']; ?>"
                                    data-max="<?php echo $besoin['quantite_restante']; ?>"
                                    data-frais="<?php echo $fraisPourcent; ?>">
                                    <i class='bx bx-cart-add me-1'></i>Acheter
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Liste des achats effectués -->
    <div class="section-header">
        <h3 class="section-title"><i class='bx bx-receipt me-2'></i>Achats Effectués</h3>
    </div>
    <div class="data-table-card">
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Ville</th>
                        <th>Besoin</th>
                        <th>Quantité</th>
                        <th>Montant HT</th>
                        <th>Frais</th>
                        <th>Montant TTC</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($achats)): ?>
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class='bx bxs-cart fs-3 d-block mb-2'></i>
                            Aucun achat effectué
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php $i = 1; foreach($achats as $achat): ?>
                        <tr>
                            <td class="text-muted"><?php echo $i++; ?></td>
                            <td>
                                <span class="date-badge">
                                    <?php echo date('d/m/Y', strtotime($achat['date_achat'])); ?>
                                </span>
                            </td>
                            <td>
                                <i class='bx bxs-map-pin text-primary me-1'></i><?php echo htmlspecialchars($achat['ville_nom']); ?>
                            </td>
                            <td class="fw-medium"><?php echo htmlspecialchars($achat['besoin_designation']); ?></td>
                            <td class="fw-bold"><?php echo number_format($achat['quantite'], 0, ',', ' '); ?></td>
                            <td><?php echo number_format($achat['montant_ht'], 0, ',', ' '); ?> Ar</td>
                            <td><span class="badge bg-warning text-dark"><?php echo $achat['frais_pourcent']; ?>%</span></td>
                            <td class="fw-bold text-danger"><?php echo number_format($achat['montant_ttc'], 0, ',', ' '); ?> Ar</td>
                            <td>
                                <form method="POST" action="<?php echo base_url('achats/' . $achat['id'] . '/supprimer'); ?>" class="d-inline" onsubmit="return confirm('Supprimer cet achat ?');">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Modal Achat -->
<div class="modal fade" id="achatModal" tabindex="-1" aria-labelledby="achatModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?php echo base_url('achats/effectuer'); ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="achatModalLabel"><i class='bx bx-cart me-2'></i>Effectuer un Achat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="besoin_id" id="modal_besoin_id">
                    <input type="hidden" name="frais_pourcent" id="modal_frais_pourcent" value="<?php echo $fraisPourcent; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ville</label>
                        <p id="modal_ville" class="form-control-plaintext"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Désignation</label>
                        <p id="modal_designation" class="form-control-plaintext"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Prix unitaire</label>
                        <p id="modal_prix_display" class="form-control-plaintext"></p>
                    </div>
                    <div class="mb-3">
                        <label for="modal_quantite" class="form-label fw-bold">Quantité à acheter <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="modal_quantite" name="quantite" min="1" required>
                        <small class="text-muted">Maximum : <span id="modal_max">0</span></small>
                    </div>
                    <div class="alert alert-info" id="modal_calcul">
                        <div class="d-flex justify-content-between">
                            <span>Montant HT :</span>
                            <strong id="modal_montant_ht">0 Ar</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Frais (<span id="modal_frais_label"><?php echo $fraisPourcent; ?></span>%) :</span>
                            <strong id="modal_frais_montant">0 Ar</strong>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between text-danger">
                            <span class="fw-bold">Montant TTC :</span>
                            <strong id="modal_montant_ttc">0 Ar</strong>
                        </div>
                    </div>
                    <div class="alert alert-warning small">
                        <i class='bx bx-info-circle me-1'></i>Solde Argent disponible : <strong><?php echo number_format($soldeArgent, 0, ',', ' '); ?> Ar</strong>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class='bx bx-cart-add me-1'></i>Confirmer l'achat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var achatModal = document.getElementById('achatModal');
    if (achatModal) {
        achatModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var besoinId = button.getAttribute('data-besoin-id');
            var designation = button.getAttribute('data-designation');
            var ville = button.getAttribute('data-ville');
            var prix = parseFloat(button.getAttribute('data-prix'));
            var max = parseInt(button.getAttribute('data-max'));
            var frais = parseFloat(button.getAttribute('data-frais'));

            document.getElementById('modal_besoin_id').value = besoinId;
            document.getElementById('modal_ville').textContent = ville;
            document.getElementById('modal_designation').textContent = designation;
            document.getElementById('modal_prix_display').textContent = prix.toLocaleString('fr-FR') + ' Ar';
            document.getElementById('modal_max').textContent = max.toLocaleString('fr-FR');
            document.getElementById('modal_frais_label').textContent = frais;
            document.getElementById('modal_frais_pourcent').value = frais;

            var qteInput = document.getElementById('modal_quantite');
            qteInput.max = max;
            qteInput.value = '';

            function updateCalcul() {
                var qte = parseInt(qteInput.value) || 0;
                var ht = prix * qte;
                var fraisMontant = ht * frais / 100;
                var ttc = ht + fraisMontant;

                document.getElementById('modal_montant_ht').textContent = ht.toLocaleString('fr-FR') + ' Ar';
                document.getElementById('modal_frais_montant').textContent = fraisMontant.toLocaleString('fr-FR') + ' Ar';
                document.getElementById('modal_montant_ttc').textContent = ttc.toLocaleString('fr-FR') + ' Ar';
            }

            qteInput.addEventListener('input', updateCalcul);
            updateCalcul();
        });
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
