<?php include __DIR__ . '/../layout/header.php'; ?>

<section class="container py-4">
    <div class="page-header">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('/'); ?>"><i class='bx bxs-dashboard'></i> Tableau de bord</a></li>
                    <li class="breadcrumb-item active">Dons</li>
                </ol>
            </nav>
            <h1 class="page-title"><i class='bx bxs-gift me-2'></i>Dons Reçus</h1>
            <p class="page-subtitle">Liste de tous les dons enregistrés</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo base_url('dons/nouveau'); ?>" class="btn btn-success">
                <i class='bx bx-plus me-1'></i>Nouveau Don
            </a>
            <form method="POST" action="<?php echo base_url('reinitialiser'); ?>" class="d-inline">
                <input type="hidden" name="redirect" value="dons">
                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Réinitialiser toutes les données aux valeurs par défaut ? Les données ajoutées seront perdues.');">
                    <i class='bx bx-reset me-1'></i>Réinitialiser
                </button>
            </form>
        </div>
    </div>

    <div class="data-table-card">
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Donateur</th>
                        <th>Désignation</th>
                        <th>Type</th>
                        <th>Quantité</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($dons)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class='bx bxs-inbox fs-3 d-block mb-2'></i>
                            Aucun don enregistré
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php $i = 1; foreach($dons as $don): ?>
                        <tr>
                            <td class="text-muted"><?php echo $i++; ?></td>
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
                            <td class="fw-bold"><?php echo number_format($don['quantite'], 0, ',', ' '); ?></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="<?php echo base_url('dons/' . $don['id'] . '/editer'); ?>" class="btn btn-sm btn-outline-warning" title="Modifier">
                                        <i class='bx bx-edit'></i>
                                    </a>
                                    <form method="POST" action="<?php echo base_url('dons/' . $don['id'] . '/supprimer'); ?>" class="d-inline" onsubmit="return confirm('Supprimer ce don ?');">
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
