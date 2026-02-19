document.addEventListener('DOMContentLoaded', function() {
    const burgerMenu = document.getElementById('burgerMenu');
    const navMenu = document.getElementById('navMenu');
    const hasSubmenuItems = document.querySelectorAll('.has-submenu');
    const header = document.querySelector('header');

    // Détection du scroll pour changer le background du header
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // Toggle burger menu
    if (burgerMenu) {
        burgerMenu.addEventListener('click', function() {
            burgerMenu.classList.toggle('active');
            navMenu.classList.toggle('active');
            
            // Empêcher le scroll du body quand le menu est ouvert
            if (navMenu.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });
    }

    // Toggle submenu en mobile
    hasSubmenuItems.forEach(item => {
        const link = item.querySelector('.nav-link');
        
        if (window.innerWidth <= 768) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                item.classList.toggle('active');
            });
        }
    });

    // Réinitialiser lors du redimensionnement
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            navMenu.classList.remove('active');
            burgerMenu.classList.remove('active');
            document.body.style.overflow = '';
            
            hasSubmenuItems.forEach(item => {
                item.classList.remove('active');
            });
        }
    });

    // Fermer le menu si on clique à l'extérieur
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            if (!e.target.closest('.navbar')) {
                navMenu.classList.remove('active');
                burgerMenu.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
    });
});