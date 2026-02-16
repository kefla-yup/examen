<?php include __DIR__ . '/../layout/header.php'; ?>

<!-- Hero Section -->
<section class="dashboard-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="hero-badge">
                    <i class='bx bx-shield-quarter'></i> Plateforme Officielle BNGRC
                </div>
                <h1 class="hero-title">Suivi des Collectes <br><span class="text-gradient">& Distributions</span></h1>
                <p class="hero-subtitle">Coordination et gestion des dons pour les sinistrés de Madagascar. Visualisez en temps réel les besoins, les dons reçus et la distribution par ville.</p>
                <div class="hero-actions">
                    <a href="<?php echo base_url('dons/nouveau'); ?>" class="btn btn-hero-primary">
                        <i class='bx bx-donate-heart'></i>Enregistrer un Don
                    </a>
                    <a href="<?php echo base_url('dispatches'); ?>" class="btn btn-hero-outline">
                        <i class='bx bxs-truck'></i>Voir Distribution
                    </a>
                </div>
            </div>
            <div class="col-lg-4 d-none d-lg-flex justify-content-center">
                <div class="hero-visual">
                    <div class="hero-visual-icon">
                        <i class='bx bx-donate-heart'></i>
                    </div>
                    <div class="hero-visual-ring ring-1"></div>
                    <div class="hero-visual-ring ring-2"></div>
                    <div class="hero-visual-ring ring-3"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Cards -->
<section class="container mb-5">
    <div class="row g-4" style="margin-top: -60px; position: relative; z-index: 10;">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card stat-card-primary">
                <div class="stat-card-icon">
                    <i class='bx bxs-city'></i>
                </div>
                <div class="stat-card-content">
                    <span class="stat-card-value"><?php echo $totalVilles; ?></span>
                    <span class="stat-card-label">Villes Sinistrées</span>
                </div>
                <div class="stat-card-trend">
                    <a href="<?php echo base_url('villes'); ?>" class="stat-link"><i class='bx bx-right-arrow-alt'></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card stat-card-danger">
                <div class="stat-card-icon">
                    <i class='bx bxs-notepad'></i>
                </div>
                <div class="stat-card-content">
                    <span class="stat-card-value"><?php echo $totalBesoins; ?></span>
                    <span class="stat-card-label">Besoins Identifiés</span>
                </div>
                <div class="stat-card-trend">
                    <a href="<?php echo base_url('besoins'); ?>" class="stat-link"><i class='bx bx-right-arrow-alt'></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card stat-card-success">
                <div class="stat-card-icon">
                    <i class='bx bxs-gift'></i>
                </div>
                <div class="stat-card-content">
                    <span class="stat-card-value"><?php echo $totalDons; ?></span>
                    <span class="stat-card-label">Dons Reçus</span>
                </div>
                <div class="stat-card-trend">
                    <a href="<?php echo base_url('dons'); ?>" class="stat-link"><i class='bx bx-right-arrow-alt'></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card stat-card-warning">
                <div class="stat-card-icon">
                    <i class='bx bxs-truck'></i>
                </div>
                <div class="stat-card-content">
                    <span class="stat-card-value"><?php echo $totalDispatches; ?></span>
                    <span class="stat-card-label">Distributions</span>
                </div>
                <div class="stat-card-trend">
                    <a href="<?php echo base_url('dispatches'); ?>" class="stat-link"><i class='bx bx-right-arrow-alt'></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Villes Overview -->
