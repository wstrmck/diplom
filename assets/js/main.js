window.addEventListener('scroll', function () {
    document.getElementById('header-nav').classList.toggle('headernav-scroll', window.scrollY > 135);
})
$(document).ready(function () {
    // Показать или скрыть кнопку в зависимости от прокрутки
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) { // Увеличим порог до 100 пикселей
            $('#top').fadeIn();
        } else {
            $('#top').fadeOut();
        }
    });

    // Прокрутка страницы вверх при клике на кнопку
    $('#top').click(function (event) {
        event.preventDefault(); // Предотвращаем стандартное поведение
        $('html, body').animate({ scrollTop: 0 }, 400); // Установим разумное время анимации
        return false;
    });
});