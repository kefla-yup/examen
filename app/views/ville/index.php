<?php include __DIR__ . '/../layout/header.php'; ?>

<section class="container py-4">
    <div class="page-header">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('/'); ?>"><i class='bx bxs-dashboard'></i> Tableau de bord</a></li>
                    <li class="breadcrumb-item active">Villes</li>
                </ol>
            </nav>
            <h1 class="page-title"><i class='bx bxs-city me-2'></i>Villes Sinistrées</h1>
            <p class="page-subtitle">Gestion des villes touchées par les catastrophes</p>
        </div>
        <a href="<?php echo base_url('villes/nouveau'); ?>" class="btn btn-primary">
            <i class='bx bx-plus me-1'></i>Nouvelle Ville
        </a>
    </div>

    <div class="row g-4">
        <?php if(empty($villes)): ?>
            <div class="col-12">
                <div class="empty-state">
                    <i class='bx bxs-city'></i>
                    <h4>Aucune ville enregistrée</h4>
                    <p>Commencez par ajouter une ville sinistrée pour suivre les besoins.</p>
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
                                <span class="ville-stat-value"><?php echo number_format($ville['population'] ?? 0, 0, ',', ' '); ?></span>
                                <span class="ville-stat-label">Population</span>
                            </div>
                            <div class="ville-stat">
                                <span class="ville-stat-value"><?php echo number_format($ville['total_dispatched'], 0, ',', ' '); ?></span>
                                <span class="ville-stat-label">Distribués</span>
                            </div>
                        </div>
                    </div>
                    <div class="ville-card-footer">
                        <a href="<?php echo base_url('villes/' . $ville['id']); ?>" class="btn btn-sm btn-outline-primary">
                            <i class='bx bx-show me-1'></i>Détails
                        </a>
                        <a href="<?php echo base_url('villes/' . $ville['id'] . '/editer'); ?>" class="btn btn-sm btn-outline-warning">
                            <i class='bx bx-edit me-1'></i>Modifier
                        </a>
                        <form method="POST" action="<?php echo base_url('villes/' . $ville['id'] . '/supprimer'); ?>" class="d-inline" onsubmit="return confirm('Supprimer cette ville et tous ses besoins ?');">
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class='bx bx-trash'></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>
