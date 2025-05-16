window.addEventListener('scroll', function () {
    document.getElementById('header-nav').classList.toggle('headernav-scroll', window.scrollY > 135);
})
$(document).ready(function () {
    $(window).scroll(function () {
        if ($(this).scrollTop() > 500) {
            $('#top').fadeIn();
        } else {
            $('#top').fadeOut();
        }
    });

    $('#top').click(function () {
        $('html, body').animate({ scrollTop: 0 }, 300);
        return false;
    });
});