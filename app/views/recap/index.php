<?php include __DIR__ . '/../layout/header.php'; ?>

<section class="container py-4">
    <div class="page-header">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('/'); ?>"><i class='bx bxs-dashboard'></i> Tableau de bord</a></li>
                    <li class="breadcrumb-item active">Récapitulation</li>
                </ol>
            </nav>
            <h1 class="page-title"><i class='bx bxs-bar-chart-alt-2 me-2'></i>Récapitulation</h1>
            <p class="page-subtitle">Vue d'ensemble des besoins totaux, satisfaits et restants en montant</p>
        </div>
        <div class="d-flex gap-2">
            <button id="btnActualiser" class="btn btn-primary btn-lg" onclick="actualiserRecap()">
                <i class='bx bx-refresh me-1' id="refreshIcon"></i>Actualiser
            </button>
            <form method="POST" action="<?php echo base_url('reinitialiser'); ?>" class="d-inline">
                <input type="hidden" name="redirect" value="recap">
                <button type="submit" class="btn btn-outline-danger btn-lg" onclick="return confirm('Réinitialiser toutes les données aux valeurs par défaut ? Les données ajoutées seront perdues.');">
                    <i class='bx bx-reset me-1'></i>Réinitialiser
                </button>
            </form>
        </div>
    </div>

    <!-- Cartes résumé -->
    <div class="row g-4 mb-5">
        <div class="col-lg-4 col-md-6">
            <div class="stat-card stat-card-danger">
                <div class="stat-card-icon">
                    <i class='bx bxs-notepad'></i>
                </div>
                <div class="stat-card-content">
                    <span class="stat-card-value" id="recap-total"><?php echo number_format($montantBesoinsTotal, 0, ',', ' '); ?></span>
                    <span class="stat-card-label">Besoins Totaux (Ar)</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="stat-card stat-card-success">
                <div class="stat-card-icon">
                    <i class='bx bxs-check-circle'></i>
                </div>
                <div class="stat-card-content">
                    <span class="stat-card-value" id="recap-satisfaits"><?php echo number_format($montantBesoinsSatisfaits, 0, ',', ' '); ?></span>
                    <span class="stat-card-label">Besoins Satisfaits (Ar)</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="stat-card stat-card-warning">
                <div class="stat-card-icon">
                    <i class='bx bxs-error-circle'></i>
                </div>
                <div class="stat-card-content">
                    <span class="stat-card-value" id="recap-restants"><?php echo number_format($montantBesoinsRestants, 0, ',', ' '); ?></span>
                    <span class="stat-card-label">Besoins Restants (Ar)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique Couverture Globale -->
    <div class="card mb-5">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0"><i class='bx bx-bar-chart-alt me-2'></i>Couverture Globale</h5>
                <span class="badge bg-primary fs-6" id="recap-pourcentage"><?php echo $pourcentageCouverture; ?>%</span>
            </div>
            <canvas id="couvertureChart" height="120"></canvas>
            <div class="row mt-3">
                <div class="col-md-6">
                    <small class="text-muted"><span class="d-inline-block rounded-circle me-1" style="width:10px;height:10px;background:#198754;"></span>Distribution : <strong id="recap-dispatch"><?php echo number_format($montantBesoinsSatisfaitsDispatch, 0, ',', ' '); ?> Ar</strong></small>
                </div>
                <div class="col-md-6">
                    <small class="text-muted"><span class="d-inline-block rounded-circle me-1" style="width:10px;height:10px;background:#0dcaf0;"></span>Achats : <strong id="recap-achats"><?php echo number_format($montantBesoinsSatisfaitsAchats, 0, ',', ' '); ?> Ar</strong></small>
                </div>
            </div>
        </div>
    </div>

    <!-- Détails par ville -->
    <div class="section-header">
        <h3 class="section-title"><i class='bx bxs-city me-2'></i>Détails par Ville</h3>
    </div>
    <div class="data-table-card">
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>Ville</th>
                        <th>Région</th>
                        <th>Besoins (Ar)</th>
                        <th>Distribution (Ar)</th>
                        <th>Achats (Ar)</th>
                        <th>Total Satisfait (Ar)</th>
                        <th>Restant (Ar)</th>
                        <th>Couverture</th>
                    </tr>
                </thead>
                <tbody id="recap-table-body">
                    <?php foreach($detailsParVille as $ville): ?>
                    <tr>
                        <td class="fw-medium">
                            <i class='bx bxs-map-pin text-primary me-1'></i><?php echo htmlspecialchars($ville['ville_nom']); ?>
                        </td>
                        <td class="text-muted"><?php echo htmlspecialchars($ville['region']); ?></td>
                        <td class="fw-bold"><?php echo number_format($ville['montant_besoins'], 0, ',', ' '); ?></td>
                        <td class="text-success"><?php echo number_format($ville['montant_distribue'], 0, ',', ' '); ?></td>
                        <td class="text-info"><?php echo number_format($ville['montant_achete'], 0, ',', ' '); ?></td>
                        <td class="fw-bold text-success"><?php echo number_format($ville['montant_satisfait'], 0, ',', ' '); ?></td>
                        <td class="fw-bold text-danger"><?php echo number_format($ville['montant_restant'], 0, ',', ' '); ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: <?php echo $ville['pourcentage']; ?>%;"></div>
                                </div>
                                <small class="fw-bold" style="min-width: 40px;"><?php echo $ville['pourcentage']; ?>%</small>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script src="<?php echo asset('js/chart.min.js'); ?>"></script>
