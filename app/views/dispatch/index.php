<?php include __DIR__ . '/../layout/header.php'; ?>

<section class="container py-4">
    <div class="page-header">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('/'); ?>"><i class='bx bxs-dashboard'></i> Tableau de bord</a></li>
                    <li class="breadcrumb-item active">Distribution</li>
                </ol>
            </nav>
            <h1 class="page-title"><i class='bx bxs-truck me-2'></i>Distribution des Dons</h1>
            <p class="page-subtitle">Simulation et suivi de la distribution des dons vers les villes sinistrées</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <form method="POST" action="<?php echo base_url('dispatches/reset'); ?>" class="d-inline">
                <button type="submit" class="btn btn-outline-secondary" onclick="return confirm('Réinitialiser toutes les distributions ?');">
                    <i class='bx bx-reset me-1'></i>Reset Dispatches
                </button>
            </form>
            <form method="POST" action="<?php echo base_url('reinitialiser'); ?>" class="d-inline">
                <input type="hidden" name="redirect" value="dispatches">
                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Réinitialiser TOUTES les données aux valeurs par défaut ?');">
                    <i class='bx bx-refresh me-1'></i>Réinitialiser Tout
                </button>
            </form>
        </div>
    </div>

    <!-- Dispatch Control Panel -->
    <div class="dispatch-panel mb-5">
        <div class="dispatch-panel-header">
            <div class="dispatch-panel-title">
                <i class='bx bx-cog'></i>
                <div>
                    <h5 class="mb-0">Centre de Contrôle</h5>
                    <small>Sélectionnez un algorithme et lancez la simulation</small>
                </div>
            </div>
        </div>
        <div class="dispatch-panel-body">
            <form method="POST" action="<?php echo base_url('dispatches/simuler'); ?>">
                <div class="dispatch-modes">
                    <label class="dispatch-mode-card <?php echo (!isset($simulation['mode']) || $simulation['mode'] === 'max') ? 'active' : ''; ?>">
                        <input type="radio" name="mode" value="max" <?php echo (!isset($simulation['mode']) || $simulation['mode'] === 'max') ? 'checked' : ''; ?> class="d-none">
                        <div class="dispatch-mode-icon" style="background: rgba(25,135,84,0.1); color: #198754;">
                            <i class='bx bx-up-arrow-alt'></i>
                        </div>
                        <div class="dispatch-mode-content">
                            <span class="dispatch-mode-label">Priorité Maximum</span>
                            <span class="dispatch-mode-desc">Les villes avec le plus grand besoin reçoivent en premier</span>
                        </div>
                        <div class="dispatch-mode-check"><i class='bx bx-check'></i></div>
                    </label>
                    <label class="dispatch-mode-card <?php echo (isset($simulation['mode']) && $simulation['mode'] === 'min') ? 'active' : ''; ?>">
                        <input type="radio" name="mode" value="min" <?php echo (isset($simulation['mode']) && $simulation['mode'] === 'min') ? 'checked' : ''; ?> class="d-none">
                        <div class="dispatch-mode-icon" style="background: rgba(13,110,253,0.1); color: #0d6efd;">
                            <i class='bx bx-down-arrow-alt'></i>
                        </div>
                        <div class="dispatch-mode-content">
                            <span class="dispatch-mode-label">Priorité Minimum</span>
                            <span class="dispatch-mode-desc">Les petits besoins sont satisfaits complètement en premier</span>
                        </div>
                        <div class="dispatch-mode-check"><i class='bx bx-check'></i></div>
                    </label>
                    <label class="dispatch-mode-card <?php echo (isset($simulation['mode']) && $simulation['mode'] === 'proportionnel') ? 'active' : ''; ?>">
                        <input type="radio" name="mode" value="proportionnel" <?php echo (isset($simulation['mode']) && $simulation['mode'] === 'proportionnel') ? 'checked' : ''; ?> class="d-none">
                        <div class="dispatch-mode-icon" style="background: rgba(111,66,193,0.1); color: #6f42c1;">
                            <i class='bx bx-equalizer'></i>
                        </div>
                        <div class="dispatch-mode-content">
                            <span class="dispatch-mode-label">Proportionnel</span>
                            <span class="dispatch-mode-desc">Distribution équitable selon le ratio besoin/total</span>
                        </div>
                        <div class="dispatch-mode-check"><i class='bx bx-check'></i></div>
                    </label>
                </div>
                <div class="dispatch-panel-actions">
                    <button type="submit" class="btn btn-warning btn-lg" onclick="return confirm('Lancer la simulation ? (Aperçu uniquement, rien ne sera enregistré)');">
                        <i class='bx bx-play me-1'></i>Lancer la Simulation
                    </button>
                    <form method="POST" action="<?php echo base_url('dispatches/valider'); ?>" class="d-inline">
                        <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Valider et exécuter la distribution ?');" <?php echo empty($simulation) ? 'disabled' : ''; ?>>
                            <i class='bx bx-check-circle me-1'></i>Valider le Dispatch
                        </button>
                    </form>
                </div>
            </form>
        </div>
    </div>

    <!-- Résultat de la simulation (aperçu) -->
    <?php if(!empty($simulation) && !empty($simulation['dispatches'])): ?>
    <div class="dispatch-simulation-result mb-5">
        <div class="dispatch-sim-header">
            <div class="d-flex align-items-center gap-3">
                <div class="dispatch-sim-icon">
                    <i class='bx bx-search-alt'></i>
                </div>
                <div>
                    <h5 class="mb-0 text-white">Aperçu de la Simulation</h5>
                    <small class="text-white-50"><?php echo htmlspecialchars($simulation['mode_label'] ?? 'Priorité besoins maximum'); ?></small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-4">
                <div class="dispatch-sim-stat">
                    <span class="dispatch-sim-stat-value"><?php echo number_format($simulation['total'], 0, ',', ' '); ?></span>
                    <span class="dispatch-sim-stat-label">unités</span>
                </div>
                <div class="dispatch-sim-stat">
                    <span class="dispatch-sim-stat-value"><?php echo count($simulation['dispatches']); ?></span>
                    <span class="dispatch-sim-stat-label">attributions</span>
                </div>
            </div>
        </div>
        <div class="dispatch-sim-body">
            <div class="table-responsive">
                <table class="table table-modern table-striped mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Donateur</th>
                            <th>Don</th>
                            <th>Ville</th>
                            <th>Besoin Couvert</th>
                            <th class="text-end">Quantité</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $si = 1; foreach($simulation['dispatches'] as $sim): ?>
                        <tr>
                            <td class="text-muted"><?php echo $si++; ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-sm bg-warning-soft">
                                        <i class='bx bx-user'></i>
                                    </div>
                                    <span class="fw-medium"><?php echo htmlspecialchars($sim['donateur']); ?></span>
                                </div>
                            </td>
                            <td class="fw-medium"><?php echo htmlspecialchars($sim['don_designation']); ?></td>
                            <td><i class='bx bxs-map-pin text-primary me-1'></i><?php echo htmlspecialchars($sim['ville_nom']); ?></td>
                            <td><?php echo htmlspecialchars($sim['besoin_designation']); ?></td>
                            <td class="text-end"><span class="badge bg-warning text-dark px-3 py-2 fs-6"><?php echo number_format($sim['quantite_attribuee'], 0, ',', ' '); ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="dispatch-sim-footer">
            <div class="d-flex align-items-center gap-2 text-muted">
                <i class='bx bx-info-circle'></i>
                <span>Ceci est un aperçu. Validez pour confirmer la distribution.</span>
            </div>
            <form method="POST" action="<?php echo base_url('dispatches/valider'); ?>" class="d-inline">
                <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Confirmer et exécuter cette distribution ?');">
                    <i class='bx bx-check-circle me-1'></i>Valider cette Distribution
                </button>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <!-- Stats par ville -->
    <?php if(!empty($stats)): ?>
    <div class="section-header mt-4">
        <h3 class="section-title"><i class='bx bx-bar-chart-alt-2 me-2'></i>Résumé par Ville</h3>
    </div>
    <div class="row g-4 mb-5">
        <?php foreach($stats as $stat): ?>
        <div class="col-lg-4 col-md-6">
            <div class="dispatch-stat-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($stat['ville_nom']); ?></h5>
                        <small class="text-muted"><?php echo htmlspecialchars($stat['region']); ?></small>
                    </div>
                    <span class="badge bg-primary-soft text-primary fs-6"><?php echo $stat['nb_dispatches']; ?></span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="dispatch-stat-icon bg-success-soft">
                        <i class='bx bxs-truck'></i>
                    </div>
                    <div>
                        <span class="fs-4 fw-bold text-success"><?php echo number_format($stat['total_distribue'], 0, ',', ' '); ?></span>
                        <small class="d-block text-muted">unités distribuées</small>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Table des dispatches -->
    <div class="section-header">
        <h3 class="section-title"><i class='bx bx-list-ul me-2'></i>Détail des Distributions</h3>
    </div>
    <div class="data-table-card">
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Donateur</th>
                        <th>Don</th>
                        <th>Ville</th>
                        <th>Besoin Couvert</th>
                        <th class="text-end">Quantité</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($dispatches)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class='bx bxs-truck fs-1 d-block mb-3' style="opacity: 0.3;"></i>
                            <h5>Aucune distribution effectuée</h5>
                            <p>Lancez une simulation puis validez-la pour distribuer les dons.</p>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php $i = 1; foreach($dispatches as $dispatch): ?>
                        <tr>
                            <td class="text-muted"><?php echo $i++; ?></td>
                            <td>
                                <span class="date-badge">
                                    <?php echo date('d/m/Y H:i', strtotime($dispatch['date_dispatch'])); ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-sm bg-success-soft">
                                        <i class='bx bx-user'></i>
                                    </div>
                                    <span class="fw-medium"><?php echo htmlspecialchars($dispatch['donateur']); ?></span>
                                </div>
                            </td>
                            <td class="fw-medium"><?php echo htmlspecialchars($dispatch['don_designation']); ?></td>
                            <td>
                                <a href="<?php echo base_url('villes/' . $dispatch['ville_id']); ?>" class="text-decoration-none">
                                    <i class='bx bxs-map-pin text-primary me-1'></i><?php echo htmlspecialchars($dispatch['ville_nom']); ?>
                                </a>
                            </td>
                            <td><?php echo htmlspecialchars($dispatch['besoin_designation']); ?></td>
                            <td class="text-end"><span class="badge bg-success px-3 py-2 fs-6"><?php echo number_format($dispatch['quantite_attribuee'], 0, ',', ' '); ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
document.querySelectorAll('.dispatch-mode-card').forEach(function(card) {
    card.addEventListener('click', function() {
        document.querySelectorAll('.dispatch-mode-card').forEach(function(c) { c.classList.remove('active'); });
        this.classList.add('active');
        this.querySelector('input[type="radio"]').checked = true;
    });
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
