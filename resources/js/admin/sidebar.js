$(document).ready(function () {

    if ($('[data-bs-toggle="sidebar"]').length) {
        $('[data-bs-toggle="sidebar"]').click(function (event) {
            event.preventDefault();
            $('.app').toggleClass('sidenav-toggled');
        });
    }

});
