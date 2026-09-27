document.addEventListener('DOMContentLoaded', function () {
    var toggles = document.querySelectorAll('.sidebar-nav .nav-parent');

    toggles.forEach(function (toggle) {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();

            var item = toggle.closest('.has-submenu');
            if (!item) return;

            item.classList.toggle('open');
        });
    });
});