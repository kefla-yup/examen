    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-wave">
            <svg viewBox="0 0 1440 120" preserveAspectRatio="none" fill="none">
                <path d="M0,80 C360,120 720,20 1080,80 C1260,110 1380,60 1440,80 L1440,120 L0,120Z" fill="currentColor"/>
            </svg>
        </div>

        <div class="container">
            <div class="row py-5 g-4">
                <!-- Brand Column -->
                <div class="col-lg-4 col-12">
                    <a class="footer-brand d-inline-flex align-items-center gap-2 mb-3" href="<?php echo base_url('/'); ?>">
                        <div class="footer-brand-icon">
                            <i class='bx bx-donate-heart'></i>
                        </div>
                        <div>
                            <span class="h4 text-white fw-bold mb-0 d-block">BNGRC</span>
                            <small style="color: rgba(255,255,255,0.5); font-size: 0.7rem; letter-spacing: 1px; text-transform: uppercase;">Bureau National de Gestion des Risques et Catastrophes</small>
                        </div>
                    </a>
                    <p class="footer-desc">
                        Application de suivi des collectes et des distributions de dons pour les sinistrés.
                        Coordination efficace de l'aide humanitaire à Madagascar.
                    </p>
                </div>

                <!-- Navigation Column -->
                <div class="col-lg-4 col-md-6">
                    <h5 class="footer-heading">Navigation</h5>
                    <ul class="footer-links">
                        <li><a href="<?php echo base_url('/'); ?>"><i class='bx bxs-dashboard'></i>Tableau de bord</a></li>
                        <li><a href="<?php echo base_url('villes'); ?>"><i class='bx bxs-city'></i>Villes sinistrées</a></li>
                        <li><a href="<?php echo base_url('besoins'); ?>"><i class='bx bxs-notepad'></i>Besoins</a></li>
                        <li><a href="<?php echo base_url('dons'); ?>"><i class='bx bxs-gift'></i>Dons reçus</a></li>
                        <li><a href="<?php echo base_url('dispatches'); ?>"><i class='bx bxs-truck'></i>Distribution</a></li>
                    </ul>
                </div>

                <!-- Contact Column -->
                <div class="col-lg-4 col-md-6">
                    <h5 class="footer-heading">Contact</h5>
                    <ul class="footer-contact">
                        <li>
                            <div class="footer-contact-icon"><i class='bx bx-envelope'></i></div>
                            <div>
                                <small>Email</small>
                                <span>contact@bngrc.mg</span>
                            </div>
                        </li>
                        <li>
                            <div class="footer-contact-icon"><i class='bx bx-phone'></i></div>
                            <div>
                                <small>Téléphone</small>
                                <span>+261 20 22 211 13</span>
                            </div>
                        </li>
                        <li>
                            <div class="footer-contact-icon"><i class='bx bx-map'></i></div>
                            <div>
                                <small>Adresse</small>
                                <span>Antananarivo, Madagascar</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Credits Section -->
        <div class="footer-credits-section">
            <div class="container">
                <div class="text-center mb-3">
                    <span class="footer-credits-label">Réalisé par</span>
                </div>
                <div class="d-flex justify-content-center flex-wrap gap-3 mb-4">
                    <div class="footer-credit-card">
                        <span class="footer-credit-etu">ETU003877</span>
                        <span class="footer-credit-name">Itiela</span>
                    </div>
                    <div class="footer-credit-card">
                        <span class="footer-credit-etu">ETU004179</span>
                        <span class="footer-credit-name">Houssena</span>
                    </div>
                    <div class="footer-credit-card">
                        <span class="footer-credit-etu">ETU003888</span>
                        <span class="footer-credit-name">Armella</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="footer-bottom">
            <div class="container text-center py-3">
                <p class="mb-0">© <?php echo date('Y'); ?> BNGRC — Bureau National de Gestion des Risques et Catastrophes. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="backToTop" title="Retour en haut">
        <i class='bx bx-chevron-up'></i>
    </button>

    <!-- Bootstrap JS -->
    <script src="<?php echo asset('js/bootstrap.bundle.min.js'); ?>"></script>
    <!-- jQuery -->
    <script src="<?php echo asset('js/jquery.min.js'); ?>"></script>
    <!-- Custom JS -->
    <script src="<?php echo asset('js/custom.js'); ?>"></script>
    
    <?php if(isset($scripts)): ?>
        <?php echo $scripts; ?>
    <?php endif; ?>
</body>
</html>
