/**
 * BNGRC – Custom JavaScript
 * Navbar scroll, animations, back-to-top
 */
document.addEventListener('DOMContentLoaded', function () {

    /* ───────────────────────────────────────────
       1. Navbar scroll effect
    ─────────────────────────────────────────── */
    var navbar = document.querySelector('#main_nav');
    function handleNavbarScroll() {
        if (!navbar) return;
        if (window.scrollY > 60) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    }
    window.addEventListener('scroll', handleNavbarScroll, { passive: true });
    handleNavbarScroll();

    /* ───────────────────────────────────────────
       2. Back-to-top button
    ─────────────────────────────────────────── */
    var backToTop = document.getElementById('backToTop');
    if (backToTop) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 400) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        }, { passive: true });

        backToTop.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    /* ───────────────────────────────────────────
       3. Auto-dismiss alerts after 5 seconds
    ─────────────────────────────────────────── */
    var alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) bsAlert.close();
        }, 5000);
    });

    /* ───────────────────────────────────────────
       4. Confirm dialogs styling
    ─────────────────────────────────────────── */
    // Forms with data-confirm attribute
    document.querySelectorAll('form[data-confirm]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            if (!confirm(form.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });

    /* ───────────────────────────────────────────
       5. Number formatting in inputs
    ─────────────────────────────────────────── */
    // Auto-format number display
    document.querySelectorAll('.format-number').forEach(function(el) {
        var val = parseFloat(el.textContent);
        if (!isNaN(val)) {
            el.textContent = val.toLocaleString('fr-FR');
        }
    });
});
