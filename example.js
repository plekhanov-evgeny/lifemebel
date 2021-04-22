//pmdbeauty.ru 
//Event header menu
	let menuHeader = document.querySelectorAll('.nav-desktop a.products')[0]
	menuHeader.addEventListener('click', function () {
		if (menuHeader.querySelectorAll('.open')[0] == undefined) {
			menuHeader.classList.add('active');
			document.querySelectorAll('.header-menu')[0].classList.add('open');
			menuHeader.querySelectorAll('.icon-down-arrow')[0].classList.add('open');
			menuHeader.querySelectorAll('.no-transition')[0].classList.add('pink');
			document.querySelectorAll('.sub-menu.nav-links')[0].style.display = 'flex';
		} else {
			menuHeader.classList.remove('active');
			document.querySelectorAll('.header-menu')[0].classList.remove('open');
			menuHeader.querySelectorAll('.icon-down-arrow')[0].classList.remove('open');
			menuHeader.querySelectorAll('.no-transition')[0].classList.remove('pink');
			document.querySelectorAll('.sub-menu.nav-links')[0].style.display = 'none';
		}
	})

	let mobileMenuHeader = document.querySelectorAll('.hamburger')[0]
	mobileMenuHeader.addEventListener('click', function () {
		if (document.querySelectorAll('.header-menu')[0].classList.contains('open')) {
			document.querySelectorAll('.header-menu')[0].classList.remove('open');
			document.querySelectorAll('body')[0].classList.remove('no-scroll');
			mobileMenuHeader.classList.remove('is-active');
			document.querySelectorAll('.app-mobile-menu nav.menu')[0].style.display = 'none';
		} else {
			document.querySelectorAll('.header-menu')[0].classList.add('open');
			document.querySelectorAll('body')[0].classList.add('no-scroll');
			mobileMenuHeader.classList.add('is-active');
			document.querySelectorAll('.app-mobile-menu nav.menu')[0].style.display = 'block';
		}
	})

//lignestbarth.ru
//JQUERY (работа главного слайдера)
$(function () {
    var owl = $('.owl-carousel.big__slider').owlCarousel({
        items: 1,
        slideBy: 1,
        lazyLoad: true,
        loop: true,
        margin: 0,
        URLhashListener: true,
        startPosition: 0,
        autoplay: true,
        autoplaySpeed: true,
        autoplayTimeout: 5000,
        nav: true,
        navText: ["<img src='/local/templates/main/img/slider/prev.svg'>", "<img src='/local/templates/main/img/slider/next.svg'>"],
    });

    const timeProgress = 5;

    function sliderProgress(idElement, time) {
        var start = 0;
        var time = Math.round(time * 1000 / 100);
        var intervalId = setInterval(function () {
            if (start > 100) {
                clearInterval(intervalId);
            } else {
                owl.on('translated.owl.carousel', function (event) {
                    clearInterval(intervalId);
                })
                $(idElement).width(start + '%');
            }
            start++;
        }, time);
    }

    var active = $('.button__block:eq(0) .button__progress .js-progress').addClass('js-progress-active');
    sliderProgress(active, timeProgress);

    owl.on('translated.owl.carousel', function (event) {
        $('.js-progress').width(0);
        $('.button__url').removeClass('active');
        $('.button__block:eq(' + event.page.index + ') .button__url').addClass('active');
        active = $('.button__block:eq(' + event.page.index + ') .button__progress .js-progress').width(0);

        if (event.page.index > 0) {
            let i = event.page.index;
            if (i == 1) {
                $('.js-progress').width(0);
                $('.button__block:eq(0) .button__progress .js-progress').width(100 + '%');
            } else {
                while (i--) {
                    $('.button__block:eq(' + i + ') .button__progress .js-progress').width(100 + '%');
                }
            }
        } else if (event.page.index == 0) {
            $('.js-progress').width(0);
        }
        sliderProgress(active, timeProgress);
    })
});
