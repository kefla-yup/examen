<?php include __DIR__ . '/../layout/header.php'; ?>

<section class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url('/'); ?>"><i class='bx bxs-dashboard'></i> Tableau de bord</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('dons'); ?>">Dons</a></li>
            <li class="breadcrumb-item active">Nouveau don</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-icon bg-success-soft">
                        <i class='bx bx-gift'></i>
                    </div>
                    <div>
                        <h2 class="form-card-title">Enregistrer un Don</h2>
                        <p class="form-card-subtitle">Saisir un nouveau don reçu</p>
                    </div>
                </div>
                <form method="POST" action="<?php echo base_url('dons/nouveau'); ?>" class="form-modern">
                    <div class="mb-4">
                        <label for="donateur" class="form-label">Donateur</label>
                        <div class="input-icon">
                            <i class='bx bx-user'></i>
                            <input type="text" class="form-control" id="donateur" name="donateur" placeholder="Anonyme (si non renseigné)">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="type_besoin_id" class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="type_besoin_id" name="type_besoin_id" required>
                            <option value="">-- Sélectionner un type --</option>
                            <?php foreach($types as $type): ?>
                                <option value="<?php echo $type['id']; ?>"><?php echo htmlspecialchars($type['nom']); ?> — <?php echo htmlspecialchars($type['description']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="designation" class="form-label">Désignation <span class="text-danger">*</span></label>
                        <select class="form-select" id="designation" name="designation" required disabled>
                            <option value="">-- Sélectionnez d'abord un type --</option>
                        </select>
                        <small class="text-muted">La liste se met à jour automatiquement selon le type choisi</small>
                    </div>

                    <script>
                    var designationsParType = <?php echo json_encode($designationsParType ?? []); ?>;
                    document.getElementById('type_besoin_id').addEventListener('change', function() {
                        var typeId = this.value;
                        var select = document.getElementById('designation');
                        select.innerHTML = '';
                        if (!typeId) {
                            select.innerHTML = '<option value="">-- Sélectionnez d\'abord un type --</option>';
                            select.disabled = true;
                            return;
                        }
                        select.disabled = false;
                        var options = designationsParType[typeId] || [];
                        select.innerHTML = '<option value="">-- Choisir une désignation --</option>';
                        options.forEach(function(d) {
                            var opt = document.createElement('option');
                            opt.value = d;
                            opt.textContent = d;
                            select.appendChild(opt);
                        });
                    });
                    </script>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="quantite" class="form-label">Quantité <span class="text-danger">*</span></label>
                            <div class="input-icon">
                                <i class='bx bx-hash'></i>
                                <input type="number" class="form-control" id="quantite" name="quantite" required min="1" value="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="date_don" class="form-label">Date du don <span class="text-danger">*</span></label>
                            <div class="input-icon">
                                <i class='bx bx-calendar'></i>
                                <input type="date" class="form-control" id="date_don" name="date_don" required value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-3 justify-content-end">
                        <a href="<?php echo base_url('dons'); ?>" class="btn btn-light">Annuler</a>
                        <button type="submit" class="btn btn-success">
                            <i class='bx bx-check me-1'></i>Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>
