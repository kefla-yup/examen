<?php include __DIR__ . '/../layout/header.php'; ?>

<section class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url('/'); ?>"><i class='bx bxs-dashboard'></i> Tableau de bord</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('dons'); ?>">Dons</a></li>
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
                        <h2 class="form-card-title">Modifier le Don</h2>
                        <p class="form-card-subtitle">Mise à jour du don de <?php echo htmlspecialchars($don['donateur']); ?></p>
                    </div>
                </div>
                <form method="POST" action="<?php echo base_url('dons/' . $don['id'] . '/editer'); ?>" class="form-modern">
                    <div class="mb-4">
                        <label for="donateur" class="form-label">Donateur</label>
                        <div class="input-icon">
                            <i class='bx bx-user'></i>
                            <input type="text" class="form-control" id="donateur" name="donateur" value="<?php echo htmlspecialchars($don['donateur']); ?>">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="type_besoin_id" class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="type_besoin_id" name="type_besoin_id" required>
                            <?php foreach($types as $type): ?>
                                <option value="<?php echo $type['id']; ?>" <?php echo $don['type_besoin_id'] == $type['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($type['nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="designation" class="form-label">Désignation <span class="text-danger">*</span></label>
                        <div class="input-icon">
                            <i class='bx bx-package'></i>
                            <input type="text" class="form-control" id="designation" name="designation" required value="<?php echo htmlspecialchars($don['designation']); ?>">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="quantite" class="form-label">Quantité <span class="text-danger">*</span></label>
                            <div class="input-icon">
                                <i class='bx bx-hash'></i>
                                <input type="number" class="form-control" id="quantite" name="quantite" required min="1" value="<?php echo $don['quantite']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="date_don" class="form-label">Date du don <span class="text-danger">*</span></label>
                            <div class="input-icon">
                                <i class='bx bx-calendar'></i>
                                <input type="date" class="form-control" id="date_don" name="date_don" required value="<?php echo $don['date_don']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-3 justify-content-end">
                        <a href="<?php echo base_url('dons'); ?>" class="btn btn-light">Annuler</a>
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
