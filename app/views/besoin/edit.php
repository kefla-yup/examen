<?php include __DIR__ . '/../layout/header.php'; ?>

<section class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url('/'); ?>"><i class='bx bxs-dashboard'></i> Tableau de bord</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('besoins'); ?>">Besoins</a></li>
            <li class="breadcrumb-item active">Modifier</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-icon bg-warning-soft">
                        <i class='bx bx-edit'></i>
                    </div>
                    <div>
                        <h2 class="form-card-title">Modifier le Besoin</h2>
                        <p class="form-card-subtitle">Mise à jour de : <?php echo htmlspecialchars($besoin['designation']); ?></p>
                    </div>
                </div>
                <form method="POST" action="<?php echo base_url('besoins/' . $besoin['id'] . '/editer'); ?>" class="form-modern">
                    <div class="mb-4">
                        <label for="ville_id" class="form-label">Ville <span class="text-danger">*</span></label>
                        <select class="form-select" id="ville_id" name="ville_id" required>
                            <?php foreach($villes as $v): ?>
                                <option value="<?php echo $v['id']; ?>" <?php echo $besoin['ville_id'] == $v['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($v['nom']); ?> (<?php echo htmlspecialchars($v['region']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="type_besoin_id" class="form-label">Type de besoin <span class="text-danger">*</span></label>
                        <select class="form-select" id="type_besoin_id" name="type_besoin_id" required>
                            <?php foreach($types as $type): ?>
                                <option value="<?php echo $type['id']; ?>" <?php echo $besoin['type_besoin_id'] == $type['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($type['nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="designation" class="form-label">Désignation <span class="text-danger">*</span></label>
                        <div class="input-icon">
                            <i class='bx bx-package'></i>
                            <input type="text" class="form-control" id="designation" name="designation" required value="<?php echo htmlspecialchars($besoin['designation']); ?>">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Prix unitaire</label>
                        <div class="input-icon">
                            <i class='bx bx-money'></i>
                            <input type="text" class="form-control" disabled value="<?php echo number_format($besoin['prix_unitaire'], 0, ',', ' '); ?> Ar">
                        </div>
                        <small class="text-muted"><i class='bx bx-lock-alt me-1'></i>Le prix unitaire ne change jamais</small>
                    </div>
                    <div class="mb-4">
                        <label for="quantite" class="form-label">Quantité <span class="text-danger">*</span></label>
                        <div class="input-icon">
                            <i class='bx bx-hash'></i>
                            <input type="number" class="form-control" id="quantite" name="quantite" required min="1" value="<?php echo $besoin['quantite']; ?>">
                        </div>
                    </div>
                    <div class="d-flex gap-3 justify-content-end">
                        <a href="<?php echo base_url('besoins'); ?>" class="btn btn-light">Annuler</a>
                        <button type="submit" class="btn btn-warning">
                            <i class='bx bx-check me-1'></i>Modifier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>