<script>
function formatNumber(num) {
    return Math.round(num).toLocaleString('fr-FR');
}

// Données initiales pour le graphique
var chartData = {
    besoinsTotal: <?php echo $montantBesoinsTotal; ?>,
    satisfaitsDispatch: <?php echo $montantBesoinsSatisfaitsDispatch; ?>,
    satisfaitsAchats: <?php echo $montantBesoinsSatisfaitsAchats; ?>,
    restants: <?php echo $montantBesoinsRestants; ?>
};

// Création du graphique
var ctx = document.getElementById('couvertureChart').getContext('2d');
var couvertureChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Besoins Totaux', 'Distribution', 'Achats', 'Restants'],
        datasets: [{
            label: 'Montant (Ar)',
            data: [chartData.besoinsTotal, chartData.satisfaitsDispatch, chartData.satisfaitsAchats, chartData.restants],
            backgroundColor: [
                'rgba(108, 117, 125, 0.8)',
                'rgba(25, 135, 84, 0.8)',
                'rgba(13, 202, 240, 0.8)',
                'rgba(220, 53, 69, 0.8)'
            ],
            borderColor: [
                'rgb(108, 117, 125)',
                'rgb(25, 135, 84)',
                'rgb(13, 202, 240)',
                'rgb(220, 53, 69)'
            ],
            borderWidth: 2,
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return formatNumber(context.raw) + ' Ar';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return formatNumber(value) + ' Ar';
                    }
                },
                grid: { color: 'rgba(0,0,0,0.05)' }
            },
            x: {
                grid: { display: false }
            }
        }
    }
});

function actualiserRecap() {
    var btn = document.getElementById('btnActualiser');
    var icon = document.getElementById('refreshIcon');
    
    btn.disabled = true;
    icon.classList.add('bx-spin');

    $.ajax({
        url: '<?php echo base_url("recap/ajax"); ?>',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            // Mettre à jour les cartes
            document.getElementById('recap-total').textContent = formatNumber(data.montantBesoinsTotal);
            document.getElementById('recap-satisfaits').textContent = formatNumber(data.montantBesoinsSatisfaits);
            document.getElementById('recap-restants').textContent = formatNumber(data.montantBesoinsRestants);
            
            // Mettre à jour le graphique
            document.getElementById('recap-pourcentage').textContent = data.pourcentageCouverture + '%';
            couvertureChart.data.datasets[0].data = [
                data.montantBesoinsTotal,
                data.montantBesoinsSatisfaitsDispatch,
                data.montantBesoinsSatisfaitsAchats,
                data.montantBesoinsRestants
            ];
            couvertureChart.update();

            // Mettre à jour les détails dispatch/achats
            document.getElementById('recap-dispatch').textContent = formatNumber(data.montantBesoinsSatisfaitsDispatch) + ' Ar';
            document.getElementById('recap-achats').textContent = formatNumber(data.montantBesoinsSatisfaitsAchats) + ' Ar';

            // Mettre à jour le tableau
            var tbody = document.getElementById('recap-table-body');
            var html = '';
            data.detailsParVille.forEach(function(ville) {
                html += '<tr>';
                html += '<td class="fw-medium"><i class="bx bxs-map-pin text-primary me-1"></i>' + ville.ville_nom + '</td>';
                html += '<td class="text-muted">' + ville.region + '</td>';
                html += '<td class="fw-bold">' + formatNumber(ville.montant_besoins) + '</td>';
                html += '<td class="text-success">' + formatNumber(ville.montant_distribue) + '</td>';
                html += '<td class="text-info">' + formatNumber(ville.montant_achete) + '</td>';
                html += '<td class="fw-bold text-success">' + formatNumber(ville.montant_satisfait) + '</td>';
                html += '<td class="fw-bold text-danger">' + formatNumber(ville.montant_restant) + '</td>';
                html += '<td><div class="d-flex align-items-center gap-2">';
                html += '<div class="progress flex-grow-1" style="height: 8px;">';
                html += '<div class="progress-bar bg-success" style="width: ' + ville.pourcentage + '%;"></div>';
                html += '</div>';
                html += '<small class="fw-bold" style="min-width: 40px;">' + ville.pourcentage + '%</small>';
                html += '</div></td>';
                html += '</tr>';
            });
            tbody.innerHTML = html;

            // Notification
            var alertHtml = '<div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">';
            alertHtml += '<i class="bx bx-check-circle me-2 fs-5"></i>';
            alertHtml += '<div>Données actualisées avec succès !</div>';
            alertHtml += '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            alertHtml += '</div>';
            var container = document.querySelector('.container.mt-3');
            if (container) {
                container.innerHTML = alertHtml;
                setTimeout(function() {
                    var alert = container.querySelector('.alert');
                    if (alert) {
                        var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                        if (bsAlert) bsAlert.close();
                    }
                }, 3000);
            }
        },
        error: function() {
            alert('Erreur lors de l\'actualisation des données.');
        },
        complete: function() {
            btn.disabled = false;
            icon.classList.remove('bx-spin');
        }
    });
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
