<?php include __DIR__ . '/../layout/header.php'; ?>

<section class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url('/'); ?>"><i class='bx bxs-dashboard'></i> Tableau de bord</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('villes'); ?>">Villes</a></li>
            <li class="breadcrumb-item active">Nouvelle ville</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-icon bg-primary-soft">
                        <i class='bx bxs-city'></i>
                    </div>
                    <div>
                        <h2 class="form-card-title">Nouvelle Ville</h2>
                        <p class="form-card-subtitle">Ajouter une ville sinistrée à la base de données</p>
                    </div>
                </div>
                <form method="POST" action="<?php echo base_url('villes/nouveau'); ?>" class="form-modern">
                    <div class="mb-4">
                        <label for="nom" class="form-label">Nom de la ville <span class="text-danger">*</span></label>
                        <div class="input-icon">
                            <i class='bx bxs-map-pin'></i>
                            <input type="text" class="form-control" id="nom" name="nom" required placeholder="Ex: Mananjary">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="region" class="form-label">Région <span class="text-danger">*</span></label>
                        <div class="input-icon">
                            <i class='bx bx-map'></i>
                            <input type="text" class="form-control" id="region" name="region" required placeholder="Ex: Vatovavy-Fitovinany">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="population" class="form-label">Population estimée</label>
                        <div class="input-icon">
                            <i class='bx bx-group'></i>
                            <input type="number" class="form-control" id="population" name="population" min="0" value="0" placeholder="0">
                        </div>
                    </div>
                    <div class="d-flex gap-3 justify-content-end">
                        <a href="<?php echo base_url('villes'); ?>" class="btn btn-light">Annuler</a>
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
