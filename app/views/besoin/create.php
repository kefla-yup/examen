<?php include __DIR__ . '/../layout/header.php'; ?>

<section class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url('/'); ?>"><i class='bx bxs-dashboard'></i> Tableau de bord</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('besoins'); ?>">Besoins</a></li>
            <li class="breadcrumb-item active">Nouveau besoin</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-icon bg-danger-soft">
                        <i class='bx bx-list-plus'></i>
                    </div>
                    <div>
                        <h2 class="form-card-title">Nouveau Besoin</h2>
                        <p class="form-card-subtitle">Enregistrer un besoin pour une ville sinistrée</p>
                    </div>
                </div>
                <form method="POST" action="<?php echo base_url('besoins/nouveau'); ?>" class="form-modern">
                    <div class="mb-4">
                        <label for="ville_id" class="form-label">Ville <span class="text-danger">*</span></label>
                        <select class="form-select" id="ville_id" name="ville_id" required>
                            <option value="">-- Sélectionner une ville --</option>
                            <?php foreach($villes as $v): ?>
                                <option value="<?php echo $v['id']; ?>" <?php echo ($preselected_ville == $v['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($v['nom']); ?> (<?php echo htmlspecialchars($v['region']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="type_besoin_id" class="form-label">Type de besoin <span class="text-danger">*</span></label>
                        <select class="form-select" id="type_besoin_id" name="type_besoin_id" required>
                            <option value="">-- Sélectionner un type --</option>
                            <?php foreach($types as $type): ?>
                                <option value="<?php echo $type['id']; ?>"><?php echo htmlspecialchars($type['nom']); ?> — <?php echo htmlspecialchars($type['description']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="designation" class="form-label">Désignation <span class="text-danger">*</span></label>
                        <div class="input-icon">
                            <i class='bx bx-package'></i>
                            <input type="text" class="form-control" id="designation" name="designation" required placeholder="Ex: Riz (kg), Tôle (feuille)...">
                        </div>
                        <small class="text-muted">Utilisez la même désignation que les dons pour permettre le dispatch automatique</small>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="prix_unitaire" class="form-label">Prix unitaire (Ar) <span class="text-danger">*</span></label>
                            <div class="input-icon">
                                <i class='bx bx-money'></i>
                                <input type="number" class="form-control" id="prix_unitaire" name="prix_unitaire" required min="0" step="0.01" placeholder="0.00">
                            </div>
                            <small class="text-muted">Le prix unitaire ne change jamais</small>
                        </div>
                        <div class="col-md-6">
                            <label for="quantite" class="form-label">Quantité <span class="text-danger">*</span></label>
                            <div class="input-icon">
                                <i class='bx bx-hash'></i>
                                <input type="number" class="form-control" id="quantite" name="quantite" required min="1" value="1" placeholder="1">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-3 justify-content-end">
                        <a href="<?php echo base_url('besoins'); ?>" class="btn btn-light">Annuler</a>
                        <button type="submit" class="btn btn-primary">
                            <i class='bx bx-check me-1'></i>Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>
