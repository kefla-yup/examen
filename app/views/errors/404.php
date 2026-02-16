<?php include __DIR__ . '/../layout/header.php'; ?>

<section class="container py-5">
    <div class="text-center py-5">
        <div class="mb-4">
            <i class='bx bx-error-circle' style="font-size: 5rem; color: var(--gray-400);"></i>
        </div>
        <h1 class="display-4 fw-bold text-dark mb-3">404</h1>
        <h3 class="text-muted mb-4">Page non trouvée</h3>
        <p class="text-muted mb-5">La page que vous cherchez n'existe pas ou a été déplacée.</p>
        <a href="<?php echo base_url('/'); ?>" class="btn btn-primary btn-lg">
            <i class='bx bx-home me-2'></i>Retour au tableau de bord
        </a>
    </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>
