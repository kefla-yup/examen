<?php include __DIR__ . '/../layout/header.php'; ?>

<section class="container py-4">
    <div class="page-header">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('/'); ?>"><i class='bx bxs-dashboard'></i> Tableau de bord</a></li>
                    <li class="breadcrumb-item active">Besoins</li>
                </ol>
            </nav>
            <h1 class="page-title"><i class='bx bxs-notepad me-2'></i>Besoins des Sinistrés</h1>
            <p class="page-subtitle">Liste de tous les besoins identifiés par ville</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo base_url('besoins/nouveau'); ?>" class="btn btn-primary">
                <i class='bx bx-plus me-1'></i>Nouveau Besoin
            </a>
            <form method="POST" action="<?php echo base_url('reinitialiser'); ?>" class="d-inline">
                <input type="hidden" name="redirect" value="besoins">
                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Réinitialiser toutes les données aux valeurs par défaut ? Les données ajoutées seront perdues.');">
                    <i class='bx bx-reset me-1'></i>Réinitialiser
                </button>
            </form>
        </div>
    </div>

    <!-- Filter by ville -->
    <div class="filter-bar mb-4">
        <form method="GET" action="<?php echo base_url('besoins'); ?>" class="d-flex align-items-center gap-3 flex-wrap">
            <label class="fw-semibold text-muted"><i class='bx bx-filter me-1'></i>Filtrer par ville :</label>
            <select name="ville_id" class="form-select form-select-sm" style="max-width: 250px;" onchange="this.form.submit()">
                <option value="">Toutes les villes</option>
                <?php foreach($villes as $v): ?>
                    <option value="<?php echo $v['id']; ?>" <?php echo $villeFilter == $v['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($v['nom']); ?> (<?php echo htmlspecialchars($v['region']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <div class="data-table-card">
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ville</th>
                        <th>Désignation</th>
                        <th>Type</th>
                        <th>Prix Unitaire</th>
                        <th>Quantité</th>
                        <th>Valeur Totale</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($besoins)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class='bx bxs-inbox fs-3 d-block mb-2'></i>
                            Aucun besoin enregistré
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php $i = 1; foreach($besoins as $besoin): ?>
                        <tr>
                            <td class="text-muted"><?php echo $i++; ?></td>
                            <td>
                                <a href="<?php echo base_url('villes/' . $besoin['ville_id']); ?>" class="fw-medium text-decoration-none">
                                    <i class='bx bxs-map-pin text-primary me-1'></i><?php echo htmlspecialchars($besoin['ville_nom']); ?>
                                </a>
                            </td>
                            <td class="fw-medium"><?php echo htmlspecialchars($besoin['designation']); ?></td>
                            <td>
                                <span class="type-badge type-<?php echo strtolower($besoin['type_nom']); ?>">
                                    <?php echo htmlspecialchars($besoin['type_nom']); ?>
                                </span>
                            </td>
                            <td><?php echo number_format($besoin['prix_unitaire'], 0, ',', ' '); ?> Ar</td>
                            <td class="fw-bold"><?php echo number_format($besoin['quantite'], 0, ',', ' '); ?></td>
                            <td class="fw-bold text-dark"><?php echo number_format($besoin['prix_unitaire'] * $besoin['quantite'], 0, ',', ' '); ?> Ar</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="<?php echo base_url('besoins/' . $besoin['id'] . '/editer'); ?>" class="btn btn-sm btn-outline-warning" title="Modifier">
                                        <i class='bx bx-edit'></i>
                                    </a>
                                    <form method="POST" action="<?php echo base_url('besoins/' . $besoin['id'] . '/supprimer'); ?>" class="d-inline" onsubmit="return confirm('Supprimer ce besoin ?');">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>
