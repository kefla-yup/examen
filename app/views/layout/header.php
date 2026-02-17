<!DOCTYPE html>
<html lang="fr">
<head>
    <title><?php echo $title ?? 'BNGRC - Gestion des Dons'; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="BNGRC - Application de suivi des collectes et distributions de dons pour les sinistrés">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo asset('img/favicon.ico'); ?>">
    <!-- Bootstrap CSS -->
    <link href="<?php echo asset('css/bootstrap.min.css'); ?>" rel="stylesheet">
    <!-- Boxicons -->
    <link href="<?php echo asset('css/boxicon.min.css'); ?>" rel="stylesheet">
    <!-- Google Fonts - Inter -->
    <link rel="stylesheet" href="<?php echo asset('css/fonts.css'); ?>">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/custom.css'); ?>">
</head>
<body>
    <!-- Navigation -->
    <nav id="main_nav" class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?php echo base_url('/'); ?>">
                <div class="brand-icon">
                    <i class='bx bx-donate-heart'></i>
                </div>
                <div class="brand-text">
                    <span class="brand-name">BNGRC</span>
                    <span class="brand-sub">Gestion des Dons</span>
                </div>
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <?php
                    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
                    $base = rtrim(BASE_URL, '/');
                    $path = $base ? str_replace($base, '', $uri) : $uri;
                ?>
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($path === '/' || $path === '/dashboard') ? 'active' : ''; ?>" href="<?php echo base_url('/'); ?>">
                            <i class='bx bxs-dashboard me-1'></i>Tableau de bord
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo strpos($path, '/villes') === 0 ? 'active' : ''; ?>" href="<?php echo base_url('villes'); ?>">
                            <i class='bx bxs-city me-1'></i>Villes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo strpos($path, '/besoins') === 0 ? 'active' : ''; ?>" href="<?php echo base_url('besoins'); ?>">
                            <i class='bx bxs-notepad me-1'></i>Besoins
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo strpos($path, '/dons') === 0 ? 'active' : ''; ?>" href="<?php echo base_url('dons'); ?>">
                            <i class='bx bxs-gift me-1'></i>Dons
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo strpos($path, '/dispatches') === 0 ? 'active' : ''; ?>" href="<?php echo base_url('dispatches'); ?>">
                            <i class='bx bxs-truck me-1'></i>Distribution
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo strpos($path, '/achats') === 0 ? 'active' : ''; ?>" href="<?php echo base_url('achats'); ?>">
                            <i class='bx bxs-cart me-1'></i>Achats
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo strpos($path, '/recap') === 0 ? 'active' : ''; ?>" href="<?php echo base_url('recap'); ?>">
                            <i class='bx bxs-bar-chart-alt-2 me-1'></i>Récap
                        </a>
                    </li>
                </ul>
                <div class="nav-actions d-flex align-items-center gap-2">
                    <a href="<?php echo base_url('dons/nouveau'); ?>" class="btn btn-nav-cta">
                        <i class='bx bx-plus me-1'></i>Nouveau Don
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <!-- End Navigation -->

    <!-- Messages Flash -->
    <div class="container mt-3" style="padding-top: 80px;">
        <?php echo Flight::displayFlash(); ?>
    </div>

    <main class="site-main" style="<?php echo empty($_SESSION['flash']) ? 'padding-top: 80px;' : ''; ?>">
