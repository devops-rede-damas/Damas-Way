document.addEventListener('DOMContentLoaded', function() {
    var toggle = document.getElementById('sidebarToggle');
    var sidebar = document.getElementById('sidebar');
    var backdrop = document.getElementById('sidebarBackdrop');
    var mainContent = document.querySelector('.main-content');

    function isDesktop() {
        return window.innerWidth >= 992;
    }

    // Restaurar estado salvo no desktop
    if (isDesktop() && localStorage.getItem('dw_sidebar_collapsed') === '1') {
        sidebar.classList.add('collapsed');
        mainContent.classList.add('sidebar-collapsed');
    }

    if (toggle) {
        toggle.addEventListener('click', function() {
            if (isDesktop()) {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('sidebar-collapsed');
                localStorage.setItem('dw_sidebar_collapsed', sidebar.classList.contains('collapsed') ? '1' : '0');
            } else {
                sidebar.classList.toggle('show');
                backdrop.classList.toggle('show');
            }
        });
    }

    if (backdrop) {
        backdrop.addEventListener('click', function() {
            sidebar.classList.remove('show');
            backdrop.classList.remove('show');
        });
    }

    // Tooltip no sidebar collapsed
    var tooltip = document.createElement('div');
    tooltip.className = 'fp-sidebar-tooltip';
    document.body.appendChild(tooltip);

    var navLinks = sidebar.querySelectorAll('.sidebar-nav .nav-link');
    navLinks.forEach(function(link) {
        var text = link.textContent.trim();
        if (text) {
            link.setAttribute('data-title', text);
        }

        link.addEventListener('mouseenter', function() {
            if (!sidebar.classList.contains('collapsed')) return;
            var title = this.getAttribute('data-title');
            if (!title) return;

            var rect = this.getBoundingClientRect();
            tooltip.textContent = title;
            tooltip.style.top = (rect.top + rect.height / 2) + 'px';
            tooltip.style.left = (rect.right + 10) + 'px';
            tooltip.classList.add('show');
        });

        link.addEventListener('mouseleave', function() {
            tooltip.classList.remove('show');
        });
    });
});
