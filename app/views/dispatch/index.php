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
        <div class="d-flex gap-2">
            <form method="POST" action="<?php echo base_url('dispatches/simuler'); ?>" class="d-inline">
                <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Lancer la simulation du dispatch ? Les distributions existantes seront remplacées.');">
                    <i class='bx bx-play-circle me-1'></i>Simuler le Dispatch
                </button>
            </form>
            <form method="POST" action="<?php echo base_url('dispatches/reset'); ?>" class="d-inline">
                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Réinitialiser toutes les distributions ?');">
                    <i class='bx bx-reset me-1'></i>Réinitialiser
                </button>
            </form>
        </div>
    </div>

    <!-- Info Card -->
    <div class="info-card mb-4">
        <div class="info-card-icon">
            <i class='bx bx-info-circle'></i>
        </div>
        <div>
            <h6 class="mb-1">Comment fonctionne le dispatch ?</h6>
            <p class="mb-0 small text-muted">
                La simulation distribue les dons aux villes sinistrées <strong>par ordre de date et de saisie</strong>. 
                Pour chaque don, le système cherche les besoins correspondants (même désignation et type) 
                et distribue en priorité aux villes ayant le plus grand besoin non satisfait.
            </p>
        </div>
    </div>

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
                        <th>Quantité</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($dispatches)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class='bx bxs-truck fs-1 d-block mb-3' style="opacity: 0.3;"></i>
                            <h5>Aucune distribution effectuée</h5>
                            <p>Cliquez sur « Simuler le Dispatch » pour distribuer les dons aux villes sinistrées.</p>
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
                            <td><span class="fw-bold text-success fs-6"><?php echo number_format($dispatch['quantite_attribuee'], 0, ',', ' '); ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>