<section class="container mb-5">
    <div class="section-header">
        <div>
            <h2 class="section-title"><i class='bx bxs-city me-2'></i>Villes & Besoins</h2>
            <p class="section-subtitle">Vue d'ensemble des villes sinistrées avec leurs besoins et dons attribués</p>
        </div>
        <a href="<?php echo base_url('villes/nouveau'); ?>" class="btn btn-primary-soft">
            <i class='bx bx-plus me-1'></i>Ajouter une ville
        </a>
    </div>

    <div class="row g-4">
        <?php if(empty($villes)): ?>
            <div class="col-12">
                <div class="empty-state">
                    <i class='bx bxs-city'></i>
                    <h4>Aucune ville enregistrée</h4>
                    <p>Commencez par ajouter les villes sinistrées pour suivre les besoins.</p>
                    <a href="<?php echo base_url('villes/nouveau'); ?>" class="btn btn-primary"><i class='bx bx-plus me-1'></i>Ajouter une ville</a>
                </div>
            </div>
        <?php else: ?>
            <?php foreach($villes as $ville): ?>
            <div class="col-lg-4 col-md-6">
                <div class="ville-card">
                    <div class="ville-card-header">
                        <div class="ville-card-icon">
                            <i class='bx bxs-map-pin'></i>
                        </div>
                        <div>
                            <h5 class="ville-card-name"><?php echo htmlspecialchars($ville['nom']); ?></h5>
                            <span class="ville-card-region"><i class='bx bx-map me-1'></i><?php echo htmlspecialchars($ville['region']); ?></span>
                        </div>
                    </div>
                    <div class="ville-card-body">
                        <div class="ville-card-stats">
                            <div class="ville-stat">
                                <span class="ville-stat-value"><?php echo number_format($ville['total_besoins'], 0, ',', ' '); ?></span>
                                <span class="ville-stat-label">Besoins</span>
                            </div>
                            <div class="ville-stat">
                                <span class="ville-stat-value"><?php echo number_format($ville['valeur_besoins'], 0, ',', ' '); ?></span>
                                <span class="ville-stat-label">Valeur (Ar)</span>
                            </div>
                            <div class="ville-stat">
                                <span class="ville-stat-value"><?php echo number_format($ville['total_dispatched'], 0, ',', ' '); ?></span>
                                <span class="ville-stat-label">Distribués</span>
                            </div>
                        </div>
                        <?php 
                        $pct = $ville['total_besoins'] > 0 ? min(100, round(($ville['total_dispatched'] / max(1, $ville['valeur_besoins'])) * 100)) : 0;
                        ?>
                        <div class="ville-progress">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Couverture</small>
                                <small class="fw-bold"><?php echo $pct; ?>%</small>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-gradient-success" style="width: <?php echo $pct; ?>%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="ville-card-footer">
                        <a href="<?php echo base_url('villes/' . $ville['id']); ?>" class="btn btn-sm btn-outline-primary">
                            <i class='bx bx-show me-1'></i>Détails
                        </a>
                        <a href="<?php echo base_url('besoins/nouveau?ville_id=' . $ville['id']); ?>" class="btn btn-sm btn-outline-success">
                            <i class='bx bx-plus me-1'></i>Besoin
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Recent Dons -->
<section class="container mb-5">
    <div class="section-header">
        <div>
            <h2 class="section-title"><i class='bx bxs-gift me-2'></i>Derniers Dons</h2>
            <p class="section-subtitle">Les dons les plus récemment enregistrés</p>
        </div>
        <a href="<?php echo base_url('dons'); ?>" class="btn btn-primary-soft">
            <i class='bx bx-list-ul me-1'></i>Voir tout
        </a>
    </div>

    <div class="data-table-card">
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Donateur</th>
                        <th>Désignation</th>
                        <th>Type</th>
                        <th>Quantité</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($recentDons)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class='bx bxs-inbox fs-3 d-block mb-2'></i>
                            Aucun don enregistré
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($recentDons as $don): ?>
                        <tr>
                            <td>
                                <span class="date-badge">
                                    <?php echo date('d/m/Y', strtotime($don['date_don'])); ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-sm bg-success-soft">
                                        <i class='bx bx-user'></i>
                                    </div>
                                    <span class="fw-medium"><?php echo htmlspecialchars($don['donateur']); ?></span>
                                </div>
                            </td>
                            <td class="fw-medium"><?php echo htmlspecialchars($don['designation']); ?></td>
                            <td>
                                <span class="type-badge type-<?php echo strtolower($don['type_nom']); ?>">
                                    <?php echo htmlspecialchars($don['type_nom']); ?>
                                </span>
                            </td>
                            <td><span class="fw-bold text-dark"><?php echo number_format($don['quantite'], 0, ',', ' '); ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Quick Actions -->
<section class="container mb-5">
    <div class="row g-4">
        <div class="col-md-4">
            <a href="<?php echo base_url('besoins/nouveau'); ?>" class="quick-action-card">
                <div class="quick-action-icon bg-danger-soft">
                    <i class='bx bx-list-plus'></i>
                </div>
                <h5>Saisir un Besoin</h5>
                <p>Enregistrer les besoins des sinistrés par ville</p>
                <span class="quick-action-arrow"><i class='bx bx-right-arrow-alt'></i></span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="<?php echo base_url('dons/nouveau'); ?>" class="quick-action-card">
                <div class="quick-action-icon bg-success-soft">
                    <i class='bx bx-gift'></i>
                </div>
                <h5>Enregistrer un Don</h5>
                <p>Saisir les dons reçus des donateurs</p>
                <span class="quick-action-arrow"><i class='bx bx-right-arrow-alt'></i></span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="<?php echo base_url('dispatches'); ?>" class="quick-action-card">
                <div class="quick-action-icon bg-warning-soft">
                    <i class='bx bx-run'></i>
                </div>
                <h5>Simuler Distribution</h5>
                <p>Dispatcher les dons par ordre de date et saisie</p>
                <span class="quick-action-arrow"><i class='bx bx-right-arrow-alt'></i></span>
            </a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>
