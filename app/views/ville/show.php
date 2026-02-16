<?php include __DIR__ . '/../layout/header.php'; ?>

<section class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url('/'); ?>"><i class='bx bxs-dashboard'></i> Tableau de bord</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('villes'); ?>">Villes</a></li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($ville['nom']); ?></li>
        </ol>
    </nav>

    <div class="page-header">
        <div>
            <h1 class="page-title"><i class='bx bxs-map-pin me-2'></i><?php echo htmlspecialchars($ville['nom']); ?></h1>
            <p class="page-subtitle"><i class='bx bx-map me-1'></i><?php echo htmlspecialchars($ville['region']); ?> — Population: <?php echo number_format($ville['population'], 0, ',', ' '); ?></p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo base_url('besoins/nouveau?ville_id=' . $ville['id']); ?>" class="btn btn-success">
                <i class='bx bx-plus me-1'></i>Ajouter un Besoin
            </a>
            <a href="<?php echo base_url('villes/' . $ville['id'] . '/editer'); ?>" class="btn btn-warning">
                <i class='bx bx-edit me-1'></i>Modifier
            </a>
        </div>
    </div>

    <!-- Besoins de la ville -->
    <div class="section-header mt-4">
        <h3 class="section-title"><i class='bx bxs-notepad me-2'></i>Besoins Identifiés</h3>
    </div>

    <div class="data-table-card">
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>Désignation</th>
                        <th>Type</th>
                        <th>Prix Unitaire</th>
                        <th>Quantité Requise</th>
                        <th>Distribué</th>
                        <th>Restant</th>
                        <th>Couverture</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($besoins)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class='bx bxs-inbox fs-3 d-block mb-2'></i>
                            Aucun besoin enregistré pour cette ville
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($besoins as $besoin): ?>
                        <?php $pct = $besoin['quantite'] > 0 ? round(($besoin['quantite_distribuee'] / $besoin['quantite']) * 100) : 0; ?>
                        <tr>
                            <td class="fw-medium"><?php echo htmlspecialchars($besoin['designation']); ?></td>
                            <td>
                                <span class="type-badge type-<?php echo strtolower($besoin['type_nom']); ?>">
                                    <?php echo htmlspecialchars($besoin['type_nom']); ?>
                                </span>
                            </td>
                            <td><?php echo number_format($besoin['prix_unitaire'], 0, ',', ' '); ?> Ar</td>
                            <td class="fw-bold"><?php echo number_format($besoin['quantite'], 0, ',', ' '); ?></td>
                            <td class="text-success fw-bold"><?php echo number_format($besoin['quantite_distribuee'], 0, ',', ' '); ?></td>
                            <td class="text-danger fw-bold"><?php echo number_format($besoin['quantite_restante'], 0, ',', ' '); ?></td>
                            <td style="min-width: 140px;">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 8px;">
                                        <div class="progress-bar <?php echo $pct >= 100 ? 'bg-success' : ($pct >= 50 ? 'bg-warning' : 'bg-danger'); ?>" style="width: <?php echo min(100, $pct); ?>%"></div>
                                    </div>
                                    <small class="fw-bold"><?php echo $pct; ?>%</small>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Dispatches vers cette ville -->
    <div class="section-header mt-5">
        <h3 class="section-title"><i class='bx bxs-truck me-2'></i>Dons Attribués</h3>
    </div>

    <div class="data-table-card">
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Donateur</th>
                        <th>Désignation Don</th>
                        <th>Besoin Couvert</th>
                        <th>Quantité</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($dispatches)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class='bx bxs-truck fs-3 d-block mb-2'></i>
                            Aucune distribution pour cette ville.<br>
                            <a href="<?php echo base_url('dispatches'); ?>" class="text-primary">Lancer une simulation</a>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($dispatches as $dispatch): ?>
                        <tr>
                            <td>
                                <span class="date-badge">
                                    <?php echo date('d/m/Y H:i', strtotime($dispatch['date_dispatch'])); ?>
                                </span>
                            </td>
                            <td class="fw-medium"><?php echo htmlspecialchars($dispatch['donateur']); ?></td>
                            <td><?php echo htmlspecialchars($dispatch['don_designation']); ?></td>
                            <td><?php echo htmlspecialchars($dispatch['besoin_designation']); ?></td>
                            <td class="fw-bold text-success"><?php echo number_format($dispatch['quantite_attribuee'], 0, ',', ' '); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>
